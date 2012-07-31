<?php

/**
 * EmployeeCustomerCallhistory form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseEmployeeCustomerCallhistoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'parent_table'         => new sfWidgetFormInput(),
      'parent_id'            => new sfWidgetFormInput(),
      'company_id'           => new sfWidgetFormInput(),
      'i_customer'           => new sfWidgetFormInput(),
      'i_xdr'                => new sfWidgetFormInput(),
      'account_id'           => new sfWidgetFormInput(),
      'account_type'         => new sfWidgetFormInput(),
      'cli'                  => new sfWidgetFormInput(),
      'phone_number'         => new sfWidgetFormInput(),
      'country_id'           => new sfWidgetFormPropelChoice(array('model' => 'Country', 'add_empty' => true)),
      'charged_quantity'     => new sfWidgetFormInput(),
      'description'          => new sfWidgetFormInput(),
      'charged_amount'       => new sfWidgetFormInput(),
      'vat_included_amount'  => new sfWidgetFormInput(),
      'charged_vat_value'    => new sfWidgetFormInput(),
      'subdivision'          => new sfWidgetFormInput(),
      'disconnect_cause'     => new sfWidgetFormInput(),
      'bill_status'          => new sfWidgetFormInput(),
      'connect_time'         => new sfWidgetFormDateTime(),
      'unix_connect_time'    => new sfWidgetFormDateTime(),
      'disconnect_time'      => new sfWidgetFormDateTime(),
      'unix_disconnect_time' => new sfWidgetFormDateTime(),
      'bill_time'            => new sfWidgetFormDateTime(),
      'status'               => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorPropelChoice(array('model' => 'EmployeeCustomerCallhistory', 'column' => 'id', 'required' => false)),
      'parent_table'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'parent_id'            => new sfValidatorInteger(array('required' => false)),
      'company_id'           => new sfValidatorInteger(array('required' => false)),
      'i_customer'           => new sfValidatorInteger(array('required' => false)),
      'i_xdr'                => new sfValidatorInteger(array('required' => false)),
      'account_id'           => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'account_type'         => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'cli'                  => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'phone_number'         => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'country_id'           => new sfValidatorPropelChoice(array('model' => 'Country', 'column' => 'id', 'required' => false)),
      'charged_quantity'     => new sfValidatorNumber(array('required' => false)),
      'description'          => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'charged_amount'       => new sfValidatorNumber(array('required' => false)),
      'vat_included_amount'  => new sfValidatorNumber(array('required' => false)),
      'charged_vat_value'    => new sfValidatorNumber(array('required' => false)),
      'subdivision'          => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'disconnect_cause'     => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'bill_status'          => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'connect_time'         => new sfValidatorDateTime(array('required' => false)),
      'unix_connect_time'    => new sfValidatorDateTime(array('required' => false)),
      'disconnect_time'      => new sfValidatorDateTime(array('required' => false)),
      'unix_disconnect_time' => new sfValidatorDateTime(array('required' => false)),
      'bill_time'            => new sfValidatorDateTime(array('required' => false)),
      'status'               => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('employee_customer_callhistory[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'EmployeeCustomerCallhistory';
  }


}
