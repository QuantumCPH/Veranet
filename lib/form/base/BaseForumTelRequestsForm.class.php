<?php

/**
 * ForumTelRequests form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseForumTelRequestsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'requestid'    => new sfWidgetFormInput(),
      'response'     => new sfWidgetFormTextarea(),
      'request_type' => new sfWidgetFormInput(),
      'iccid'        => new sfWidgetFormInput(),
      'msisdn'       => new sfWidgetFormInput(),
      'created_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorPropelChoice(array('model' => 'ForumTelRequests', 'column' => 'id', 'required' => false)),
      'requestid'    => new sfValidatorString(array('max_length' => 255)),
      'response'     => new sfValidatorString(),
      'request_type' => new sfValidatorString(array('max_length' => 255)),
      'iccid'        => new sfValidatorString(array('max_length' => 255)),
      'msisdn'       => new sfValidatorString(array('max_length' => 255)),
      'created_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('forum_tel_requests[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ForumTelRequests';
  }


}
