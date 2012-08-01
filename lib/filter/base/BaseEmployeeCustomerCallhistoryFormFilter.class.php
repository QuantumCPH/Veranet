<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * EmployeeCustomerCallhistory filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseEmployeeCustomerCallhistoryFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'parent_table'         => new sfWidgetFormFilterInput(),
      'parent_id'            => new sfWidgetFormFilterInput(),
      'company_id'           => new sfWidgetFormFilterInput(),
      'i_customer'           => new sfWidgetFormFilterInput(),
      'i_xdr'                => new sfWidgetFormFilterInput(),
      'account_id'           => new sfWidgetFormFilterInput(),
      'account_type'         => new sfWidgetFormFilterInput(),
      'cli'                  => new sfWidgetFormFilterInput(),
      'phone_number'         => new sfWidgetFormFilterInput(),
      'country_id'           => new sfWidgetFormPropelChoice(array('model' => 'Country', 'add_empty' => true)),
      'charged_quantity'     => new sfWidgetFormFilterInput(),
      'description'          => new sfWidgetFormFilterInput(),
      'charged_amount'       => new sfWidgetFormFilterInput(),
      'vat_included_amount'  => new sfWidgetFormFilterInput(),
      'charged_vat_value'    => new sfWidgetFormFilterInput(),
      'subdivision'          => new sfWidgetFormFilterInput(),
      'disconnect_cause'     => new sfWidgetFormFilterInput(),
      'bill_status'          => new sfWidgetFormFilterInput(),
      'connect_time'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'unix_connect_time'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'disconnect_time'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'unix_disconnect_time' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'bill_time'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'status'               => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'parent_table'         => new sfValidatorPass(array('required' => false)),
      'parent_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'company_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'i_customer'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'i_xdr'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'account_id'           => new sfValidatorPass(array('required' => false)),
      'account_type'         => new sfValidatorPass(array('required' => false)),
      'cli'                  => new sfValidatorPass(array('required' => false)),
      'phone_number'         => new sfValidatorPass(array('required' => false)),
      'country_id'           => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Country', 'column' => 'id')),
      'charged_quantity'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'description'          => new sfValidatorPass(array('required' => false)),
      'charged_amount'       => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'vat_included_amount'  => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'charged_vat_value'    => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'subdivision'          => new sfValidatorPass(array('required' => false)),
      'disconnect_cause'     => new sfValidatorPass(array('required' => false)),
      'bill_status'          => new sfValidatorPass(array('required' => false)),
      'connect_time'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'unix_connect_time'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'disconnect_time'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'unix_disconnect_time' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'bill_time'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'status'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('employee_customer_callhistory_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'EmployeeCustomerCallhistory';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'parent_table'         => 'Text',
      'parent_id'            => 'Number',
      'company_id'           => 'Number',
      'i_customer'           => 'Number',
      'i_xdr'                => 'Number',
      'account_id'           => 'Text',
      'account_type'         => 'Text',
      'cli'                  => 'Text',
      'phone_number'         => 'Text',
      'country_id'           => 'ForeignKey',
      'charged_quantity'     => 'Number',
      'description'          => 'Text',
      'charged_amount'       => 'Number',
      'vat_included_amount'  => 'Number',
      'charged_vat_value'    => 'Number',
      'subdivision'          => 'Text',
      'disconnect_cause'     => 'Text',
      'bill_status'          => 'Text',
      'connect_time'         => 'Date',
      'unix_connect_time'    => 'Date',
      'disconnect_time'      => 'Date',
      'unix_disconnect_time' => 'Date',
      'bill_time'            => 'Date',
      'status'               => 'Number',
    );
  }
}
