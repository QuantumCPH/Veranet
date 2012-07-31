<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * Odrs filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseOdrsFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'i_xdr'               => new sfWidgetFormFilterInput(),
      'parent_table'        => new sfWidgetFormFilterInput(),
      'parent_id'           => new sfWidgetFormFilterInput(),
      'account_id'          => new sfWidgetFormFilterInput(),
      'cli'                 => new sfWidgetFormFilterInput(),
      'description'         => new sfWidgetFormFilterInput(),
      'bill_start'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'bill_end'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'charged_amount'      => new sfWidgetFormFilterInput(),
      'company_id'          => new sfWidgetFormFilterInput(),
      'connect_time'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'disconnect_time'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'bill_time'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'i_service'           => new sfWidgetFormFilterInput(),
      'vat_included_amount' => new sfWidgetFormFilterInput(),
      'charged_vat_value'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'i_xdr'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'parent_table'        => new sfValidatorPass(array('required' => false)),
      'parent_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'account_id'          => new sfValidatorPass(array('required' => false)),
      'cli'                 => new sfValidatorPass(array('required' => false)),
      'description'         => new sfValidatorPass(array('required' => false)),
      'bill_start'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'bill_end'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'charged_amount'      => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'company_id'          => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'connect_time'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'disconnect_time'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'bill_time'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'i_service'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'vat_included_amount' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'charged_vat_value'   => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('odrs_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'Odrs';
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
      'description'         => 'Text',
      'bill_start'          => 'Date',
      'bill_end'            => 'Date',
      'charged_amount'      => 'Number',
      'company_id'          => 'Number',
      'connect_time'        => 'Date',
      'disconnect_time'     => 'Date',
      'bill_time'           => 'Date',
      'i_service'           => 'Number',
      'vat_included_amount' => 'Number',
      'charged_vat_value'   => 'Number',
    );
  }
}
