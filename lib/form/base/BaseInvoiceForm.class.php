<?php

/**
 * Invoice form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseInvoiceForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'invoice_number'        => new sfWidgetFormInput(),
      'company_id'            => new sfWidgetFormPropelChoice(array('model' => 'Company', 'add_empty' => false)),
      'totalPayment'          => new sfWidgetFormInput(),
      'total_payable_balance' => new sfWidgetFormInput(),
      'current_bill'          => new sfWidgetFormInput(),
      'net_bill'              => new sfWidgetFormInput(),
      'due_date'              => new sfWidgetFormDateTime(),
      'created_at'            => new sfWidgetFormDateTime(),
      'billing_starting_date' => new sfWidgetFormDateTime(),
      'billing_ending_date'   => new sfWidgetFormDateTime(),
      'invoice_cost'          => new sfWidgetFormInput(),
      'subscription_fee'      => new sfWidgetFormInput(),
      'registration_fee'      => new sfWidgetFormInput(),
      'payment_history_total' => new sfWidgetFormInput(),
      'moms'                  => new sfWidgetFormInput(),
      'totalusage'            => new sfWidgetFormInput(),
      'invoice_status_id'     => new sfWidgetFormPropelChoice(array('model' => 'InvoiceStatus', 'add_empty' => false)),
      'invoice_html'          => new sfWidgetFormTextarea(),
      'registration_html'     => new sfWidgetFormTextarea(),
      'registration_file'     => new sfWidgetFormInput(),
      'html_file'             => new sfWidgetFormInput(),
      'pdf_file'              => new sfWidgetFormInput(),
      'paid_amount'           => new sfWidgetFormInput(),
      'net_payment'           => new sfWidgetFormInput(),
      'paid_datetime'         => new sfWidgetFormDateTime(),
      'start_time'            => new sfWidgetFormInput(),
      'end_time'              => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorPropelChoice(array('model' => 'Invoice', 'column' => 'id', 'required' => false)),
      'invoice_number'        => new sfValidatorNumber(array('required' => false)),
      'company_id'            => new sfValidatorPropelChoice(array('model' => 'Company', 'column' => 'id')),
      'totalPayment'          => new sfValidatorNumber(),
      'total_payable_balance' => new sfValidatorNumber(array('required' => false)),
      'current_bill'          => new sfValidatorNumber(array('required' => false)),
      'net_bill'              => new sfValidatorNumber(array('required' => false)),
      'due_date'              => new sfValidatorDateTime(),
      'created_at'            => new sfValidatorDateTime(),
      'billing_starting_date' => new sfValidatorDateTime(),
      'billing_ending_date'   => new sfValidatorDateTime(),
      'invoice_cost'          => new sfValidatorInteger(array('required' => false)),
      'subscription_fee'      => new sfValidatorNumber(array('required' => false)),
      'registration_fee'      => new sfValidatorNumber(array('required' => false)),
      'payment_history_total' => new sfValidatorNumber(array('required' => false)),
      'moms'                  => new sfValidatorNumber(array('required' => false)),
      'totalusage'            => new sfValidatorNumber(array('required' => false)),
      'invoice_status_id'     => new sfValidatorPropelChoice(array('model' => 'InvoiceStatus', 'column' => 'id')),
      'invoice_html'          => new sfValidatorString(),
      'registration_html'     => new sfValidatorString(array('required' => false)),
      'registration_file'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'html_file'             => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'pdf_file'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'paid_amount'           => new sfValidatorNumber(array('required' => false)),
      'net_payment'           => new sfValidatorNumber(array('required' => false)),
      'paid_datetime'         => new sfValidatorDateTime(array('required' => false)),
      'start_time'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'end_time'              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('invoice[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Invoice';
  }


}
