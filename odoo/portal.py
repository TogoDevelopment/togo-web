from odoo import models, http, _
from odoo.addons.portal.controllers.portal import CustomerPortal, pager as portal_pager
from odoo.exceptions import AccessError, MissingError
from odoo.http import request

from datetime import datetime, date
from itertools import groupby
from collections import Counter
from operator import itemgetter

def audit_order_transactions(order_transactions):
    order_transactions = list(filter(lambda x: x["partner_id"] is not None, order_transactions))
    order_transactions_agg = {k: [dict[k] for dict in order_transactions] for k in order_transactions[0]}

    mtesc = list(filter(lambda trans: "MTESC" == trans["move_name"].split("/")[0], order_transactions))
    rlesc = list(filter(lambda trans: "RLESC" == trans["move_name"].split("/")[0], order_transactions))
    misc =  list(filter(lambda trans: "MISC"  == trans["move_name"].split("/")[0], order_transactions))
    rinv =  list(filter(lambda trans: "RINV"  == trans["move_name"].split("/")[0], order_transactions))
    inv =   list(filter(lambda trans: "INV"   == trans["move_name"].split("/")[0] or "TRESC" == trans["move_name"].split("/")[0], order_transactions))

    total_credit = round(sum(order_transactions_agg["credit"]), 2)
    total_debit = round(sum(order_transactions_agg["debit"]), 2)
    count_transactions = len(order_transactions)
    count_mtesc = len(mtesc)
    count_rlesc = len(rlesc)
    count_misc = len(misc)
    count_rinv = len(rinv)
    count_inv = len(inv)
    
    fingerprint = lambda transaction: f"{transaction['ref']}/{transaction['journal_id']}/{transaction['partner_id']}/{transaction['debit']}/{transaction['credit']}"
    duplicates = {fprint: count for fprint, count in Counter([fingerprint(trans) for trans in order_transactions]).items() if count > 1}

    summary = {
        "order_id": order_transactions[0]["ref"],
        "total_credit": total_credit,
        "total_debit": total_debit,
        "count_transactions": count_transactions,
        "count_mtesc": count_mtesc,
        "count_rlesc": count_rlesc,
        "count_misc": count_misc,
        "count_inv": count_inv,
        "count_rinv": count_rinv,
        # "duplicates": duplicates,
    }
    checks = {
        "escrow_count_balanced": abs(count_mtesc - count_rlesc) == count_misc,
        "inv_count_balanced": count_inv - count_rinv == 3,
        "transaction_count_balanced": count_transactions == count_mtesc + count_rlesc + count_inv + count_misc + count_rinv,
        "no_duplicates": not bool(len(duplicates)),
        "balanced": total_credit - total_debit == 0,
    }
    checks["passed"] = all(checks.values())
    return {**summary, **checks}

