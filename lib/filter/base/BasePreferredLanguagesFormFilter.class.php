<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PreferredLanguages filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BasePreferredLanguagesFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'language'      => new sfWidgetFormFilterInput(),
      'language_code' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'language'      => new sfValidatorPass(array('required' => false)),
      'language_code' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('preferred_languages_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PreferredLanguages';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'language'      => 'Text',
      'language_code' => 'Text',
    );
  }
}
