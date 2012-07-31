<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * CompanyNetBalance filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseCompanyNetBalanceFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'bill_start'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'bill_end'    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'net_balance' => new sfWidgetFormFilterInput(),
      'company_id'  => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'bill_start'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'bill_end'    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'net_balance' => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'company_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('company_net_balance_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CompanyNetBalance';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'bill_start'  => 'Date',
      'bill_end'    => 'Date',
      'net_balance' => 'Number',
      'company_id'  => 'Number',
    );
  }
}
