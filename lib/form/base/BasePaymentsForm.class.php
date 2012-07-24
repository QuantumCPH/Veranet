<?php

/**
 * Payments form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BasePaymentsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'             => new sfWidgetFormInputHidden(),
      'txnid'          => new sfWidgetFormInput(),
      'payment_amount' => new sfWidgetFormInput(),
      'payment_status' => new sfWidgetFormInput(),
      'itemid'         => new sfWidgetFormInput(),
      'createdtime'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'             => new sfValidatorPropelChoice(array('model' => 'Payments', 'column' => 'id', 'required' => false)),
      'txnid'          => new sfValidatorString(array('max_length' => 20)),
      'payment_amount' => new sfValidatorNumber(),
      'payment_status' => new sfValidatorString(array('max_length' => 25)),
      'itemid'         => new sfValidatorString(array('max_length' => 25)),
      'createdtime'    => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('payments[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Payments';
  }


}
