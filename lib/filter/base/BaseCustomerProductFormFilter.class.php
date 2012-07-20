<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * CustomerProduct filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseCustomerProductFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'customer_id' => new sfWidgetFormPropelChoice(array('model' => 'Customer', 'add_empty' => true)),
      'product_id'  => new sfWidgetFormPropelChoice(array('model' => 'Product', 'add_empty' => true)),
      'created_at'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'status_id'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'customer_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Customer', 'column' => 'id')),
      'product_id'  => new sfValidatorPropelChoice(array('required' => false, 'model' => 'Product', 'column' => 'id')),
      'created_at'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'status_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('customer_product_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'CustomerProduct';
  }

  public function getFields()
  {
    return array(
      'id'          => 'Number',
      'customer_id' => 'ForeignKey',
      'product_id'  => 'ForeignKey',
      'created_at'  => 'Boolean',
      'status_id'   => 'Number',
    );
  }
}
