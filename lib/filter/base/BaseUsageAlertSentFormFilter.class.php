<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * UsageAlertSent filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseUsageAlertSentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'usage_alert_id' => new sfWidgetFormFilterInput(),
      'customerid'     => new sfWidgetFormFilterInput(),
      'messagetype'    => new sfWidgetFormFilterInput(),
      'alert_amount'   => new sfWidgetFormFilterInput(),
      'senttime'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'usage_alert_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'customerid'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'messagetype'    => new sfValidatorPass(array('required' => false)),
      'alert_amount'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'senttime'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('usage_alert_sent_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UsageAlertSent';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'usage_alert_id' => 'Number',
      'customerid'     => 'Number',
      'messagetype'    => 'Text',
      'alert_amount'   => 'Number',
      'senttime'       => 'Boolean',
    );
  }
}
