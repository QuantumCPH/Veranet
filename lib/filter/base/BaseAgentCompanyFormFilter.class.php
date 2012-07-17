<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * AgentCompany filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseAgentCompanyFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'name'                        => new sfWidgetFormFilterInput(),
      'first_name'                  => new sfWidgetFormFilterInput(),
      'middle_name'                 => new sfWidgetFormFilterInput(),
      'last_name'                   => new sfWidgetFormFilterInput(),
      'cvr_number'                  => new sfWidgetFormFilterInput(),
      'ean_number'                  => new sfWidgetFormFilterInput(),
      'kimarin_agent_id'            => new sfWidgetFormFilterInput(),
      'nationality_id'              => new sfWidgetFormPropelChoice(array('model' => 'Nationality', 'add_empty' => true)),
      'address'                     => new sfWidgetFormFilterInput(),
      'post_code'                   => new sfWidgetFormFilterInput(),
      'country_id'                  => new sfWidgetFormPropelChoice(array('model' => 'Country', 'add_empty' => true)),
      'province_id'                 => new sfWidgetFormFilterInput(),
      'city_id'                     => new sfWidgetFormPropelChoice(array('model' => 'City', 'add_empty' => true)),
      'contact_name'                => new sfWidgetFormFilterInput(),
      'email'                       => new sfWidgetFormFilterInput(),
      'mobile_number'               => new sfWidgetFormFilterInput(),
      'head_phone_number'           => new sfWidgetFormFilterInput(),
      'fax_number'                  => new sfWidgetFormFilterInput(),
      'website'                     => new sfWidgetFormFilterInput(),
      'status_id'                   => new sfWidgetFormPropelChoice(array('model' => 'Status', 'add_empty' => true)),
      'company_type_id'             => new sfWidgetFormPropelChoice(array('model' => 'CompanyType', 'add_empty' => true)),
      'product_detail'              => new sfWidgetFormFilterInput(),
      'commission_period_id'        => new sfWidgetFormPropelChoice(array('model' => 'CommissionPeriod', 'add_empty' => true)),
      'account_manager_id'          => new sfWidgetFormPropelChoice(array('model' => 'User', 'add_empty' => true)),
      'created_at'                  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'agent_commission_package_id' => new sfWidgetFormPropelChoice(array('model' => 'AgentCommissionPackage', 'add_empty' => true)),
      'sms_code'                    => new sfWidgetFormFilterInput(),
      'is_prepaid'                  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'balance'                     => new sfWidgetFormFilterInput(),
      'invoice_method_id'           => new sfWidgetFormFilterInput(),
      'comments'                    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'name'                        => new sfValidatorPass(array('required' => false)),
      'first_name'                  => new sfValidatorPass(array('required' => false)),
      'middle_name'                 => new sfValidatorPass(array('required' => false)),
      'last_name'                   => new sfValidatorPass(array('required' => false)),
      'cvr_number'                  => new sfValidatorPass(array('required' => false)),
      'ean_number'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'kimarin_agent_id'            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nationality_id'              => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Nationality', 'column' => 'id')),
      'address'                     => new sfValidatorPass(array('required' => false)),
      'post_code'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'country_id'                  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Country', 'column' => 'id')),
      'province_id'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'city_id'                     => new sfValidatorPropelChoice(array('required' => false, 'model' => 'City', 'column' => 'id')),
      'contact_name'                => new sfValidatorPass(array('required' => false)),
      'email'                       => new sfValidatorPass(array('required' => false)),
      'mobile_number'               => new sfValidatorPass(array('required' => false)),
      'head_phone_number'           => new sfValidatorPass(array('required' => false)),
      'fax_number'                  => new sfValidatorPass(array('required' => false)),
      'website'                     => new sfValidatorPass(array('required' => false)),
      'status_id'                   => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Status', 'column' => 'id')),
      'company_type_id'             => new sfValidatorPropelChoice(array('required' => false, 'model' => 'CompanyType', 'column' => 'id')),
      'product_detail'              => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'commission_period_id'        => new sfValidatorPropelChoice(array('required' => false, 'model' => 'CommissionPeriod', 'column' => 'id')),
      'account_manager_id'          => new sfValidatorPropelChoice(array('required' => false, 'model' => 'User', 'column' => 'id')),
      'created_at'                  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'agent_commission_package_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'AgentCommissionPackage', 'column' => 'id')),
      'sms_code'                    => new sfValidatorPass(array('required' => false)),
      'is_prepaid'                  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'balance'                     => new sfValidatorSchemaFilter('text', new sfValidatorNumber(array('required' => false))),
      'invoice_method_id'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comments'                    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('agent_company_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'AgentCompany';
  }

  public function getFields()
  {
    return array(
      'id'                          => 'Number',
      'name'                        => 'Text',
      'first_name'                  => 'Text',
      'middle_name'                 => 'Text',
      'last_name'                   => 'Text',
      'cvr_number'                  => 'Text',
      'ean_number'                  => 'Number',
      'kimarin_agent_id'            => 'Number',
      'nationality_id'              => 'ForeignKey',
      'address'                     => 'Text',
      'post_code'                   => 'Number',
      'country_id'                  => 'ForeignKey',
      'province_id'                 => 'Number',
      'city_id'                     => 'ForeignKey',
      'contact_name'                => 'Text',
      'email'                       => 'Text',
      'mobile_number'               => 'Text',
      'head_phone_number'           => 'Text',
      'fax_number'                  => 'Text',
      'website'                     => 'Text',
      'status_id'                   => 'ForeignKey',
      'company_type_id'             => 'ForeignKey',
      'product_detail'              => 'Number',
      'commission_period_id'        => 'ForeignKey',
      'account_manager_id'          => 'ForeignKey',
      'created_at'                  => 'Date',
      'agent_commission_package_id' => 'ForeignKey',
      'sms_code'                    => 'Text',
      'is_prepaid'                  => 'Boolean',
      'balance'                     => 'Number',
      'invoice_method_id'           => 'Number',
      'comments'                    => 'Text',
    );
  }
}
