<?php

/**
 * PreferredLanguages form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BasePreferredLanguagesForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'            => new sfWidgetFormInputHidden(),
      'language'      => new sfWidgetFormInput(),
      'language_code' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'            => new sfValidatorPropelChoice(array('model' => 'PreferredLanguages', 'column' => 'id', 'required' => false)),
      'language'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'language_code' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('preferred_languages[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PreferredLanguages';
  }


}
