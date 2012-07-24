<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * ChangeNumberDetail filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseChangeNumberDetailFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_id' => new sfWidgetFormFilterInput(),
      'old_number'  => new sfWidgetFormFilterInput(),
      'new_number'  => new sfWidgetFormFilterInput(),
      'status'      => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'customer_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'old_number'  => new sfValidatorPass(array('required' => false)),
      'new_number'  => new sfValidatorPass(array('required' => false)),
      'status'      => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('change_number_detail_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'ChangeNumberDetail';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'customer_id' => 'Number',
      'old_number'  => 'Text',
      'new_number'  => 'Text',
      'status'      => 'Boolean',
      'created_at'  => 'Date',
    );
  }
}