class PortalAccount(CustomerPortal):
    @http.route('/tables', type='json', auth='user')
    def show_tables(self, order_id):
        # request.env.cr.execute(f"SELECT * FROM account_move_line")
        # return request.env.cr.dictfetchall()
        request.env.cr.execute(f"SELECT * FROM account_move_line WHERE ref = '{order_id}' OR ref LIKE '%{order_id}%'")
        transactions = request.env.cr.dictfetchall()
        return transactions

    @http.route('/audit/orders', type='json', auth='user')
    def audit_orders(self):
        request.env.cr.execute(f"SELECT * FROM account_move_line WHERE ref != ''")
        transactions = request.env.cr.dictfetchall()

        key = lambda x: list(filter(lambda y: y.isnumeric(), x.split(" ")))[0]
        grouped_transactions = groupby(sorted(transactions, key=key), key=key)
        
        audits = [audit_order_transactions(order_transactions) for _, order_transactions in grouped_transactions]
        failed = filter(lambda audit: not audit["passed"], audits)
        return list(failed)    
    
    @http.route('/audit/order', type='json', auth='user')
    def audit_order(self, order_id):
        request.env.cr.execute(f"SELECT * FROM account_move_line WHERE ref = '{order_id}' OR ref LIKE '%{order_id}%'")
        transactions = request.env.cr.dictfetchall()
        return audit_order_transactions(transactions)
        

    @http.route('/create/invoice', type='json', auth='user')
    def create_invoice(self, partner_id, price_unit, tax=0.0):
        """
        Create new invoice for the transporter
        """
        tax_id = request.env['account.tax'].search([('amount', '=', tax), ('type_tax_use', '=', 'sale')], limit=1).id
        product_id = request.env['product.template'].search([('default_code', '=', 'SC')], limit=1)
        # edited, get partner by togo id
        #partner = request.env['res.partner'].search([('togo_partner_id','=', 40)], limit=1)
        move = request.env['account.move'].create({
            'type': 'out_invoice',
            'partner_id':  partner_id,
            'invoice_line_ids': [(0, 0, {
                'quantity': 1,
                'price_unit': price_unit,
                'product_id': product_id.id,
                'tax_ids': [tax_id],
                #'account_id': partner.property_account_payable_id.id # edited, account_id added
            })],
        })
        move.post()

    @http.route('/my/invoice', type='json', auth='user')
    def get_invoices(self, partner_id):
        """
        Return all the invoice lines that are related to a specific partner
        """
        domain = [('type', 'in', ('out_invoice', 'out_refund', 'in_invoice', 'in_refund', 'out_receipt', 'in_receipt'))]
        domain += [('partner_id', '=', partner_id)]
        invoices_ids = request.env['account.move'].search(domain)
        invoices_lines = []
        for inv in invoices_ids:
            for line in inv.invoice_line_ids:
                vals = {
                    'id': inv.id,
                    'name': inv.name,
                    'description': line.name,
                    'price_unit': line.price_unit,
                    'amount_tax': inv.amount_tax,
                    'amount_total': line.price_subtotal,
                }
                invoices_lines.append(vals)
        data = {'status': 200, 'response': invoices_lines, 'message': 'Success'}
        return data

    @http.route('/partner/entries', type='json', auth='user')
    def get_journal_entries(self, customer_id):
        """
        Return All the journal entries that are related to specific partner.
        """

        customer_id = int(customer_id)

        # edited (get customer odoo-id by customer-id)
        partner=request.env['res.partner'].search([('togo_partner_id','=',customer_id)],limit=1)
        if not partner:
            return {'status':404, 'message': 'No Partner Found For The Partner Id %s'%customer_id}
        domain = [('partner_id', '=', partner.id)]
        account_move_line_ids = request.env['account.move.line'].search(domain)
        line_ids = []
        for line in account_move_line_ids:
            if line.move_id.state != 'cancel':
                #amount = line.debit if line.debit else - line.credit
                vals = {
                    # 'journal_id':  dir(line.move_id.journal_id),
                    'id': line.id,
                    #'date': line.date,
                    'create_date': line.create_date,
                    'time': line.create_date.strftime("%m/%d/%Y %H:%M:%S").split(' ')[1],
                    'date': line.create_date.strftime("%m/%d/%Y %H:%M:%S").split(' ')[0],
                    # 'date_maturity': line.date_maturity,
                    'ref':  line.move_id.ref if line.move_id.ref else '',
                    'journal_id_name':  line.move_id.journal_id.name if line.move_id.journal_id.name else '',
                    #'amount_total_signed':  line.move_id.amount_total_signed if line.move_id.amount_total_signed else '',
                    #'state':  line.move_id.state if line.move_id.state else '',
                    # 'move_id name':  line.move_id.name if line.move_id.name else '',
                    # 'move_id date':  line.move_id.date if line.move_id.date else '',
                    # 'move_id':  line.move_id,
                    # 'name': line.name,
                    'debit': line.debit,
                    'credit': line.credit,
                    #'amount': amount,
                    # 'product_id': line.product_id,
                    #'quantity': line.quantity,
                    'move_name': line.move_name,
                    #'price_total': line.price_total,
                    #'price_subtotal': line.price_subtotal,
                    # 'display_type': line.display_type,
                    #'account_internal_type': line.account_internal_type,
                    #'amount_currency': line.amount_currency,
                }
                line_ids.append(vals)

        sortedArray = sorted(line_ids, key=lambda x: datetime.strptime(x['create_date'].strftime("%m/%d/%y %H:%M"), '%m/%d/%y %H:%M'), reverse=True)

        data = {'status': 200, 'response': sortedArray, 'message': 'Success'}
        return data
    

    @http.route('/partner/daily_entries', type='json', auth='user')
    def get_daily_journal_entries(self, customer_id):
        """
        Return All the journal entries that are related to a specific partner for today.
        """
        customer_id = int(customer_id)

        # edited (get customer odoo-id by customer-id)
        partner = request.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        if not partner:
            return {'status': 404, 'message': 'No Partner Found For The Partner Id %s' % customer_id}

        # Calculate today's date in Odoo's date format (YYYY-MM-DD)
        today_date = date.today().strftime('%Y-%m-%d')

        # Modify the domain to filter entries for today
        domain = [
            ('partner_id', '=', partner.id),
            ('create_date', '>=', today_date + ' 00:00:00'),
            ('create_date', '<=', today_date + ' 23:59:59'),
        ]

        account_move_line_ids = request.env['account.move.line'].search(domain)
        line_ids = []

        for line in account_move_line_ids:
            if line.move_id.state != 'cancel':
                vals = {
                    'id': line.id,
                    'create_date': line.create_date,
                    'time': line.create_date.strftime("%m/%d/%Y %H:%M:%S").split(' ')[1],
                    'date': line.create_date.strftime("%m/%d/%Y %H:%M:%S").split(' ')[0],
                    'ref': line.move_id.ref if line.move_id.ref else '',
                    'journal_id_name': line.move_id.journal_id.name if line.move_id.journal_id.name else '',
                    'debit': line.debit,
                    'credit': line.credit,
                    'move_name': line.move_name,
                }
                line_ids.append(vals)

        sortedArray = sorted(line_ids, key=lambda x: datetime.strptime(x['create_date'].strftime("%m/%d/%y %H:%M"), '%m/%d/%y %H:%M'), reverse=True)

        data = {'status': 200, 'response': sortedArray, 'message': 'Success'}
        return data


    ## edited (new function to get transactions related to a specific order)

    @http.route('/partner_order/entries', type='json', auth='user')
    def get_journal_entries_by_order_id(self, customer_id, order_id):
        """
        Return All the journal entries that are related to specific partner AND to a specific order.
        """

        customer_id = int(customer_id)
        order_id = int(order_id)

        # edited
        partner=request.env['res.partner'].search([('togo_partner_id','=',customer_id)],limit=1)
        if not partner:
            return {'status':404, 'message': 'No Partner Found For The Partner Id %s'%customer_id}
        domain = [('partner_id', '=', partner.id)]
        account_move_line_ids = request.env['account.move.line'].search(domain)
        line_ids = []

        for line in account_move_line_ids:
            #amount = line.debit if line.debit else - line.credit

            if line.move_id.ref:
                if line.move_id.ref.isnumeric():
                    if int(line.move_id.ref) == order_id:
                        if line.move_id.state != 'cancel':
                            vals = {
                                # 'journal_id':  dir(line.move_id.journal_id),
                                'id': line.id,
                                #'date': line.date,
                                'create_date': line.create_date,
                                'time': line.create_date.strftime("%m/%d/%Y %H:%M:%S").split(' ')[1],
                                'date': line.create_date.strftime("%m/%d/%Y %H:%M:%S").split(' ')[0],
                                # 'date_maturity': line.date_maturity,
                                'ref':  line.move_id.ref if line.move_id.ref else '',
                                'journal_id_name':  line.move_id.journal_id.name if line.move_id.journal_id.name else '',
                                #'amount_total_signed':  line.move_id.amount_total_signed if line.move_id.amount_total_signed else '',
                                #'state':  line.move_id.state if line.move_id.state else '',
                                # 'move_id name':  line.move_id.name if line.move_id.name else '',
                                # 'move_id date':  line.move_id.date if line.move_id.date else '',
                                # 'move_id':  line.move_id,
                                # 'name': line.name,
                                'debit': line.debit,
                                'credit': line.credit,
                                #'amount': amount,
                                # 'product_id': line.product_id,
                                #'quantity': line.quantity,
                                'move_name': line.move_name,
                                #'price_total': line.price_total,
                                #'price_subtotal': line.price_subtotal,
                                # 'display_type': line.display_type,
                                #'account_internal_type': line.account_internal_type,
                                #'amount_currency': line.amount_currency,
                            }
                            line_ids.append(vals)

        sortedArray = sorted(line_ids, key=lambda x: datetime.strptime(x['create_date'].strftime("%m/%d/%y %H:%M"), '%m/%d/%y %H:%M'), reverse=True)

        data = {'status': 200, 'response': sortedArray, 'message': 'Success'}
        return data

    @http.route('/order_entries/entries', type='json', auth='user')
    def get_journal_entries_for_order(self, order_id):

        order_id = int(order_id)

        request.env.cr.execute(f"SELECT * FROM account_move_line WHERE ref = '{order_id}' OR ref LIKE '% {order_id} %'")
        sortedArray = request.env.cr.dictfetchall()
        data = {'status': 200, 'response': sortedArray, 'message': 'Success'}
        return data


    @http.route('/account/get_account_balance', type='json', auth="user", cors='*')
    def get_account_balance(self, account_id=None, from_date = None, to_date = None):
        """Get Account Balance From Odoo By Json-rpc api"""
        status = False
        response  = {'status':False,'statusMessage':''}
        if not account_id:
            response['statusMessage'] = "Account Id required to Get Account Balance"
        else:
            try:
                domain = [
                ('account_id', 'in', [int(account_id)]),
                ('display_type', 'not in', ('line_section', 'line_note')),
                ('parent_state', '!=', 'cancel')
                ]
                if from_date:
                    domain.append(("date",">=", from_date))
                if to_date:
                    domain.append(("date","<=", to_date))
                move_lines = request.env["account.move.line"].search(domain)
                response.update({
                        "credit_amount":0.0,
                        "debit_amount":0.0,
                        "differ_balance":0.0,
                        "status":True
                    })
                if move_lines:
                    credit_amount = sum(move_lines.mapped("credit"))
                    debit_amount = sum(move_lines.mapped("debit"))
                    differ_balance = float(credit_amount) - float(debit_amount)
                    response.update({
                        "credit_amount":credit_amount,
                        "debit_amount":debit_amount,
                        "differ_balance":differ_balance,
                        "status":True
                    })
            except Exception as e:
                response["statusMessage"] =  str(e)
        return response
