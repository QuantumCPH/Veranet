<?php

/**
 * EmployeeCallhistory form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseEmployeeCallhistoryForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'company_id'           => new sfWidgetFormInput(),
      'i_xdr'                => new sfWidgetFormInput(),
      'account_id'           => new sfWidgetFormInput(),
      'cli'                  => new sfWidgetFormInput(),
      'phone_number'         => new sfWidgetFormInput(),
      'country_id'           => new sfWidgetFormInput(),
      'charged_quantity'     => new sfWidgetFormInput(),
      'duration'             => new sfWidgetFormInput(),
      'description'          => new sfWidgetFormInput(),
      'charged_amount'       => new sfWidgetFormInput(),
      'subdivision'          => new sfWidgetFormInput(),
      'disconnect_cause'     => new sfWidgetFormInput(),
      'bill_status'          => new sfWidgetFormInput(),
      'connect_time'         => new sfWidgetFormDateTime(),
      'unix_connect_time'    => new sfWidgetFormInput(),
      'disconnect_time'      => new sfWidgetFormDateTime(),
      'unix_disconnect_time' => new sfWidgetFormInput(),
      'bill_time'            => new sfWidgetFormDateTime(),
      'status_id'            => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorPropelChoice(array('model' => 'EmployeeCallhistory', 'column' => 'id', 'required' => false)),
      'company_id'           => new sfValidatorInteger(array('required' => false)),
      'i_xdr'                => new sfValidatorInteger(array('required' => false)),
      'account_id'           => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'cli'                  => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'phone_number'         => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'country_id'           => new sfValidatorInteger(array('required' => false)),
      'charged_quantity'     => new sfValidatorNumber(array('required' => false)),
      'duration'             => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'description'          => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'charged_amount'       => new sfValidatorNumber(array('required' => false)),
      'subdivision'          => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'disconnect_cause'     => new sfValidatorString(array('max_length' => 250, 'required' => false)),
      'bill_status'          => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'connect_time'         => new sfValidatorDateTime(array('required' => false)),
      'unix_connect_time'    => new sfValidatorInteger(array('required' => false)),
      'disconnect_time'      => new sfValidatorDateTime(array('required' => false)),
      'unix_disconnect_time' => new sfValidatorInteger(array('required' => false)),
      'bill_time'            => new sfValidatorDateTime(array('required' => false)),
      'status_id'            => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('employee_callhistory[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'EmployeeCallhistory';
  }


}
