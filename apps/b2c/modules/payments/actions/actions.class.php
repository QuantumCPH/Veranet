<?php
require_once(sfConfig::get('sf_lib_dir') . '/changeLanguageCulture.php');
require_once(sfConfig::get('sf_lib_dir') . '/emailLib.php');
require_once(sfConfig::get('sf_lib_dir') . '/commissionLib.php');
require_once(sfConfig::get('sf_lib_dir') . '/smsCharacterReplacement.php');
require_once(sfConfig::get('sf_lib_dir') . '/payment.class.php');


/**
 * payments actions.
 *
 * @package    zapnacrm
 * @subpackage payments
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php,v 1.6 2010-09-19 18:53:06 orehman Exp $
 */
class paymentsActions extends sfActions {
 private function getTargetUrl() {
        return sfConfig::get('app_customer_url');
    }
    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->forward('default', 'module');
    }

    public function executeThankyou(sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11
      $lnaugeval=$request->getParameter('lng');
        if(isset($lnaugeval) && $lnaugeval!=''){
        $this->getUser()->setCulture($request->getParameter('lng'));
        }
        $urlval = "thanks-" . $request->getParameter('transact');

        $email2 = new DibsCall();
        $email2->setCallurl($urlval);

        $email2->save();
    }

    public function executeReject(sfWebRequest $request) {
        
        $Parameters=$request->getURI();

       // $Parameters=$Parameters.$request->getParameter('amount');
        $email2 = new DibsCall();
        $email2->setCallurl($Parameters);

        $email2->save();
        
        //get the order_id
        $order_id = $request->getParameter('orderid');
        //$error_text = substr($request->getParameter('errortext'), 0, strpos($request->getParameter('errortext'), '!'));
        $error_text = $this->getContext()->getI18N()->__('Your payment has not been accepted, due to incorrect payment information. Please enter the correct payment information.');

        $this->forward404Unless($order_id);

        $order = CustomerOrderPeer::retrieveByPK($order_id);
        $c = new Criteria();
        $c->add(TransactionPeer::ORDER_ID, $order_id);
        $transaction = TransactionPeer::doSelectOne($c);

        $this->forward404Unless($order);

        $order->setOrderStatusId(4); //cancelled

        $this->getUser()->setFlash('error_payment',
                $error_text
        );

        $this->order = $order;
        $this->forward404Unless($this->order);

        $this->order_id = $order->getId();
        $this->amount = $transaction->getAmount();
        $this->form = new PaymentForm();

        $this->setTemplate('signup');
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));
        if ($form->isValid()) {
            $product_id = $this->getUser()->getAttribute('product_id', '', 'usersignup');
            $customer_id = $this->getUser()->getAttribute('customer_id', '', 'usersignup');

            if ($product_id == '' || $customer_id == '') {
                $this->forward404('Product or customer id not found in session');
            }

            $order = new Order();
            $transaction = new Transaction();
            $product = ProductPeer::retrieveByPK($product_id);

            $order->setProductId($product_id);
            $order->setCustomerId($customer_id);
            $order->setExtraRefill($form->getValue('extra_refill'));
            $order->setIsFirstOrder(1);

            $order->save();

            $transaction->setAmount($product->getPrice() + $order->getExtraRefill());
            $transaction->setDescription('Product order');
            $transaction->setOrderId($order->getId());
            $transaction->setCustomerId($customer_id);
            //$transaction->setTransactionStatusId() // default value 1

            $transaction->save();

            $this->processTransaction($form->getValues(), $transaction, $request);

            $this->redirect('@signup_complete');
        }
    }

    public function executeSignup(sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11
      

        //$this->getUser()->setCulture('en');
        //$getCultue = $this->getUser()->getCulture();
        // Store data in the user session
        //$this->getUser()->setAttribute('activelanguage', $getCultue);
        $this->targetUrl = $this->getTargetUrl();
        $this->form = new PaymentForm();

///////////////////////postal charges section//////////////////////////////
        $lang =  sfConfig::get('app_language_symbol');
        $this->lang = $lang;

        $countrylng = new Criteria();
        $countrylng->add(EnableCountryPeer::LANGUAGE_SYMBOL, $lang);
        $countrylng = EnableCountryPeer::doSelectOne($countrylng);
        if($countrylng){
            $countryName = $countrylng->getName();
            $languageSymbol = $countrylng->getLanguageSymbol();
            $lngId = $countrylng->getId();

            $postalcharges = new Criteria();
            $postalcharges->add(PostalChargesPeer::COUNTRY, $lngId);
            $postalcharges->add(PostalChargesPeer::STATUS, 1);
            $postalcharges = PostalChargesPeer::doSelectOne($postalcharges);
            if($postalcharges){
              $this->postalcharge =  $postalcharges->getCharges();
            }else{
                $this->postalcharge =  0;
            }
        }









///////////////////////////////////////////////////////////////////////////////////////
        $product_id = $request->getParameter('pid');
        $customer_id = $request->getParameter('cid');

        $this->getUser()->setAttribute('product_ids', $product_id);
        $this->getUser()->setAttribute('cusid', $customer_id);

        if ($product_id == '' || $customer_id == '') {
            $this->forward404('Product id not found in session');
        }
        $order = new CustomerOrder();
        $transaction = new Transaction();
        $order->setProductId($product_id);
        $order->setCustomerId($customer_id);
        $order->setExtraRefill($order->getProduct()->getInitialBalance());
        //$extra_refil_choices = ProductPeer::getRefillChoices();
        //TODO: restrict quantity to be 1
        $order->setQuantity(1);
        //$order->setExtraRefill($extra_refil_choices[0]);//minumum refill amount
        $order->setIsFirstOrder(1);
        $order->save();
        $transaction->setAmount($order->getProduct()->getPrice() + $this->postalcharge + $order->getProduct()->getRegistrationFee()+(($this->postalcharge + $order->getProduct()->getRegistrationFee())*sfConfig::get('app_vat_percentage')));
        //TODO: $transaction->setAmount($order->getProduct()->getPrice());
        $transaction->setDescription('Registration');
        $transaction->setOrderId($order->getId());
        $transaction->setCustomerId($customer_id);
        //$transaction->setTransactionStatusId() // default value 1
        $transaction->save();
        $this->order = $order;
        $this->forward404Unless($this->order);
        $this->order_id = $order->getId();
        $this->amount = $transaction->getAmount();
    }

    protected function processTransaction($creditcardinfo = null, Transaction $transactionObj = null, sfWebRequest $request
    ) {

        $relay_script_url = 'https://relay.ditonlinebetalingssystem.dk/relay/v2/relay.cgi/';

        $transactionInfo = array(
            'cardno' => $creditcardinfo['cardno'],
            'expmonth' => $creditcardinfo['expmonth'],
            'expyear' => $creditcardinfo['expyear'],
            'cvc' => $creditcardinfo['cvc'],
            'merchantnumber' => sfConfig::get('app_epay_merchant_number'),
            'currency' => sfConfig::get('app_epay_currency'),
            'instantCapture' => sfConfig::get('app_epay_instant_capture'),
            'authemail' => sfConfig::get('app_epay_authemail'),
            'orderid' => $transactionObj->getOrderId(),
            'amount' => $transactionObj->getAmount(),
            'accepturl' => $relay_script_url . $this->getController()->genUrl('@epay_accept_url'),
            'declineurl' => $relay_script_url . $this->getController()->genUrl('@epay_reject_url'),
        );
    }

    public function executeShowReceipt(sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        changeLanguageCulture::languageCulture($request, $this);

        //is authenticated
        $this->customer = CustomerPeer::retrieveByPK(
                        $this->getUser()->getAttribute('customer_id', null, 'usersession')
        );

        $this->redirectUnless($this->customer, '@customer_login');
        //check to see if transaction id is there
        $lang =  sfConfig::get('app_language_symbol');
        $this->lang = $lang;
        
        $countrylng = new Criteria();
        $countrylng->add(EnableCountryPeer::LANGUAGE_SYMBOL,$lang );
        $countrylng = EnableCountryPeer::doSelectOne($countrylng);
        if($countrylng){
            $countryName = $countrylng->getName();
            $languageSymbol = $countrylng->getLanguageSymbol();
            $lngId = $countrylng->getId();
            
            $postalcharges = new Criteria();
            $postalcharges->add(PostalChargesPeer::COUNTRY, $lngId);
            $postalcharges->add(PostalChargesPeer::STATUS, 1);
            $postalcharges = PostalChargesPeer::doSelectOne($postalcharges);
            //var_dump($postalcharges);
            if($postalcharges){
                $postalcharge =  $postalcharges->getCharges();
               $forIphoneAdaptor = $postalcharge;
            }else{
                $postalcharge =  '';
            }
        }
        $transaction_id = $request->getParameter('tid');

        $this->forward404Unless($transaction_id);

        //is this receipt really belongs to authenticated user

        $transaction = TransactionPeer::retrieveByPK($transaction_id);

        $this->forward404Unless($transaction->getCustomerId() == $this->customer->getId(), 'Not allowed');

        //set customer order
        $customer_order = CustomerOrderPeer::retrieveByPK($transaction->getOrderId());
       // $this->customer_order = $customer_order;
        $customerorder = $customer_order->getIsFirstOrder();
        if ($customer_order) {
            $vat = $customer_order->getIsFirstOrder() ?
                    ($customer_order->getProduct()->getRegistrationFee()+$postalcharge) * sfConfig::get('app_vat_percentage') : 0;
        }
        else
            die('Error retreiving');

        if(strstr($transaction->getDescription(),"Refill")||strstr($transaction->getDescription(),"Charge")){
            $vat = $transaction->getAmount() - ($transaction->getAmount()/(sfConfig::get('app_vat_percentage')+1));
        }

        $this->renderPartial('payments/order_receipt', array(
            'customer' => $this->customer,
            'order' => CustomerOrderPeer::retrieveByPK($transaction->getOrderId()),
            'transaction' => $transaction,
            'vat' => $vat,
            'postalcharge'=>$postalcharge,
            'customerorder'=>$customerorder,
        ));

        return sfView::NONE;
    }



    public function executeCtpay(sfWebRequest $request) {
      
        $urlval = $request->getParameter('transact');
        $email2 = new DibsCall();
        $email2->setCallurl($urlval);
        $email2->save();
    }

  public function executeTest(sfWebRequest $request) {


  
       return sfView::NONE;
    }

    public function executeTransaction(sfWebRequest $request)
    {
        $order_id = $request->getParameter('item_number');
        $item_amount = $request->getParameter('amount');        
        
        
        $lang=$this->getUser()->getCulture();
      
        $return_url = $this->getTargetUrl().'payments/thankyou';
        $cancel_url = $this->getTargetUrl().'payments/reject';
        
        $callbackparameters = $lang.'-'.$order_id.'-'.$item_amount;
        $notify_url = $this->getTargetUrl().'pScripts/confirmpayment?p='.$callbackparameters;        
        
        $email2 = new DibsCall();
        $email2->setCallurl($notify_url);

        $email2->save();
        
        
        $querystring = '';
     
        $order = CustomerOrderPeer::retrieveByPK($order_id);
        $item_name = $order->getProduct()->getName();
        
	//loop for posted values and append to querystring
	foreach($_POST as $key => $value){
		$value = urlencode(stripslashes($value));
		$querystring .= "$key=$value&";
	}
        
        $querystring .= "item_name=".urlencode($item_name)."&";
        $querystring .= "return=".urldecode($return_url)."&";
        $querystring .= "cancel_return=".urldecode($cancel_url)."&";
	$querystring .= "notify_url=".urldecode($notify_url);
        
        //$environment = "sandbox";
        if($order_id && $item_amount){
	   Payment::SendPayment($querystring);
        }else{
           echo 'error';  
        }
	return sfView::NONE;
	//exit();
    }   
}
