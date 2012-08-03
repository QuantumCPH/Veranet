<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Invoice filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseInvoiceFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'invoice_number'        => new sfWidgetFormFilterInput(),
      'company_id'            => new sfWidgetFormPropelChoice(array('model' => 'Company', 'add_empty' => true)),
      'totalPayment'          => new sfWidgetFormFilterInput(),
      'total_payable_balance' => new sfWidgetFormFilterInput(),
      'current_bill'          => new sfWidgetFormFilterInput(),
      'net_bill'              => new sfWidgetFormFilterInput(),
      'due_date'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'billing_starting_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'billing_ending_date'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'invoice_cost'          => new sfWidgetFormFilterInput(),
      'subscription_fee'      => new sfWidgetFormFilterInput(),
      'registration_fee'      => new sfWidgetFormFilterInput(),
      'payment_history_total' => new sfWidgetFormFilterInput(),
      'moms'                  => new sfWidgetFormFilterInput(),
      'totalusage'            => new sfWidgetFormFilterInput(),
      'invoice_status_id'     => new sfWidgetFormPropelChoice(array('model' => 'InvoiceStatus', 'add_empty' => true)),
      'invoice_html'          => new sfWidgetFormFilterInput(),
      'registration_html'     => new sfWidgetFormFilterInput(),
      'registration_file'     => new sfWidgetFormFilterInput(),
      'html_file'             => new sfWidgetFormFilterInput(),
      'pdf_file'              => new sfWidgetFormFilterInput(),
      'paid_amount'           => new sfWidgetFormFilterInput(),
      'net_payment'           => new sfWidgetFormFilterInput(),
      'paid_datetime'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'start_time'            => new sfWidgetFormFilterInput(),
      'end_time'              => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'invoice_number'        => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'company_id'            => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Company', 'column' => 'id')),
      'totalPayment'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'total_payable_balance' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'current_bill'          => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'net_bill'              => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'due_date'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'billing_starting_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'billing_ending_date'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'invoice_cost'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'subscription_fee'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'registration_fee'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'payment_history_total' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'moms'                  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'totalusage'            => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'invoice_status_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'InvoiceStatus', 'column' => 'id')),
      'invoice_html'          => new sfValidatorPass(array('required' => false)),
      'registration_html'     => new sfValidatorPass(array('required' => false)),
      'registration_file'     => new sfValidatorPass(array('required' => false)),
      'html_file'             => new sfValidatorPass(array('required' => false)),
      'pdf_file'              => new sfValidatorPass(array('required' => false)),
      'paid_amount'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'net_payment'           => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'paid_datetime'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'start_time'            => new sfValidatorPass(array('required' => false)),
      'end_time'              => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('invoice_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Invoice';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'invoice_number'        => 'Number',
      'company_id'            => 'ForeignKey',
      'totalPayment'          => 'Number',
      'total_payable_balance' => 'Number',
      'current_bill'          => 'Number',
      'net_bill'              => 'Number',
      'due_date'              => 'Date',
      'created_at'            => 'Date',
      'billing_starting_date' => 'Date',
      'billing_ending_date'   => 'Date',
      'invoice_cost'          => 'Number',
      'subscription_fee'      => 'Number',
      'registration_fee'      => 'Number',
      'payment_history_total' => 'Number',
      'moms'                  => 'Number',
      'totalusage'            => 'Number',
      'invoice_status_id'     => 'ForeignKey',
      'invoice_html'          => 'Text',
      'registration_html'     => 'Text',
      'registration_file'     => 'Text',
      'html_file'             => 'Text',
      'pdf_file'              => 'Text',
      'paid_amount'           => 'Number',
      'net_payment'           => 'Number',
      'paid_datetime'         => 'Date',
      'start_time'            => 'Text',
      'end_time'              => 'Text',
    );
  }
}
