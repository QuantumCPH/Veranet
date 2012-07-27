<?php

/**
 * RegistrationSubscription form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseRegistrationSubscriptionForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'i_xdr'               => new sfWidgetFormInput(),
      'parent_table'        => new sfWidgetFormInput(),
      'parent_id'           => new sfWidgetFormInput(),
      'account_id'          => new sfWidgetFormInput(),
      'cli'                 => new sfWidgetFormInput(),
      'bill_start'          => new sfWidgetFormDateTime(),
      'bill_end'            => new sfWidgetFormDateTime(),
      'sub_fee'             => new sfWidgetFormInput(),
      'reg_fee'             => new sfWidgetFormInput(),
      'product_id'          => new sfWidgetFormInput(),
      'product_name'        => new sfWidgetFormInput(),
      'reg_exempted'        => new sfWidgetFormInputCheckbox(),
      'employee_created_at' => new sfWidgetFormDateTime(),
      'company_id'          => new sfWidgetFormInput(),
      'connect_time'        => new sfWidgetFormDateTime(),
      'disconnect_time'     => new sfWidgetFormDateTime(),
      'bill_time'           => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorPropelChoice(array('model' => 'RegistrationSubscription', 'column' => 'id', 'required' => false)),
      'i_xdr'               => new sfValidatorInteger(array('required' => false)),
      'parent_table'        => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'parent_id'           => new sfValidatorInteger(array('required' => false)),
      'account_id'          => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'cli'                 => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'bill_start'          => new sfValidatorDateTime(array('required' => false)),
      'bill_end'            => new sfValidatorDateTime(array('required' => false)),
      'sub_fee'             => new sfValidatorNumber(array('required' => false)),
      'reg_fee'             => new sfValidatorNumber(array('required' => false)),
      'product_id'          => new sfValidatorInteger(array('required' => false)),
      'product_name'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'reg_exempted'        => new sfValidatorBoolean(array('required' => false)),
      'employee_created_at' => new sfValidatorDateTime(array('required' => false)),
      'company_id'          => new sfValidatorInteger(array('required' => false)),
      'connect_time'        => new sfValidatorDateTime(array('required' => false)),
      'disconnect_time'     => new sfValidatorDateTime(array('required' => false)),
      'bill_time'           => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('registration_subscription[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'RegistrationSubscription';
  }


}
