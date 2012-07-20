<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * PromotionRates filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BasePromotionRatesFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'agent_id'     => new sfWidgetFormPropelChoice(array('model' => 'Company', 'add_empty' => true)),
      'network_name' => new sfWidgetFormFilterInput(),
      'network_rate' => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'agent_id'     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Company', 'column' => 'id')),
      'network_name' => new sfValidatorPass(array('required' => false)),
      'network_rate' => new sfValidatorPass(array('required' => false)),
      'created_at'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('promotion_rates_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'PromotionRates';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'agent_id'     => 'ForeignKey',
      'network_name' => 'Text',
      'network_rate' => 'Text',
      'created_at'   => 'Boolean',
    );
  }
}
