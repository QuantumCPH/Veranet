<?php

/**
 * CallhistoryCallsLog form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseCallhistoryCallsLogForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'        => new sfWidgetFormInputHidden(),
      'parent'    => new sfWidgetFormInput(),
      'parent_id' => new sfWidgetFormInput(),
      'todate'    => new sfWidgetFormDateTime(),
      'fromdate'  => new sfWidgetFormDateTime(),
      'status'    => new sfWidgetFormInput(),
      'i_service' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'        => new sfValidatorPropelChoice(array('model' => 'CallhistoryCallsLog', 'column' => 'id', 'required' => false)),
      'parent'    => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'parent_id' => new sfValidatorInteger(array('required' => false)),
      'todate'    => new sfValidatorDateTime(array('required' => false)),
      'fromdate'  => new sfValidatorDateTime(array('required' => false)),
      'status'    => new sfValidatorInteger(array('required' => false)),
      'i_service' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('callhistory_calls_log[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CallhistoryCallsLog';
  }


}
