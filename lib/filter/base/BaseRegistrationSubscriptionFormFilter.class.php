<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * RegistrationSubscription filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseRegistrationSubscriptionFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'i_xdr'               => new sfWidgetFormFilterInput(),
      'parent_table'        => new sfWidgetFormFilterInput(),
      'parent_id'           => new sfWidgetFormFilterInput(),
      'account_id'          => new sfWidgetFormFilterInput(),
      'cli'                 => new sfWidgetFormFilterInput(),
      'bill_start'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'bill_end'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'sub_fee'             => new sfWidgetFormFilterInput(),
      'reg_fee'             => new sfWidgetFormFilterInput(),
      'product_id'          => new sfWidgetFormFilterInput(),
      'product_name'        => new sfWidgetFormFilterInput(),
      'reg_exempted'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'employee_created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'company_id'          => new sfWidgetFormFilterInput(),
      'connect_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'disconnect_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'bill_time'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'i_xdr'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'parent_table'        => new sfValidatorPass(array('required' => false)),
      'parent_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'account_id'          => new sfValidatorPass(array('required' => false)),
      'cli'                 => new sfValidatorPass(array('required' => false)),
      'bill_start'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'bill_end'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'sub_fee'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'reg_fee'             => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'product_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'product_name'        => new sfValidatorPass(array('required' => false)),
      'reg_exempted'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'employee_created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'company_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'connect_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'disconnect_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'bill_time'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('registration_subscription_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'RegistrationSubscription';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'i_xdr'               => 'Number',
      'parent_table'        => 'Text',
      'parent_id'           => 'Number',
      'account_id'          => 'Text',
      'cli'                 => 'Text',
      'bill_start'          => 'Date',
      'bill_end'            => 'Date',
      'sub_fee'             => 'Number',
      'reg_fee'             => 'Number',
      'product_id'          => 'Number',
      'product_name'        => 'Text',
      'reg_exempted'        => 'Boolean',
      'employee_created_at' => 'Date',
      'company_id'          => 'Number',
      'connect_time'        => 'Date',
      'disconnect_time'     => 'Date',
      'bill_time'           => 'Date',
    );
  }
}
