<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * UsNumber filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseUsNumberFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_id'      => new sfWidgetFormFilterInput(),
      'iccid'            => new sfWidgetFormFilterInput(),
      'msisdn'           => new sfWidgetFormFilterInput(),
      'us_mobile_number' => new sfWidgetFormFilterInput(),
      'active_status'    => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'customer_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'iccid'            => new sfValidatorPass(array('required' => false)),
      'msisdn'           => new sfValidatorPass(array('required' => false)),
      'us_mobile_number' => new sfValidatorPass(array('required' => false)),
      'active_status'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('us_number_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsNumber';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'customer_id'      => 'Number',
      'iccid'            => 'Text',
      'msisdn'           => 'Text',
      'us_mobile_number' => 'Text',
      'active_status'    => 'Number',
      'created_at'       => 'Boolean',
    );
  }
}
