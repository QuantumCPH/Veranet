<?php

/**
 * Odrs form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseOdrsForm extends BaseFormPropel
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
      'description'         => new sfWidgetFormInput(),
      'bill_start'          => new sfWidgetFormDateTime(),
      'bill_end'            => new sfWidgetFormDateTime(),
      'charged_amount'      => new sfWidgetFormInput(),
      'company_id'          => new sfWidgetFormInput(),
      'connect_time'        => new sfWidgetFormDateTime(),
      'disconnect_time'     => new sfWidgetFormDateTime(),
      'bill_time'           => new sfWidgetFormDateTime(),
      'i_service'           => new sfWidgetFormInput(),
      'vat_included_amount' => new sfWidgetFormInput(),
      'charged_vat_value'   => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorPropelChoice(array('model' => 'Odrs', 'column' => 'id', 'required' => false)),
      'i_xdr'               => new sfValidatorInteger(array('required' => false)),
      'parent_table'        => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'parent_id'           => new sfValidatorInteger(array('required' => false)),
      'account_id'          => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'cli'                 => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'description'         => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'bill_start'          => new sfValidatorDateTime(array('required' => false)),
      'bill_end'            => new sfValidatorDateTime(array('required' => false)),
      'charged_amount'      => new sfValidatorNumber(array('required' => false)),
      'company_id'          => new sfValidatorInteger(array('required' => false)),
      'connect_time'        => new sfValidatorDateTime(array('required' => false)),
      'disconnect_time'     => new sfValidatorDateTime(array('required' => false)),
      'bill_time'           => new sfValidatorDateTime(array('required' => false)),
      'i_service'           => new sfValidatorInteger(array('required' => false)),
      'vat_included_amount' => new sfValidatorNumber(array('required' => false)),
      'charged_vat_value'   => new sfValidatorNumber(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('odrs[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Odrs';
  }


}
