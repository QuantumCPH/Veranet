<?php

/**
 * Handsets form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseHandsetsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'brand_name'  => new sfWidgetFormInput(),
      'model_name'  => new sfWidgetFormInput(),
      'auto_reboot' => new sfWidgetFormInput(),
      'dialer_mode' => new sfWidgetFormInput(),
      'tested_by'   => new sfWidgetFormInput(),
      'comments'    => new sfWidgetFormTextarea(),
      'supported'   => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'Handsets', 'column' => 'id', 'required' => false)),
      'brand_name'  => new sfValidatorString(array('max_length' => 150)),
      'model_name'  => new sfValidatorString(array('max_length' => 150)),
      'auto_reboot' => new sfValidatorString(array('max_length' => 150)),
      'dialer_mode' => new sfValidatorString(array('max_length' => 150)),
      'tested_by'   => new sfValidatorString(array('max_length' => 150)),
      'comments'    => new sfValidatorString(array('required' => false)),
      'supported'   => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('handsets[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Handsets';
  }


}
