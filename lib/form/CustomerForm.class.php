<?php

/**
 * Customer form.
 *
 * @package    zapnacrm
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: CustomerForm.class.php,v 1.6 2010-09-19 22:20:12 orehman Exp $
 */
class CustomerForm extends BaseCustomerForm
{

	
  public function configure()
  {
  	parent::setup();
  	//mobile_number

	$this->validatorSchema['mobile_number'] = new sfValidatorAnd(
		array(
			$this->validatorSchema['mobile_number'],
			new sfValidatorRegex(
				array(
					'pattern'=>'/^[0-9]{8,12}$/',
				),
				array('invalid'=>'Please enter a valid 8 digit mobile number.')
			)
		)
	);
	
	//$this->widgetSchema->setClass('mobile_number', 'required');
       // $emailWidget = new sfWidgetFormInput(array(), array('class' => 'required email'));

	//pobox
	//sfValidatorString
	$this->validatorSchema['po_box_number'] = new sfValidatorNumber(
		array('required'=>true),
		array('invalid'=>'Please enter a valid postal code. E.g. 3344 ')
	);
  	
  	//product
  	
	$product_criteria = new Criteria();
	if(sfConfig::get('sf_app')=='agent'){
            $product_criteria->add(ProductPeer::IS_IN_STORE, true);
        }
        else if(sfConfig::get('sf_app')=='b2c'){
            $product_criteria->add(ProductPeer::INCLUDE_IN_ZEROCALL, true);
        }
  
        //$product_criteria->add(ProductPeer::IS_IN_STORE, false);
//        if(strcmp(sfConfig::get('sf_app'),'agent')){
//        $product_criteria->add(ProductPeer::IS_IN_STORE, 1, Criteria::EQUAL);
//        }else if (strcmp(sfConfig::get('sf_app'),'b2c')){
//        $product_criteria->add(ProductPeer::INCLUDE_IN_ZEROCALL, 1, Criteria::EQUAL);
//        }
	$this->widgetSchema['product'] = new sfWidgetFormPropelChoice(array(
	                'model' => 'Product',
	                'order_by' => array('ProductOrder','asc'),
					'criteria'	=>	$product_criteria,
					//'add_empty' => 'Choose a product',
	        ));
	        

	        
	$this->validatorSchema['product'] = new sfValidatorPropelChoice(array(
    								'model'		=> 'Product',
    								'column'	=> 'id',
									'criteria'	=>	$product_criteria,
    							),array(
    								'required'	=> 'Please choose a product',
    								'invalid'	=> 'Invalid product',
    							));
        //-----------------For get the Sim Types---------------------
            $this->widgetSchema['sim_type_id'] = new sfWidgetFormPropelChoice(array(
                    'model' => 'SimTypes',
                    'order_by' => array('Title','asc'),
                    //'add_empty' => 'Choose a product',
            ));
            //----------------------------------------------------------
            //-----------------For get the Preferred languages---------------------
            $this->widgetSchema['preferred_language_id'] = new sfWidgetFormPropelChoice(array(
                    'model' => 'PreferredLanguages',
                    'order_by' => array('Language','asc')
            ));
            //----------------------------------------------------------
            //-----------------For get the Province---------------------
            $this->widgetSchema['province_id'] = new sfWidgetFormPropelChoice(array(
                    'model' => 'Province',
                    'order_by' => array('Province','asc')
            ));
            //----------------------------------------------------------
            //-----------------For get the Nationality---------------------
            $this->widgetSchema['nationality_id'] = new sfWidgetFormPropelChoice(array(
                    'model' => 'Nationality',
                    'order_by' => array('Title','asc')
            ));
            //----------------------------------------------------------
            //
            //
            //


    //date of birth
	$years = range(1950, 2020);
	$this->widgetSchema['date_of_birth']->setOption('years' , array_combine($years, $years));
	$this->widgetSchema['date_of_birth']->setOption('format', '%day% %month% %year%');
	       
	//manufacturer
	$this->widgetSchema['manufacturer'] = new sfWidgetFormPropelChoice(array(
	                'model' => 'Manufacturer',
	                'order_by' => array('Name','asc'),
	        		), array (
	        			'required'=> 'Please choose a manufacturer'
	        		)
	        );
	/*
	$this->widgetSchema['device_id'] = new sfWidgetFormPropelChoice(array(
	                'model' => 'Device',
	                'order_by' => array('Name','asc'),
					'add_empty' => 'select model'
	        ));
	
	*/
	//device_id
	$this->validatorSchema['device_id'] = new sfValidatorPropelChoice(array(
    								'model'		=> 'Device',
    								'column'	=> 'id',
    							),array(
    								'required'	=> 'Please choose mobile model',
    								'invalid'	=> 'Invalid model',
    							));

  	//email
  	$this->validatorSchema['email'] = new sfValidatorAnd(
  		array(
  			$this->validatorSchema['email'],
  			new sfValidatorString(
  				array (
  					'min_length'=>5,
  				)
  			),
  			new sfValidatorRegex(
				array(
					'pattern'=>'/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i',
                                        'min_length'=>3,
				),
				array('invalid'=>sfContext::getInstance()->getI18N()->__('Please enter a valid e-mail address.'))
			)
  			
  		)
  	);
  	
  	//password
   	$this->validatorSchema['password'] = new sfValidatorAnd(
  		array(
  			$this->validatorSchema['password'],
  			new sfValidatorString(
  				array (
  					'min_length'=>6,
  				)
  			),
  			
  		)
  	); 	
  	
  	$this->widgetSchema['password'] = new sfWidgetFormInputPassword();
  	
  	//password_confirm
  	
  	$this->validatorSchema['password_confirm'] = clone $this->validatorSchema['password'];
  	
  	$this->setWidget('password_confirm', $this->widgetSchema['password']);
  	
  	$this->widgetSchema->moveField('password_confirm', 'after', 'password'); 


    
    //terms and conditions
	$this->setWidget('terms_conditions', new sfWidgetFormInputCheckbox(array(), array('class'=>'chkbx')));
	$this->setValidator('terms_conditions', new sfValidatorString(array('required' => true), array('required' => 'Please accept the terms and conditions')));
    
	//news letter subscriber
	$this->widgetSchema['is_newsletter_subscriber'] = new sfWidgetFormInputCheckbox(array(), array('class'=>'chkbx'));

	//auto_refill_amount
	$this->setWidget('auto_refill_amount', new sfWidgetFormChoice(array(
								'choices'=>ProductPeer::getRefillHashChoices(),
								'expanded'=>false,
					)));

	$this->setValidator('auto_refill_amount', new sfValidatorChoice( 
		array(
		'choices' => array_keys(ProductPeer::getRefillHashChoices()),
		'required' => false				
		)
	));
	
	//auto_refill_min_balance
	$this->setWidget('auto_refill_min_balance', new sfWidgetFormChoice(
		array(
			'choices'=>ProductPeer::getAutoRefillLowerLimitHashChoices(),
			'expanded'=>false,
		)
	));
	
	$this->setValidator('auto_refill_min_balance', new sfValidatorChoice( 
		array(
			'choices' => array_keys(ProductPeer::getAutoRefillLowerLimitHashChoices()),
			'required' => false				
		)
	));
	
	//hidden filelds
  	//referrer
  	
  	$this->widgetSchema['referrer_id'] = new sfWidgetFormInputHidden();	
	$this->widgetSchema['customer_status_id'] = new sfWidgetFormInputHidden();

	//set help
	$this->widgetSchema->setHelp('terms_conditions', sfContext::getInstance()->getI18n()->__('Please check this box to confirm that you have<br />read and accept the 1 terms and conditions.',array('1',sfConfig::get("app_site_title"))));
	$this->widgetSchema->setHelp('is_newsletter_subscriber', sfContext::getInstance()->getI18n()->__('Yes, subscribe me to newsletter'));
	$this->widgetSchema->setHelp('auto_refill', sfContext::getInstance()->getI18N()->__('Auto refill?'));
	$this->validatorSchema->addOption('allow_extra_fields', true);
	
	//set up other fields
	$this->setWidget('HTTP_COOKIE', new sfWidgetFormInputHidden());
	
	//labels
	$this->widgetSchema->setLabels(
		array(
			'po_box_number'=>'Postcode',
			'telecom_operator_id'=>'Mobile service provider',
			'manufacturer'=>'Mobile brand',
                        'to_date'=>'To date',
                        'from_date'=>'From date',
			'country_id'=>'Country',
			'device_id'=>'Mobile Model',
			'password_confirm'=>'Confirm password',
			'date_of_birth'=>'Date of birth<br />(dd-mm-yyyy)',
                        'second_last_name'=> 'Middle Name',
                        'nie_passport_number'=>'N.I.E. or passport<br />number', 
                        'preferred_language_id'=>'Preferred language', 
                        'province_id'=>'Province',
                        'sim_type_id'=>'SIM type',
                        'nationality_id'=>'Country of citizenship',
                        'mobile_number'=>'Mobile number 0034',
                        'city'=>'Town/city',
                        'email'=>'E-mail'
		)
	);
	
	//defaults
	$this->setDefaults(array(
		'is_newsletter_subscriber'=> true,
		'country_id'=>sfConfig::get('app_country_code'),
		'is_newsletter_subscriber'=>1,
		'customer_status_id'=>1
	));

        $decorator = new sidFormFormatter($this->widgetSchema, $this->validatorSchema);
        $this->widgetSchema->addFormFormatter('custom', $decorator);
        $this->widgetSchema->setFormFormatterName('custom'); 
	
	
  }
  
