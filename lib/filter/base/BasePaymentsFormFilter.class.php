<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Payments filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BasePaymentsFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'txnid'          => new sfWidgetFormFilterInput(),
      'payment_amount' => new sfWidgetFormFilterInput(),
      'payment_status' => new sfWidgetFormFilterInput(),
      'itemid'         => new sfWidgetFormFilterInput(),
      'createdtime'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'txnid'          => new sfValidatorPass(array('required' => false)),
      'payment_amount' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'payment_status' => new sfValidatorPass(array('required' => false)),
      'itemid'         => new sfValidatorPass(array('required' => false)),
      'createdtime'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('payments_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Payments';
  }

  public function getFields()
  {
    return array(
      'id'             => 'Number',
      'txnid'          => 'Text',
      'payment_amount' => 'Number',
      'payment_status' => 'Text',
      'itemid'         => 'Text',
      'createdtime'    => 'Boolean',
    );
  }
}
