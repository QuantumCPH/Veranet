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
      'company_id'           => new sfWidgetFormFilterInput(),
      'i_xdr'                => new sfWidgetFormFilterInput(),
      'account_id'           => new sfWidgetFormFilterInput(),
      'cli'                  => new sfWidgetFormFilterInput(),
      'phone_number'         => new sfWidgetFormFilterInput(),
      'country_id'           => new sfWidgetFormFilterInput(),
      'charged_quantity'     => new sfWidgetFormFilterInput(),
      'duration'             => new sfWidgetFormFilterInput(),
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
      'company_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'i_xdr'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'account_id'           => new sfValidatorPass(array('required' => false)),
      'cli'                  => new sfValidatorPass(array('required' => false)),
      'phone_number'         => new sfValidatorPass(array('required' => false)),
      'country_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'charged_quantity'     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'duration'             => new sfValidatorPass(array('required' => false)),
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
      'company_id'           => 'Number',
      'i_xdr'                => 'Number',
      'account_id'           => 'Text',
      'cli'                  => 'Text',
      'phone_number'         => 'Text',
      'country_id'           => 'Number',
      'charged_quantity'     => 'Number',
      'duration'             => 'Text',
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
