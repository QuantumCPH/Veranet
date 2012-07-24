<?php

/**
 * ChangeNumberDetail form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseChangeNumberDetailForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'customer_id' => new sfWidgetFormInput(),
      'old_number'  => new sfWidgetFormInput(),
      'new_number'  => new sfWidgetFormInput(),
      'status'      => new sfWidgetFormInputCheckbox(),
      'created_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'ChangeNumberDetail', 'column' => 'id', 'required' => false)),
      'customer_id' => new sfValidatorInteger(),
      'old_number'  => new sfValidatorString(array('max_length' => 255)),
      'new_number'  => new sfValidatorString(array('max_length' => 255)),
      'status'      => new sfValidatorBoolean(),
      'created_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('change_number_detail[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ChangeNumberDetail';
  }


}