  /*
   * return all values back, to identify if there is any customer with
   * specified mobile number and with status not equals 1
   */
  public function validateUniqueCustomer(sfValidatorBase $validator, $values)
  {
  	
  	$c = new Criteria();
    	$c->add(CustomerPeer::MOBILE_NUMBER, $values['mobile_number']);
  	$c->addAnd(CustomerPeer::CUSTOMER_STATUS_ID,3);
        $c->addAnd(CustomerPeer::BLOCK,0);
   	if (CustomerPeer::doCount($c)>=1)
  	{
  	      throw new sfValidatorErrorSchema($validator, array(
	        'mobile_number' => new sfValidatorError($validator, 'Number already registered.'),
	      ));	
  	}

  	return $values;
  }
  
  public function validateUniquePassportNo(sfValidatorBase $validator, $values)
  {
  	
  	$c = new Criteria();
  	if($values['nie_passport_number']!="" && $values['nie_passport_number']!=0){
            $c->add(CustomerPeer::NIE_PASSPORT_NUMBER, $values['nie_passport_number']);
            $c->addAnd(CustomerPeer::CUSTOMER_STATUS_ID,3);
             $c->addAnd(CustomerPeer::BLOCK,0);
            if (CustomerPeer::doCount($c)>=1)
            {
                  throw new sfValidatorErrorSchema($validator, array(
                    'nie_passport_number' => new sfValidatorError($validator, 'N.I.E/Passport Number already registered.'),
                  ));	
            }
        }
  	return $values;
  }
}
