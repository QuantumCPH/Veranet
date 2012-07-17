<?php

/**
 * TransactionDescription form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseTransactionDescriptionForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                     => new sfWidgetFormInputHidden(),
      'title'                  => new sfWidgetFormInput(),
      'transaction_type_id'    => new sfWidgetFormPropelChoice(array('model' => 'TransactionType', 'add_empty' => true)),
      'transaction_section_id' => new sfWidgetFormPropelChoice(array('model' => 'TransactionSection', 'add_empty' => true)),
      'b2c'                    => new sfWidgetFormInputCheckbox(),
      'b2b'                    => new sfWidgetFormInputCheckbox(),
    ));

    $this->setValidators(array(
      'id'                     => new sfValidatorPropelChoice(array('model' => 'TransactionDescription', 'column' => 'id', 'required' => false)),
      'title'                  => new sfValidatorString(array('max_length' => 255)),
      'transaction_type_id'    => new sfValidatorPropelChoice(array('model' => 'TransactionType', 'column' => 'id', 'required' => false)),
      'transaction_section_id' => new sfValidatorPropelChoice(array('model' => 'TransactionSection', 'column' => 'sectionId', 'required' => false)),
      'b2c'                    => new sfValidatorBoolean(array('required' => false)),
      'b2b'                    => new sfValidatorBoolean(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('transaction_description[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TransactionDescription';
  }


}
