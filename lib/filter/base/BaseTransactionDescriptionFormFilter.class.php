<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * TransactionDescription filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseTransactionDescriptionFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'title'                  => new sfWidgetFormFilterInput(),
      'transaction_type_id'    => new sfWidgetFormPropelChoice(array('model' => 'TransactionType', 'add_empty' => true)),
      'transaction_section_id' => new sfWidgetFormPropelChoice(array('model' => 'TransactionSection', 'add_empty' => true)),
      'b2c'                    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'b2b'                    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
    ));

    $this->setValidators(array(
      'title'                  => new sfValidatorPass(array('required' => false)),
      'transaction_type_id'    => new sfValidatorPropelChoice(array('required' => false, 'model' => 'TransactionType', 'column' => 'id')),
      'transaction_section_id' => new sfValidatorPropelChoice(array('required' => false, 'model' => 'TransactionSection', 'column' => 'sectionId')),
      'b2c'                    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'b2b'                    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
    ));

    $this->widgetSchema->setNameFormat('transaction_description_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TransactionDescription';
  }

  public function getFields()
  {
    return array(
      'id'                     => 'Number',
      'title'                  => 'Text',
      'transaction_type_id'    => 'ForeignKey',
      'transaction_section_id' => 'ForeignKey',
      'b2c'                    => 'Boolean',
      'b2b'                    => 'Boolean',
    );
  }
}
