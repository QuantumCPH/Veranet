<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Handsets filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseHandsetsFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'brand_name'  => new sfWidgetFormFilterInput(),
      'model_name'  => new sfWidgetFormFilterInput(),
      'auto_reboot' => new sfWidgetFormFilterInput(),
      'dialer_mode' => new sfWidgetFormFilterInput(),
      'tested_by'   => new sfWidgetFormFilterInput(),
      'comments'    => new sfWidgetFormFilterInput(),
      'supported'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'brand_name'  => new sfValidatorPass(array('required' => false)),
      'model_name'  => new sfValidatorPass(array('required' => false)),
      'auto_reboot' => new sfValidatorPass(array('required' => false)),
      'dialer_mode' => new sfValidatorPass(array('required' => false)),
      'tested_by'   => new sfValidatorPass(array('required' => false)),
      'comments'    => new sfValidatorPass(array('required' => false)),
      'supported'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('handsets_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Handsets';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'brand_name'  => 'Text',
      'model_name'  => 'Text',
      'auto_reboot' => 'Text',
      'dialer_mode' => 'Text',
      'tested_by'   => 'Text',
      'comments'    => 'Text',
      'supported'   => 'Boolean',
    );
  }
}
