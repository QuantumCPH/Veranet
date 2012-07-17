<?php

/**
 * TransactionSection form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseTransactionSectionForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'sectionId'     => new sfWidgetFormInputHidden(),
      'sectiontTitle' => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'sectionId'     => new sfValidatorPropelChoice(array('model' => 'TransactionSection', 'column' => 'sectionId', 'required' => false)),
      'sectiontTitle' => new sfValidatorString(array('max_length' => 255)),
    ));

    $this->widgetSchema->setNameFormat('transaction_section[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TransactionSection';
  }


}
