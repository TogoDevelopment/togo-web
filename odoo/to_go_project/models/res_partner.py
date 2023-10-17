# -*- coding: utf-8 -*-

from odoo import models, fields, api
from datetime import datetime, date
import time


class Transaction(models.Model):
    _name = 'res.palpay'
    transaction_palpay = fields.Char('PAYPAL Transaction')
    amount = fields.Float("Amount")
    mobile = fields.Char("Mobile")

    def check_status(self, transaction_palpay):
        transaction_palpay = self.env['res.palpay'].search([('transaction_palpay', '=', transaction_palpay)], limit=1)
        if transaction_palpay:
            return {'status': 'SUCCESS'}


        else:
            return {'status': 'FAILED'}


class ResPartner(models.Model):
    _inherit = 'res.partner'
    togo_partner_id = fields.Char('Togo Partner ID ')
    identity_no = fields.Char("Identity No")

    def is_valid_customer(self, mobile):
        mobile = "%" + mobile[-9:]
        partner_id = self.env['res.partner'].search([('mobile', 'ilike', mobile)], limit=1)
        if partner_id:
            partner_data = {'name': partner_id.name,
                            'identity_no': partner_id.identity_no,

                            }
            return partner_data
        else:

            return {'name': 'NOT FOUND'}

    def recharge_balance(self, mobile, amount, transaction_palpay):

        journal = self.env['account.journal']
        debit_account = self.env['account.account']
        credit_account = self.env['account.account']

        company_currency = self.env.user.company_id.currency_id
        mobile = "%" + mobile[-9:]
        partner = self.env['res.partner'].search([('mobile', 'ilike', mobile)], limit=1)

        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'RPP%'), ('currency_id', 'in', [company_currency.id, False])], limit=1)
        debit_account = self.env['account.account'].search([('code', '=', '101001')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APC%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), False, partner,
                                            False, 0)
        if move_id:
            self.env['res.palpay'].create({
                'mobile': mobile,
                'transaction_palpay': transaction_palpay,
                'amount': amount

            })

            return {'status': 'SUCCESS'}

        else:
            return {'status': 'FAILED'}

    def cancel_recharge(self, mobile, amount):
        journal = self.env['account.journal']
        debit_account = self.env['account.account']
        credit_account = self.env['account.account']

        company_currency = self.env.user.company_id.currency_id
        partner = self.env['res.partner'].search([('mobile', '=', mobile)], limit=1)

        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'CRPP%'), ('currency_id', 'in', [company_currency.id, False])], limit=1)
        credit_account = self.env['account.account'].search([('code', '=', '101001')], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'APC%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), False, partner,
                                            False, 0)
        if move_id:
            return 'SUCCESS'
        else:
            return 'FAILED'

    def create_partner(self, id, mobile, type):

        id = int(id)

        partner_obj = self.env['res.partner']
        partner_obj.create({'name': mobile,
                            'mobile': mobile,
                            'togo_partner_id': id,
                            'customer_rank': 1 if type == 'Client' else 0,
                            'supplier_rank': 1 if type == 'Transporter' else 0,
                            })
        partner_id = self.env['res.partner'].search([('togo_partner_id', '=', id)], limit=1)
        return partner_id.id
    
    # this function added to create partner record with all its parameters
    def create_new_partner(self, id, mobile, type, IDNo, email, name):

        id = int(id)

        partner_obj = self.env['res.partner']
        partner_obj.create({'name': mobile,
                            'mobile': mobile,
                            'togo_partner_id': id,
                            'customer_rank': 1 if type == 'Client' else 0,
                            'supplier_rank': 1 if type == 'Transporter' else 0,
                            'identity_no': IDNo,
                            'email': email,
                            'name': name
                            })
        partner_id = self.env['res.partner'].search([('togo_partner_id', '=', id)], limit=1)
        return partner_id.id

    def edit_partner_data(self, id, data):

        id = int(id)

        partner = self.search([('togo_partner_id', '=', id)], limit=1)
        if partner:
            partner.write(data)
            return 1
        else:
            return -1

    def delete_partner(self, id):

        id = int(id)

        partner = self.search([('togo_partner_id', '=', id)], limit=1)
        if partner:
            partner.unlink()
            return 1
        else:
            return -1

    # edited
    def can_request(self, customer_id, amount):

        customer_id = int(customer_id)

        customer = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        return 1 if (self.get_balance(customer_id)) > float(amount) else -1

    def get_temp_balance(self):

        """ partner = self.env['res.partner'].search([('togo_partner_id', '=', 3)], limit=1)
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)
        balance = self.env['account.move.line'].search([('partner_id', '=', partner.id), ('account_id', '=', account.id)]).browse(0).balance

        return balance """

        """ partner = self.env['res.partner'].search([('togo_partner_id', '=', 3)], limit=1)
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)
        lines = self.env['account.move.line'].search([('partner_id', '=', partner.id), ('account_id', '=', account.id)])
        data = lines.read()
        return data """

        """ partner = self.env['res.partner'].search([('togo_partner_id', '=', 3)], limit=1)
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)
        lines = self.env['account.move.line'].search([('partner_id', '=', partner.id), ('account_id', '=', account.id)])
        balance = sum(lines.mapped('balance'))
        return balance """

        """ partners = self.env['res.partner'].search([])
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)
        total_balance = 0
        for partner in partners:
            lines = self.env['account.move.line'].search([('partner_id', '=', partner.id), ('account_id', '=', account.id)])
            partner_balance = sum(lines.mapped('balance'))
            total_balance += partner_balance
        return total_balance """

        # First, get the account you want to get the balance for
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)

        # Then, execute a search on the account.move.line model to get the total balance
        balance = sum(self.env['account.move.line'].search([('account_id', '=', account.id)]).mapped('balance'))

        return balance


    def get_user_temp_balance(self, id):
        customer_id = int(id)

        """ partner = self.env['res.partner'].search([('togo_partner_id', '=', 3)], limit=1)
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)
        balance = self.env['account.move.line'].search([('partner_id', '=', partner.id), ('account_id', '=', account.id)]).browse(0).balance

        return balance """

        """ partner = self.env['res.partner'].search([('togo_partner_id', '=', 3)], limit=1)
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)
        lines = self.env['account.move.line'].search([('partner_id', '=', partner.id), ('account_id', '=', account.id)])
        data = lines.read()
        return data """

        partner = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)
        lines = self.env['account.move.line'].search([('partner_id', '=', partner.id), ('account_id', '=', account.id)])
        balance = sum(lines.mapped('balance'))
        return balance

        """ partner = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)
        lines = self.env['account.move.line'].search([('partner_id', '=', partner.id), ('account_id', '=', account.id)])
        balance = sum(line.balance for line in lines if line.debit - line.credit > 0)
        reversed_move_lines = lines.filtered(lambda line: line.move_id.state == 'posted' and line.move_id.journal_id.type == 'reversal')
        reversed_balance = sum(reversed_move_lines.mapped('balance')) if reversed_move_lines else 0.0
        return balance - reversed_balance """

        """ partner = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)
        lines = self.env['account.move.line'].search([('partner_id', '=', partner.id), ('account_id', '=', account.id)])
        
        normal_moves = lines.filtered(lambda line: line.move_id.state == 'posted' and not line.move_id.reversed_entry_id)
        reversed_moves = lines.filtered(lambda line: line.move_id.state == 'posted' and line.move_id.reversed_entry_id)
        
        normal_balance = sum(normal_moves.mapped('balance'))
        reversed_balance = -sum(reversed_moves.mapped('balance'))
        
        return normal_balance + reversed_balance """
    
    def get_user_temp_transactions(self, id):
        customer_id = int(id)

        partner = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        account = self.env['account.account'].search([('code', 'ilike', '101507')], limit=1)
        lines = self.env['account.move.line'].search([('partner_id', '=', partner.id), ('account_id', '=', account.id)], order='date DESC')
        transactions = []
        for line in lines:
            transaction = {
                'date': line.date,
                'name': line.move_id.name,
                'create_date': line.create_date,
                #'time': line.create_date.strftime("%m/%d/%Y %H:%M:%S").split(' ')[1],
                #'date': line.create_date.strftime("%m/%d/%Y %H:%M:%S").split(' ')[0],
                'credit': line.credit,
                'debit': line.debit,
                'balance': line.balance,
            }
            transactions.append(transaction)
        sortedArray = sorted(transactions, key=lambda x: datetime.strptime(x['create_date'].strftime("%m/%d/%y %H:%M"), '%m/%d/%y %H:%M'), reverse=True)
        return sortedArray
        


    #################################################################################################

    ###### move from temp account to partner account (lend)
    def move_from_temp_to_partner(self, customer_id, amount):

        customer_id = int(customer_id)

        company_currency = self.env.user.company_id.currency_id
        partnerC = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        partnerD = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'TRNS3%'), ('currency_id', 'in', [company_currency.id, False])], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', '101507%')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal,
                                            float(amount), partnerD, partnerC, False, False, 0)
        if move_id:
            return 1
        else:
            return -1
        
    ###### move to temp account partner partner account (collect)
    def move_from_partner_to_temp(self, customer_id, amount):

        customer_id = int(customer_id)

        company_currency = self.env.user.company_id.currency_id
        partnerC = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        partnerD = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'TRNS3%'), ('currency_id', 'in', [company_currency.id, False])], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', '101507%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal,
                                            float(amount), partnerD, partnerC, False, False, 0)
        if move_id:
            return 1
        else:
            return -1
        
    ###################################################################################################


    # edited
    def get_balance(self, customer_id):

        customer_id = int(customer_id)

        customer = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        return customer.debit - customer.credit

    def get_JOD_exchange_rate(self, amount):
        amount_in_shekel = float(amount)
    
        # Retrieve the exchange rate dynamically from Odoo
        currency = self.env.ref('base.JOD')  # Assuming Jordanian dinar is denoted by 'JOD' in Odoo
        exchange_rate = currency.rate or 0.0

        amount_in_dinar = amount_in_shekel * exchange_rate

        return amount_in_dinar
    
    def JOD_to_ILS(self, amount):
        amount_in_dinar = float(amount)
    
        # Retrieve the exchange rate dynamically from Odoo
        currency = self.env.ref('base.JOD')  # Assuming Jordanian dinar is denoted by 'JOD' in Odoo
        exchange_rate = currency.rate or 0.0

        amount_in_shekel = amount_in_dinar * exchange_rate

        return amount_in_shekel

    def confirm_request(self, customer_id, amount):

        customer_id = int(customer_id)

        partner = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'Res%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'APC%')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'ART%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), partner, partner,
                                            False, 0)
        if move_id:
            return 1
        else:
            return -1

    def cancel_request(self, customer_id, amount):

        customer_id = int(customer_id)

        partner = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'Can%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APC%')], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'ART%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), partner, partner,
                                            False, 0)
        if move_id:
            return 1
        else:
            return -1

    def get_cancellation_fees_amount(self):
        cancellation_fees = self.env['product.product'].search([('default_code', '=', 'CF')], limit=1)
        return cancellation_fees.list_price

    def cancellation_fees_discount(self, partner_id):

        partner_id = int(partner_id)

        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'CFD%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        credit_account = self.env['account.account'].search([('code', '=', '411105')], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'APC%')], limit=1)
        cancellation_fees = self.env['product.product'].search([('default_code', '=', 'CF')], limit=1)
        partner = self.env['res.partner'].search([('togo_partner_id', '=', partner_id)], limit=1)

        cancellation_fees_amount = cancellation_fees.list_price
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(cancellation_fees_amount),
                                            partner, False, False, 0)
        if move_id:
            return 1
        else:
            return -1

    def deliver_to_transporter(self, customer_id, amount):

        customer_id = int(customer_id)

        partner = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'DTT%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'ART%')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'Escrow%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), partner, False,
                                            False, 0)
        if move_id:
            return 1
        else:
            return -1

    ##############

    def delivery_request(self, vendor_id, togo_ratio, amount):

        vendor_id = int(vendor_id)

        tax_account = self.env['account.tax'].search([('type_tax_use', '=', 'sale')], limit=1)
        Togo_amount = float(amount) * (float(togo_ratio) / 100)
        amount_tax = float(Togo_amount) * (float(tax_account.amount) / 100)
        vendor_amount = float(amount) - (Togo_amount + amount_tax)
        partner = self.env['res.partner'].search([('togo_partner_id', '=', vendor_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'Del%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'Escrow%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), False, partner,
                                            False, 0)
        if float(togo_ratio) != 0:
            journal = self.env['account.journal'].search(
                [('code', 'ilike', 'TDel%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
                limit=1)
            credit_account = self.env['account.account'].search([('code', '=', '411105')], limit=1)
            debit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
            tax_account = self.env['account.account'].search([('code', '=', '111200')], limit=1)
            move_id = self.create_journal_entry(credit_account, debit_account, journal, Togo_amount, partner, False,
                                                tax_account, amount_tax)
        if move_id:
            return {'total_amount': amount, 'togo_discount': Togo_amount, 'tax_discount': amount_tax,
                    'date_invoice': datetime.today().strftime('%Y-%m-%d'),
                    'time_invoice': datetime.today().strftime('%H:%M:%S')}
        else:
            return -1

    def recharge_customer_balance(self, partner_id, recharge_method, amount):

        partner_id = int(partner_id)

        journal = self.env['account.journal']
        debit_account = self.env['account.account']
        credit_account = self.env['account.account']

        company_currency = self.env.user.company_id.currency_id
        partner = self.env['res.partner'].search([('togo_partner_id', '=', partner_id)], limit=1)

        if recharge_method == 'bank':
            journal = self.env['account.journal'].search(
                [('code', 'ilike', 'RCB%'), ('currency_id', 'in', [company_currency.id, False])], limit=1)
            debit_account = self.env['account.account'].search([('code', '=', '101001')], limit=1)

        elif recharge_method == 'cash':
            journal = self.env['account.journal'].search(
                [('code', 'ilike', 'RCC%'), ('currency_id', 'in', [company_currency.id, False])], limit=1)
            debit_account = self.env['account.account'].search([('code', '=', '105001')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APC%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), False, partner,
                                            False, 0)
        if move_id:
            return 1
        else:
            return -1

    def create_journal_entry(self, credit_account, debit_account, journal, amount, partner_de, partner_cr, tax_account,
                             tax_amount, order_id=False):

        company_currency = self.env.user.company_id.currency_id
        date = datetime.today().strftime('%Y-%m-%d')
        journal_name = journal.with_context(ir_sequence_date=date).sequence_id.next_by_id()
        tax_debit = 0
        tax_credit = 0
        amount_currency_tax = 0

        move_vals = {
            'name': journal_name,
            'date': date,
            'ref': order_id if order_id else '',
            'company_id': journal.company_id.id,
            'journal_id': journal.id,
            'narration': '',
        }
        move = self.env['account.move'].create(move_vals)
        aml_obj = self.env['account.move.line'].with_context(check_move_validity=False)
        debit, credit, amount_currency, currency_id = aml_obj.with_context(
            date=datetime.today()).compute_amount_fields(amount, self.env.user.company_id.currency_id,
                                                         self.env.user.company_id.currency_id,
                                                         self.env.user.company_id.currency_id)
        tax_debit, tax_credit, amount_currency_tax, currency_id = aml_obj.with_context(
            date=datetime.today()).compute_amount_fields(tax_amount, self.env.user.company_id.currency_id,
                                                         self.env.user.company_id.currency_id,
                                                         self.env.user.company_id.currency_id)

        counterpart_aml_dict = self._get_shared_move_line_vals(debit + tax_debit, credit + tax_credit,
                                                               amount_currency + amount_currency_tax, move.id,
                                                               partner_de)
        vals = self._get_counterpart_move_line_vals(debit_account, journal)
        counterpart_aml_dict.update(vals)
        counterpart_aml = aml_obj.create(counterpart_aml_dict)
        ###############################
        liquidity_aml_dict = self._get_shared_move_line_vals(credit, debit, -amount_currency, move.id, partner_cr)
        vals = self._get_liquidity_move_line_vals(credit_account, journal)
        liquidity_aml_dict.update(vals)
        liquidity_aml = aml_obj.create(liquidity_aml_dict)
        ###############
        if tax_account:
            liquidity_aml_dict = self._get_shared_move_line_vals(tax_credit, tax_debit, -amount_currency_tax, move.id,
                                                                 partner_cr)
            vals = self._get_liquidity_move_line_vals(tax_account, journal)
            liquidity_aml_dict.update(vals)
            liquidity_aml = aml_obj.create(liquidity_aml_dict)
        ########3
        move.post()
        return move

    def _get_shared_move_line_vals(self, debit, credit, amount_currency, move_id, partner_id):

        return {
            'partner_id': partner_id.id if partner_id else '',
            'move_id': move_id,
            'debit': debit,
            'credit': credit,
            'amount_currency': amount_currency or False,
            'internal_note': "",
        }

    def _get_counterpart_move_line_vals(self, account_id_db, journal):

        return {
            'name': 'Debit Account',
            'account_id': account_id_db.id,
            'journal_id': journal.id,
            # 'currency_id': self.env.user.company_id.currency_id.id,
            'internal_note': "",
        }

    def _get_liquidity_move_line_vals(self, account_id_cr, journal):

        return {
            'name': 'Liquidity Account',
            'account_id': account_id_cr.id,
            'journal_id': journal.id,
            # 'currency_id': self.env.user.company_id.currency_id.id,
            'internal_note': "",
        }

    def exchange_cod_owner(self, from_owner, to_partner, order_id, amount):

        order_id = int(order_id)
        to_partner = int(to_partner)
        from_owner = int(from_owner)

        journal = self.env['account.journal']
        debit_account = self.env['account.account']
        credit_account = self.env['account.account']
        company_currency = self.env.user.company_id.currency_id
        partnerC = self.env['res.partner'].search([('togo_partner_id', '=', from_owner)], limit=1)
        partnerD = self.env['res.partner'].search([('togo_partner_id', '=', to_partner)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'TESCR%'), ('currency_id', 'in', [company_currency.id, False])], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal,
                                            float(amount), partnerD, partnerC, False, 0, order_id)
        if move_id:
            return 1
        else:
            return -1

    ###### NO neeed for reseve want to move the money directly to the escrow #### 
    def move_to_escrow(self, customer_id, order_id, amount):

        customer_id = int(customer_id)

        partner = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'MTESC%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'Escrow%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), partner, False,
                                            False, 0, order_id)
        if move_id:
            return 1
        else:
            return -1

            # edited

    def move_to_transfer_out_temp(self, customer_id, amount):

        customer_id = int(customer_id)

        journal = self.env['account.journal']
        debit_account = self.env['account.account']
        credit_account = self.env['account.account']
        company_currency = self.env.user.company_id.currency_id

        partnerC = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        partnerD = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)

        journal = self.env['account.journal'].search([('code', 'ilike', 'TRNS1%'), ('currency_id', 'in', [company_currency.id, False])], limit=1)
        
        debit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', '101505%')], limit=1)

        move_id = self.create_journal_entry(credit_account, debit_account, journal,
                                            float(amount), partnerD, partnerC, False, 0)
        if move_id:
            return 1
        else:
            return -1

    # edited
    def reverse_move_to_transfer_out_temp(self, customer_id, amount):

        customer_id = int(customer_id)

        journal = self.env['account.journal']
        debit_account = self.env['account.account']
        credit_account = self.env['account.account']
        company_currency = self.env.user.company_id.currency_id
        partnerC = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        partnerD = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'TRNS3%'), ('currency_id', 'in', [company_currency.id, False])], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', '101505%')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal,
                                            float(amount), partnerD, partnerC, False, 0)
        if move_id:
            return 1
        else:
            return -1

    # edited
    ###### added, when move to bank 
    def move_to_transfer_out_final(self, customer_id, amount, ref):

        customer_id = int(customer_id)

        partner = self.env['res.partner'].search([('togo_partner_id', '=', customer_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'TRNS2%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', '101505%')], limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', '101001%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), partner, False,
                                            False, 0, ref)
        if move_id:
            return 1
        else:
            return -1

            ############### Replace delivery_request with release_escrow

    def release_escrow(self, vendor_id, togo_ratio, order_id, amount):

        vendor_id = int(vendor_id)
        order_id = int(order_id)

        tax_account = self.env['account.tax'].search([('type_tax_use', '=', 'sale')], limit=1)
        Togo_amount = float(amount) * (float(togo_ratio) / 100)
        amount_tax = float(Togo_amount) * (float(tax_account.amount) / 100)
        vendor_amount = float(amount) - (Togo_amount + amount_tax)
        partner = self.env['res.partner'].search([('togo_partner_id', '=', vendor_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'RLESC%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'Escrow%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), False, partner,
                                            False, 0, order_id)
        if float(togo_ratio) != 0:
            """
            create_journal_entry replaced with create invoice below
            """
            
            # journal = self.env['account.journal'].search([('code','ilike','TRESC%'),('currency_id','in',[self.env.user.company_id.currency_id.id,False])], limit=1)
            # credit_account=self.env['account.account'].search([('code', '=', '411105')],limit=1)
            # debit_account=self.env['account.account'].search([('code', 'ilike', 'APV%')],limit=1)
            # tax_account=self.env['account.account'].search([('code', '=', '111200')],limit=1)
            # move_id=self.create_journal_entry(credit_account,debit_account,journal,Togo_amount,partner,False,tax_account,amount_tax, order_id)

            # move_id=self.create_invoice(partner.id, Togo_amount, 16)

            """
            Create invoice
            """
            if Togo_amount < 1:
                Togo_amount = 1

            tax_id = self.env['account.tax'].search([('amount', '=', 16), ('type_tax_use', '=', 'sale')], limit=1).id
            product_id = self.env['product.template'].search([('default_code', '=', 'SC')], limit=1)
            move = self.env['account.move'].create({
                'type': 'out_invoice',
                'partner_id': partner.id,
                'invoice_line_ids': [(0, 0, {
                    'quantity': 1,
                    'price_unit': Togo_amount,
                    'product_id': product_id,
                    'tax_ids': [(4, tax_id)],
                    'ref': order_id if order_id else '',
                })],
            })
            move.action_post()
            move.post()
            move_id = move
            # return move

        if move_id:
            return {'total_amount': amount, 'togo_discount': Togo_amount, 'tax_discount': amount_tax,
                    'date_invoice': datetime.today().strftime('%Y-%m-%d'),
                    'time_invoice': datetime.today().strftime('%H:%M:%S')}
        else:
            return -1


    def release_escrow_deal_noneCOD(self, vendor_id, order_id, amount):

        vendor_id = int(vendor_id)
        order_id = int(order_id)

        tax_account = self.env['account.tax'].search([('type_tax_use', '=', 'sale')], limit=1)
        Togo_amount = float(amount)
        amount_tax = float(Togo_amount) * (float(tax_account.amount) / 100)
        vendor_amount = float(amount) - (Togo_amount + amount_tax)
        partner = self.env['res.partner'].search([('togo_partner_id', '=', vendor_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'RLESC%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'Escrow%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), False, partner,
                                            False, 0, order_id)
        if float(amount) != 0:
            """
            create_journal_entry replaced with create invoice below
            """

            # journal = self.env['account.journal'].search([('code','ilike','TRESC%'),('currency_id','in',[self.env.user.company_id.currency_id.id,False])], limit=1)
            # credit_account=self.env['account.account'].search([('code', '=', '411105')],limit=1)
            # debit_account=self.env['account.account'].search([('code', 'ilike', 'APV%')],limit=1)
            # tax_account=self.env['account.account'].search([('code', '=', '111200')],limit=1)
            # move_id=self.create_journal_entry(credit_account,debit_account,journal,Togo_amount,partner,False,tax_account,amount_tax, order_id)

            # move_id=self.create_invoice(partner.id, Togo_amount, 16)

            """
            Create invoice
            """
            if Togo_amount < 1:
                Togo_amount = 1

            tax_id = self.env['account.tax'].search([('amount', '=', 16), ('type_tax_use', '=', 'sale')], limit=1).id
            product_id_DEL = self.env['product.template'].search([('default_code', '=', 'DEL')], limit=1)
            move = self.env['account.move'].create({
                'type': 'out_invoice',
                'partner_id': partner.id,
                'invoice_line_ids': [(0, 0, {
                    'quantity': 1,
                    'price_unit': Togo_amount / (1 + 0.16),
                    'product_id': product_id_DEL,
                    'tax_ids': [(4, tax_id)],
                    'ref': order_id if order_id else '',
                })]
            })

            # 'price_unit': Togo_amount / (1 + 0.16), ???!!

            move.action_post()
            move.post()
            move_id = move
            # return move

        if move_id:
            return {'total_amount': amount, 'togo_discount': Togo_amount, 'tax_discount': amount_tax,
                    'date_invoice': datetime.today().strftime('%Y-%m-%d'),
                    'time_invoice': datetime.today().strftime('%H:%M:%S')}
        else:
            return -1

    def release_escrow_deal_COD(self, vendor_id, togo_ratio, order_id, amount, delivery_price):

        vendor_id = int(vendor_id)
        order_id = int(order_id)

        tax_account = self.env['account.tax'].search([('type_tax_use', '=', 'sale')], limit=1)
        Togo_amount = float(amount) * (float(togo_ratio) / 100)
        del_price = float(delivery_price)
        amount_tax = float(Togo_amount) * (float(tax_account.amount) / 100)
        vendor_amount = float(amount) - (Togo_amount + amount_tax)
        partner = self.env['res.partner'].search([('togo_partner_id', '=', vendor_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'RLESC%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'Escrow%')], limit=1)
        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(amount), False, partner,
                                            False, 0, order_id)

        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(del_price), False, partner,
                                            False, 0, order_id)
        if float(togo_ratio) != 0:
            """
            create_journal_entry replaced with create invoice below
            """

            # journal = self.env['account.journal'].search([('code','ilike','TRESC%'),('currency_id','in',[self.env.user.company_id.currency_id.id,False])], limit=1)
            # credit_account=self.env['account.account'].search([('code', '=', '411105')],limit=1)
            # debit_account=self.env['account.account'].search([('code', 'ilike', 'APV%')],limit=1)
            # tax_account=self.env['account.account'].search([('code', '=', '111200')],limit=1)
            # move_id=self.create_journal_entry(credit_account,debit_account,journal,Togo_amount,partner,False,tax_account,amount_tax, order_id)

            # move_id=self.create_invoice(partner.id, Togo_amount, 16)

            """
            Create invoice
            """
            if Togo_amount < 1:
                Togo_amount = 1

            tax_id = self.env['account.tax'].search([('amount', '=', 16), ('type_tax_use', '=', 'sale')], limit=1).id
            tax = self.env['account.tax'].search([('id', '=', tax_id)], limit=1)
            tax_amount = tax.amount
            product_id_SC = self.env['product.template'].search([('default_code', '=', 'SC')], limit=1)
            product_id_DEL = self.env['product.template'].search([('default_code', '=', 'DEL')], limit=1)
            move = self.env['account.move'].create({
                'type': 'out_invoice',
                'partner_id': partner.id,
                'invoice_line_ids': [
                    (0, 0, {
                        'quantity': 1,
                        'price_unit': Togo_amount,
                        'product_id': product_id_SC,
                        'tax_ids': [(4, tax_id)],
                        'ref': order_id if order_id else '',
                    }),
                    (0, 0, {
                        'quantity': 1,
                        'price_unit': del_price / (1 + 0.16),
                        'product_id': product_id_DEL,
                        'tax_ids': [(4, tax_id)],
                        'ref': order_id if order_id else '',
                    })
                ]
            })
            move.action_post()
            move.post()
            move_id = move
            # return move

        else:
            """
            Create invoice
            """

            tax_id = self.env['account.tax'].search([('amount', '=', 16), ('type_tax_use', '=', 'sale')], limit=1).id
            product_id_DEL = self.env['product.template'].search([('default_code', '=', 'DEL')], limit=1)
            move = self.env['account.move'].create({
                'type': 'out_invoice',
                'partner_id': partner.id,
                'invoice_line_ids': [(0, 0, {
                    'quantity': 1,
                    'price_unit': del_price / (1 + 0.16),
                    'product_id': product_id_DEL,
                    'tax_ids': [(4, tax_id)],
                    'ref': order_id if order_id else '',
                })]
            })

            # 'price_unit': Togo_amount / (1 + 0.16), ???!!

            move.action_post()
            move.post()
            move_id = move
            # return move

        if move_id:
            return {'total_amount': amount, 'togo_discount': Togo_amount, 'tax_discount': amount_tax,
                    'date_invoice': datetime.today().strftime('%Y-%m-%d'),
                    'time_invoice': datetime.today().strftime('%H:%M:%S')}
        else:
            return -1
        
    def release_escrow_deal_delivery_price(self, vendor_id, order_id, delivery_price):

        vendor_id = int(vendor_id)
        order_id = int(order_id)
        del_price = float(delivery_price)
        
        partner = self.env['res.partner'].search([('togo_partner_id', '=', vendor_id)], limit=1)
        journal = self.env['account.journal'].search(
            [('code', 'ilike', 'RLESC%'), ('currency_id', 'in', [self.env.user.company_id.currency_id.id, False])],
            limit=1)
        credit_account = self.env['account.account'].search([('code', 'ilike', 'APV%')], limit=1)
        debit_account = self.env['account.account'].search([('code', 'ilike', 'Escrow%')], limit=1)

        move_id = self.create_journal_entry(credit_account, debit_account, journal, float(del_price), False, partner,
                                            False, 0, order_id)

        tax_id = self.env['account.tax'].search([('amount', '=', 16), ('type_tax_use', '=', 'sale')], limit=1).id
        product_id_DEL = self.env['product.template'].search([('default_code', '=', 'DEL')], limit=1)

        move = self.env['account.move'].create({
            'type': 'out_invoice',
            'partner_id': partner.id,
            'invoice_line_ids': [(0, 0, {
                'quantity': 1,
                'price_unit': del_price / (1 + 0.16),
                'product_id': product_id_DEL,
                'tax_ids': [(4, tax_id)],
                'ref': order_id if order_id else '',
            })]
        })

        move.action_post()
        move.post()
        move_id = move

        if move_id:
            return 1
        else:
            return -1
        
    def create_in_invoice(self, vendor_id, order_id, foreign_order_barcode, amount):
        del_price = float(amount)
        vendor_id = int(vendor_id)
        order_id = int(order_id)
        foreign_order_barcode = str(foreign_order_barcode)

        # return str(del_price) + " - " + str(vendor_id) + " - " + str(order_id)
        
        partner = self.env['res.partner'].search([('togo_partner_id', '=', vendor_id)], limit=1)

        # return partner.id

        tax_id = self.env['account.tax'].search([('amount', '=', 16), ('type_tax_use', '=', 'sale')], limit=1).id
        product_id = self.env['product.template'].search([('default_code', '=', 'DS')], limit=1)
        move = self.env['account.move'].create({
            'type': 'in_invoice',
            'partner_id': partner.id,
            'invoice_line_ids': [(0, 0, {
                'quantity': 1,
                'price_unit': del_price / (1.16),
                'product_id': product_id,
                'tax_ids': [(4, tax_id)],
                'ref': order_id if order_id else '',
                'name': foreign_order_barcode,
                #'foreign_ref': foreign_order_id,
            })],
        })
        
        try:
            move.action_post()
            move.post()
            return True  # Invoice creation was successful
        except Exception as e:
            # Handle any exceptions that occurred during the invoice creation process
            return False

class AccountMoveLine(models.Model):
    _inherit = 'account.move.line'

    @api.model
    def compute_amount_fields(self, amount, src_currency, company_currency, invoice_currency=False):
        """ Helper function to compute value for fields debit/credit/amount_currency based on an amount and the currencies given in parameter"""
        amount_currency = False
        currency_id = False
        if src_currency and src_currency != company_currency:
            amount_currency = amount
            amount = src_currency.with_context(self._context).compute(amount, company_currency)
            currency_id = src_currency.id
        debit = amount > 0 and amount or 0.0
        credit = amount < 0 and -amount or 0.0
        if invoice_currency and invoice_currency != company_currency and not amount_currency:
            amount_currency = src_currency.with_context(self._context).compute(amount, invoice_currency)
            currency_id = invoice_currency.id
        return debit, credit, amount_currency, currency_id
