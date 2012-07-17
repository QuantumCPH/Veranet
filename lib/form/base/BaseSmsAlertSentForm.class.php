<?php

/**
 * SmsAlertSent form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseSmsAlertSentForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'customer_id'           => new sfWidgetFormInput(),
      'customer_name'         => new sfWidgetFormInput(),
      'mobile_number'         => new sfWidgetFormInput(),
      'customer_email'        => new sfWidgetFormInput(),
      'customer_product'      => new sfWidgetFormInput(),
      'agent_name'            => new sfWidgetFormInput(),
      'registration_type'     => new sfWidgetFormInput(),
      'fonet_customer_id'     => new sfWidgetFormInput(),
      'message_descerption'   => new sfWidgetFormInput(),
      'status'                => new sfWidgetFormInputCheckbox(),
      'created_at'            => new sfWidgetFormDateTime(),
      'alert_sent'            => new sfWidgetFormInputCheckbox(),
      'usage_alert_status_id' => new sfWidgetFormInput(),
      'alert_activated'       => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorPropelChoice(array('model' => 'SmsAlertSent', 'column' => 'id', 'required' => false)),
      'customer_id'           => new sfValidatorInteger(array('required' => false)),
      'customer_name'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'mobile_number'         => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'customer_email'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'customer_product'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'agent_name'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'registration_type'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'fonet_customer_id'     => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'message_descerption'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'status'                => new sfValidatorBoolean(),
      'created_at'            => new sfValidatorDateTime(),
      'alert_sent'            => new sfValidatorBoolean(array('required' => false)),
      'usage_alert_status_id' => new sfValidatorInteger(array('required' => false)),
      'alert_activated'       => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('sms_alert_sent[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'SmsAlertSent';
  }


}
