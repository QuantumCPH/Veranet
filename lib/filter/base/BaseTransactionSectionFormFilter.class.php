<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/base/BaseFormFilterPropel.class.php');

/**
 * TransactionSection filter form base class.
 *
 * @package    zapnacrm
 * @subpackage filter
 * @author     Your name here
 */
class BaseTransactionSectionFormFilter extends BaseFormFilterPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'sectiontTitle' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'sectiontTitle' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('transaction_section_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TransactionSection';
  }

  public function getFields()
  {
    return array(
      'sectionId'     => 'Number',
      'sectiontTitle' => 'Text',
    );
  }
}
