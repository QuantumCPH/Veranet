<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * CallLog filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseCallLogFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'imsi'          => new sfWidgetFormFilterInput(),
      'dest'          => new sfWidgetFormFilterInput(),
      'mac'           => new sfWidgetFormFilterInput(),
      'mobile_number' => new sfWidgetFormFilterInput(),
      'created'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'check_status'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'imsi'          => new sfValidatorPass(array('required' => false)),
      'dest'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mac'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'mobile_number' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'check_status'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('call_log_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CallLog';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'imsi'          => 'Text',
      'dest'          => 'Number',
      'mac'           => 'Number',
      'mobile_number' => 'Number',
      'created'       => 'Boolean',
      'check_status'  => 'Boolean',
    );
  }
}
