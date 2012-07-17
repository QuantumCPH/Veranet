<?php

/**
 * CardNumbers form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseCardNumbersForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'card_number' => new sfWidgetFormInput(),
      'card_serial' => new sfWidgetFormInput(),
      'card_price'  => new sfWidgetFormInput(),
      'status'      => new sfWidgetFormInput(),
      'created_at'  => new sfWidgetFormDateTime(),
      'used_at'     => new sfWidgetFormDateTime(),
      'customer_id' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'CardNumbers', 'column' => 'id', 'required' => false)),
      'card_number' => new sfValidatorString(array('max_length' => 255)),
      'card_serial' => new sfValidatorString(array('max_length' => 255)),
      'card_price'  => new sfValidatorInteger(),
      'status'      => new sfValidatorInteger(),
      'created_at'  => new sfValidatorDateTime(),
      'used_at'     => new sfValidatorDateTime(),
      'customer_id' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('card_numbers[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CardNumbers';
  }


}
