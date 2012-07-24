<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * EmployeeCallhistory filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseEmployeeCallhistoryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'employee_id'          => new sfWidgetFormPropelChoice(array('model' => 'Employee', 'add_empty' => true)),
      'company_id'           => new sfWidgetFormPropelChoice(array('model' => 'Company', 'add_empty' => true)),
      'i_customer'           => new sfWidgetFormFilterInput(),
      'i_xdr'                => new sfWidgetFormFilterInput(),
      'account_id'           => new sfWidgetFormFilterInput(),
      'cli'                  => new sfWidgetFormFilterInput(),
      'phone_number'         => new sfWidgetFormFilterInput(),
      'country_id'           => new sfWidgetFormPropelChoice(array('model' => 'Country', 'add_empty' => true)),
      'charged_quantity'     => new sfWidgetFormFilterInput(),
      'duration'             => new sfWidgetFormFilterInput(),
      'duration_minutes'     => new sfWidgetFormFilterInput(),
      'description'          => new sfWidgetFormFilterInput(),
      'charged_amount'       => new sfWidgetFormFilterInput(),
      'subdivision'          => new sfWidgetFormFilterInput(),
      'disconnect_cause'     => new sfWidgetFormFilterInput(),
      'bill_status'          => new sfWidgetFormFilterInput(),
      'connect_time'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'unix_connect_time'    => new sfWidgetFormFilterInput(),
      'disconnect_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'unix_disconnect_time' => new sfWidgetFormFilterInput(),
      'bill_time'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'status_id'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'employee_id'          => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Employee', 'column' => 'id')),
      'company_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Company', 'column' => 'id')),
      'i_customer'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'i_xdr'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'account_id'           => new sfValidatorPass(array('required' => false)),
      'cli'                  => new sfValidatorPass(array('required' => false)),
      'phone_number'         => new sfValidatorPass(array('required' => false)),
      'country_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Country', 'column' => 'id')),
      'charged_quantity'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'duration'             => new sfValidatorPass(array('required' => false)),
      'duration_minutes'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'description'          => new sfValidatorPass(array('required' => false)),
      'charged_amount'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'subdivision'          => new sfValidatorPass(array('required' => false)),
      'disconnect_cause'     => new sfValidatorPass(array('required' => false)),
      'bill_status'          => new sfValidatorPass(array('required' => false)),
      'connect_time'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'unix_connect_time'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'disconnect_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'unix_disconnect_time' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'bill_time'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'status_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('employee_callhistory_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'EmployeeCallhistory';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'employee_id'          => 'ForeignKey',
      'company_id'           => 'ForeignKey',
      'i_customer'           => 'Number',
      'i_xdr'                => 'Number',
      'account_id'           => 'Text',
      'cli'                  => 'Text',
      'phone_number'         => 'Text',
      'country_id'           => 'ForeignKey',
      'charged_quantity'     => 'Number',
      'duration'             => 'Text',
      'duration_minutes'     => 'Number',
      'description'          => 'Text',
      'charged_amount'       => 'Number',
      'subdivision'          => 'Text',
      'disconnect_cause'     => 'Text',
      'bill_status'          => 'Text',
      'connect_time'         => 'Date',
      'unix_connect_time'    => 'Number',
      'disconnect_time'      => 'Date',
      'unix_disconnect_time' => 'Number',
      'bill_time'            => 'Date',
      'status_id'            => 'Number',
    );
  }
}
