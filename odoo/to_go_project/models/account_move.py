# -*- coding: utf-8 -*-
import base64
import datetime

from odoo import models, fields, api, _


class AccountMove(models.Model):
    _inherit = 'account.move'

    def get_debit_credit_customer_related_aml_items(self):
        for rec in self:
            debit = 0.0
            credit = 0.0
            if rec.partner_id:
                if rec.ref:
                    entry_ams = self.search([
                        ('type', '=', 'entry'),
                        ('ref', '=', rec.ref),
                    ])
                    if entry_ams:
                        for entry_am in entry_ams:
                            aml_objs = self.env['account.move.line'].search([
                                ('move_id', '=', entry_am.id),
                                ('partner_id', '=', rec.partner_id.id),
                            ])
                            debit += sum([item.debit for item in aml_objs])
                            credit += sum([item.credit for item in aml_objs])
            return [credit, debit]

    def send_mail_with_invoice_to_customer(self):
        invoice_report_id = self.env.ref('account.account_invoices')
        generated_report = invoice_report_id.render_qweb_pdf(self.id)
        data_record = base64.b64encode(generated_report[0])
        ir_values = {
            'name': 'ToGo Palestine Invoice Report',
            'type': 'binary',
            'datas': data_record,
            'store_fname': data_record,
            'mimetype': 'application/pdf',
            'res_model': 'account.move',
        }
        report_attachment = self.env['ir.attachment'].sudo().create(ir_values)
        email_template = self.env['mail.mail'].with_context().create({
            'subject': 'ToGo Palestine Invoice (Ref {})'.format(self.name),
            'body_html': '''<p style="margin: 0px; padding: 0px; font-size: 13px;">
        Dear {}'''.format(self.partner_id.name) + '''

        <br /><br />
        Here is your invoice "({})"'''.format(self.name) + '''
            (with reference: {})'''.format(self.ref if self.ref else None) + '''
        amounting in <strong>{}{}</strong> '''.format(self.amount_total, self.currency_id.name) + '''
        from {}.'''.format(self.company_id.name) + '''

            Please remit payment at your earliest convenience.

        Do not hesitate to contact us if you have any questions.
    </p>''',
            'email_to': self.partner_id.email,
            'email_from': self.env.user.email,
        })

        email_template.attachment_ids = [(4, report_attachment.id)]
        email_template.send()
        email_template.attachment_ids = [(3, report_attachment.id)]

        self.message_post(
            body=(_("Mail sent on %s by %s") % (fields.Datetime.now(), self.env.user.display_name))
        )
    def send_success_mail(self):
        # imd_res = self.env['ir.model.data']
        # template_res = self.env['mail.template']
        # current_date = datetime.date.today()
        # _, template_id = imd_res.get_object_reference('account', 'email_template_edi_invoice')
        # email_context = self.env.context.copy()
        # template = template_res.browse(template_id)
        # return template.with_context(email_context).send_mail(self.id)
        template = self.env.ref('account.email_template_edi_invoice', raise_if_not_found=False)
        return template.send_mail(self.id)

    def action_post(self):
        res = super(AccountMove, self).action_post()
        if self.type == 'out_invoice':
            # self.send_mail_with_invoice_to_customer()
            self.send_success_mail()
        return res

    # @api.model
    # def create(self, vals_list):
    #     res = super(AccountMove, self).create(vals_list)
    #     res.send_mail_with_invoice_to_customer()
    #     return res
