<?php

/**
 * EmployeeSubscriptionCalllogs form base class.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 */
class BaseEmployeeSubscriptionCalllogsForm extends BaseFormPropel
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'parent'      => new sfWidgetFormInput(),
      'parent_id'   => new sfWidgetFormInput(),
      'description' => new sfWidgetFormInput(),
      'todate'      => new sfWidgetFormDateTime(),
      'fromdate'    => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorPropelChoice(array('model' => 'EmployeeSubscriptionCalllogs', 'column' => 'id', 'required' => false)),
      'parent'      => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'parent_id'   => new sfValidatorInteger(array('required' => false)),
      'description' => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'todate'      => new sfValidatorDateTime(array('required' => false)),
      'fromdate'    => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('employee_subscription_calllogs[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'EmployeeSubscriptionCalllogs';
  }


}
