<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * ForumTelRequests filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseForumTelRequestsFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'requestid'    => new sfWidgetFormFilterInput(),
      'response'     => new sfWidgetFormFilterInput(),
      'request_type' => new sfWidgetFormFilterInput(),
      'iccid'        => new sfWidgetFormFilterInput(),
      'msisdn'       => new sfWidgetFormFilterInput(),
      'created_at'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'requestid'    => new sfValidatorPass(array('required' => false)),
      'response'     => new sfValidatorPass(array('required' => false)),
      'request_type' => new sfValidatorPass(array('required' => false)),
      'iccid'        => new sfValidatorPass(array('required' => false)),
      'msisdn'       => new sfValidatorPass(array('required' => false)),
      'created_at'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('forum_tel_requests_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ForumTelRequests';
  }

  public function getFields()
  {
    return array(
      'id'           => 'Number',
      'requestid'    => 'Text',
      'response'     => 'Text',
      'request_type' => 'Text',
      'iccid'        => 'Text',
      'msisdn'       => 'Text',
      'created_at'   => 'Date',
    );
  }
}
