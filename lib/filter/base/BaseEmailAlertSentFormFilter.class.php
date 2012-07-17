<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * EmailAlertSent filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseEmailAlertSentFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_id'           => new sfWidgetFormFilterInput(),
      'customer_name'         => new sfWidgetFormFilterInput(),
      'mobile_number'         => new sfWidgetFormFilterInput(),
      'customer_email'        => new sfWidgetFormFilterInput(),
      'customer_product'      => new sfWidgetFormFilterInput(),
      'agent_name'            => new sfWidgetFormFilterInput(),
      'registration_type'     => new sfWidgetFormFilterInput(),
      'fonet_customer_id'     => new sfWidgetFormFilterInput(),
      'message_descerption'   => new sfWidgetFormFilterInput(),
      'status'                => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'alert_sent'            => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'usage_alert_status_id' => new sfWidgetFormFilterInput(),
      'alert_activated'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'customer_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'customer_name'         => new sfValidatorPass(array('required' => false)),
      'mobile_number'         => new sfValidatorPass(array('required' => false)),
      'customer_email'        => new sfValidatorPass(array('required' => false)),
      'customer_product'      => new sfValidatorPass(array('required' => false)),
      'agent_name'            => new sfValidatorPass(array('required' => false)),
      'registration_type'     => new sfValidatorPass(array('required' => false)),
      'fonet_customer_id'     => new sfValidatorPass(array('required' => false)),
      'message_descerption'   => new sfValidatorPass(array('required' => false)),
      'status'                => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'alert_sent'            => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'usage_alert_status_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'alert_activated'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('email_alert_sent_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'EmailAlertSent';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'customer_id'           => 'Number',
      'customer_name'         => 'Text',
      'mobile_number'         => 'Text',
      'customer_email'        => 'Text',
      'customer_product'      => 'Text',
      'agent_name'            => 'Text',
      'registration_type'     => 'Text',
      'fonet_customer_id'     => 'Text',
      'message_descerption'   => 'Text',
      'status'                => 'Boolean',
      'created_at'            => 'Date',
      'alert_sent'            => 'Boolean',
      'usage_alert_status_id' => 'Number',
      'alert_activated'       => 'Boolean',
    );
  }
}
