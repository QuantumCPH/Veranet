<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * AgentPaymentHistory filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseAgentPaymentHistoryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'agent_id'          => new sfWidgetFormFilterInput(),
      'customer_id'       => new sfWidgetFormFilterInput(),
      'expenese_type'     => new sfWidgetFormFilterInput(),
      'order_description' => new sfWidgetFormFilterInput(),
      'amount'            => new sfWidgetFormFilterInput(),
      'remaining_balance' => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'agent_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'customer_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'expenese_type'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'order_description' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'amount'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'remaining_balance' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('agent_payment_history_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AgentPaymentHistory';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'agent_id'          => 'Number',
      'customer_id'       => 'Number',
      'expenese_type'     => 'Number',
      'order_description' => 'Number',
      'amount'            => 'Number',
      'remaining_balance' => 'Number',
      'created_at'        => 'Boolean',
    );
  }
}
