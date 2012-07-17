<?php

/**
 * BillingProducts form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseBillingProductsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'title'      => new sfWidgetFormInput(),
      'a_iproduct' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorPropelChoice(array('model' => 'BillingProducts', 'column' => 'id', 'required' => false)),
      'title'      => new sfValidatorString(array('max_length' => 50)),
      'a_iproduct' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('billing_products[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'BillingProducts';
  }


}
