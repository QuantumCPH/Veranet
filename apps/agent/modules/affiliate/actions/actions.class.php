<?php

require_once(sfConfig::get('sf_lib_dir') . '/Browser.php');
require_once(sfConfig::get('sf_lib_dir') . '/emailLib.php');
require_once(sfConfig::get('sf_lib_dir') . '/changeLanguageCulture.php');
require_once(sfConfig::get('sf_lib_dir') . '/sms.class.php');
require_once(sfConfig::get('sf_lib_dir') . '/zerocall_out_sms.php');

/**
 * affiliate actions.
 * @package    zapnacrm
 * @subpackage affiliate
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php,v 1.2 2010-08-05 20:37:52 orehman Exp $
 */
class affiliateActions extends sfActions {

    private $currentCulture;
    
    private function getTargetUrl() {
        return sfConfig::get('app_agent_url');
    }

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeIndex(sfWebRequest $request) {
        $this->updateNews = NewupdatePeer::doSelect(new Criteria());
        $this->forward('default', 'module');
    }

    public function executeReceipts(sfWebRequest $request) {

        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham

        $this->updateNews = NewupdatePeer::doSelect(new Criteria());
        $this->forward404Unless($this->getUser()->isAuthenticated());
        $this->targetUrl = $this->getTargetUrl();

        $c = new Criteria();
        $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession');
        $c->add(AgentCompanyPeer::ID, $agent_company_id);
        $agent = AgentCompanyPeer::doSelectOne($c);

        $this->forward404Unless(AgentCompanyPeer::doSelectOne($c));

        $transactions = array();
        $registrations = array();
        $i = 1;

        //echo $agent_company_id;

        $c = new Criteria();
        $c->add(CustomerPeer::REFERRER_ID, $agent_company_id);
        $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
        $c->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
        $customers = CustomerPeer::doSelect($c);

        $startdate = $request->getParameter('startdate');
        $enddate = $request->getParameter('enddate');
        if ($startdate != '') {
            $startdate = date('Y-m-d 00:00:00', strtotime($startdate));
            $this->startdate = date('Y-m-d', strtotime($startdate));
        }else{
            $startdate = date('Y-m-d 00:00:00', strtotime($agent->getCreatedAt()));
            $this->startdate = $startdate;
        }

        if ($enddate != '') {
            $enddate = date('Y-m-d 23:59:59', strtotime($enddate));
            $this->enddate = date('Y-m-d', strtotime($enddate));
        }else{
           $enddate = date('Y-m-d 23:59:59');
        }


        foreach ($customers as $customer) {
            //echo $customer->getId().'<br>';
            $tc = new Criteria();
            $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
            $tc->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
            if ($startdate != "" && $enddate != "") {
                $tc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                $tc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
            }
            $tc->add(TransactionPeer::TRANSACTION_STATUS_ID, 3);
            $tc->add(TransactionPeer::DESCRIPTION, 'Registration');
            if (TransactionPeer::doSelectOne($tc)) {
                $registrations[$i] = TransactionPeer::doSelectOne($tc);
            }
            // echo $customer->getId().'__'.$agent_company_id.'<br>';
            $i = $i + 1;

//                           echo $customer->getMobileNumber();
//                           echo '<br/>';
        }

        //echo count($registrations);
        $ar = new Criteria();
        $ar->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
        $ar->add(TransactionPeer::DESCRIPTION, 'Registration', Criteria::NOT_EQUAL);
        $ar->addAnd(TransactionPeer::DESCRIPTION, 'Fee for change number (' . $agent->getName() . ')', Criteria::NOT_EQUAL);
        if ($startdate != "" && $enddate != "") {
            $ar->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
            $ar->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
        }
        $ar->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
        $ar->addAnd(TransactionPeer::TRANSACTION_STATUS_ID, 3);
        $refills = TransactionPeer::doSelect($ar);

//                foreach ($refills as $refill){
//                    $transactions[$i]=$refill;
//                    $i=$i+1;
//                }

        $cn = new Criteria();
        $cn->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
        $cn->addAnd(TransactionPeer::DESCRIPTION, 'Fee for change number (' . $agent->getName() . ')', Criteria::EQUAL);
        if ($startdate != "" && $enddate != "") {
            $cn->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
            $cn->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
        }
        $cn->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
        $cn->addAnd(TransactionPeer::TRANSACTION_STATUS_ID, 3);
        $numberchange = TransactionPeer::doSelect($cn);
        //var_dump($numberchange);
        $this->registrations = $registrations;
        $this->numberchanges = $numberchange;
        $this->refills = $refills;
        $this->counter = $i - 1;
    }

    public function executePrintReceipt(sfWebRequest $request) {
        //is authenticated
        $this->forward404Unless($this->getUser()->isAuthenticated());
        $this->updateNews = NewupdatePeer::doSelect(new Criteria());

        //check to see if transaction id is there

        $transaction_id = $request->getParameter('tid');
        $this->forward404Unless($transaction_id);
        //is this receipt really belongs to authenticated user

        $transaction = TransactionPeer::retrieveByPK($transaction_id);
        $c = new Criteria();
        $c->add(CustomerPeer::ID, $transaction->getCustomerId());
        $this->customer = CustomerPeer::doSelectOne($c);

        $this->forward404Unless($transaction->getCustomerId() == $this->customer->getId(), 'Not allowed');
        //set customer order
        $customer_order = CustomerOrderPeer::retrieveByPK($transaction->getOrderId());

        if ($customer_order) {
            $vat = $customer_order->getIsFirstOrder() ?
                    $customer_order->getProduct()->getRegistrationFee() * sfConfig::get('app_vat_percentage') :
                    0;
        }
        else
            die('Error retreiving');


        $agent_company_id = $this->customer->getReferrerId();
        if ($agent_company_id != '') {
            $c = new Criteria();
            $c->add(AgentCompanyPeer::ID, $agent_company_id);
            $recepient_agent_name = AgentCompanyPeer::doSelectOne($c)->getName();
        } else {
            $recepient_agent_name = '';
        }

        if ($customer_order->getIsFirstOrder()) {
            $this->renderPartial('affiliate/order_receipt', array(
                'customer' => $this->customer,
                'order' => CustomerOrderPeer::retrieveByPK($transaction->getOrderId()),
                'transaction' => $transaction,
                'agent_name' => $recepient_agent_name,
                'vat' => $vat,
            ));
        } else {
            $this->renderPartial('affiliate/refill_order_receipt', array(
                'customer' => $this->customer,
                'order' => CustomerOrderPeer::retrieveByPK($transaction->getOrderId()),
                'transaction' => $transaction,
                'agent_name' => $recepient_agent_name,
                'vat' => $vat,
                'wrap' => false,
            ));
        }
        return sfView::NONE;
    }

    public function executeNewsListing(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->isAuthenticated());


        $c = new Criteria();
        $c->addDescendingOrderByColumn(NewupdatePeer::STARTING_DATE);
        $news = NewupdatePeer::doSelect($c);
        $this->news = $news;
    }

    public function executeReport(sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham

        $this->forward404Unless($this->getUser()->isAuthenticated());
        $nc = new Criteria();
        $nc->addDescendingOrderByColumn(NewupdatePeer::STARTING_DATE);
        $this->updateNews = NewupdatePeer::doSelect($nc);
        //verify if agent is already logged in
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        $this->forward404Unless($agent);
        $this->agent = $agent;

        $startdate = $request->getParameter('startdate');
        $enddate = $request->getParameter('enddate');
        if ($startdate != '') {
            $startdate = date('Y-m-d 00:00:00', strtotime($startdate));
            $this->startdate = date('Y-m-d', strtotime($startdate));
        }else{
            $startdate = date('Y-m-d 00:00:00', strtotime($this->agent->getCreatedAt()));
            $this->startdate = date('Y-m-d', strtotime($startdate));
        }
        if ($enddate != '') {
            $enddate = date('Y-m-d 23:59:59', strtotime($enddate));
            $this->enddate = date('Y-m-d', strtotime($enddate));
        }else{
            $enddate = date('Y-m-d 23:59:59');
        }





        //get All customer registrations from customer table
        try {
            $c = new Criteria();
            $c->add(CustomerPeer::REFERRER_ID, $agent_company_id);
            $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            $c->add(CustomerPeer::REGISTRATION_TYPE_ID, 4, Criteria::NOT_EQUAL);
            if ($startdate != "" && $enddate != "") {
                    $c->addAnd(CustomerPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                    $c->addAnd(CustomerPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
                }
            $c->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
            $customers = CustomerPeer::doSelect($c);
            $registration_sum = 0.00;
            $registration_commission = 0.00;
            $registrations = array();
            $comregistrations = array();
            $i = 1;
            foreach ($customers as $customer) {
                $tc = new Criteria();
                //echo $customer->getId();
                $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
                $tc->add(TransactionPeer::TRANSACTION_STATUS_ID, 3);
                $tc->add(TransactionPeer::DESCRIPTION, 'Registration');
                if (TransactionPeer::doSelectOne($tc)) {
                    $registrations[$i] = TransactionPeer::doSelectOne($tc);
                }
                $i = $i + 1;
                //
                //                           echo $customer->getId();
                //                           echo '<br/>';
            }
            //                       echo 'transactions';
            //                       echo '<br/>';
            //print_r($registrations);
            if (count($registrations) >= 1) {
                //echo count($registrations);
                foreach ($registrations as $registration) {
                    //                       echo $registration->getCustomerId();
                    //                       echo '<br/>';
                    $registration_sum = $registration_sum + $registration->getAmount();
                    if ($registration != NULL) {
                        $coc = new Criteria();
                        $coc->add(CustomerOrderPeer::ID, $registration->getOrderId());
                        $customer_order = CustomerOrderPeer::doSelectOne($coc);
                        $registration_commission = $registration_commission + ($registration->getCommissionAmount());
                    }
                }
            }
            $this->registrations = $registrations;
            $this->registration_revenue = $registration_sum;
            $this->registration_commission = $registration_commission;
            $cc = new Criteria();
            $cc->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
            $cc->addAnd(TransactionPeer::DESCRIPTION, 'Refill');
            $cc->addAnd(TransactionPeer::TRANSACTION_STATUS_ID, 3);
            if ($startdate != "" && $enddate != "") {
                    $cc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                    $cc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
                }


            $cc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
            $refills = TransactionPeer::doSelect($cc);
            $refill_sum = 0.00;
            $refill_com = 0.00;
            foreach ($refills as $refill) {
                $refill_sum = $refill_sum + $refill->getAmount();
                $refill_com = $refill_com + $refill->getCommissionAmount();
            }
            $this->refills = $refills;
            $this->refill_revenue = $refill_sum;
            $this->refill_com = $refill_com;
            $efc = new Criteria();
            $efc->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
            $efc->add(TransactionPeer::TRANSACTION_STATUS_ID, 3);


             if ($startdate != "" && $enddate != "") {
                    $efc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                    $efc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
                }

            $efc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
            $ef = TransactionPeer::doSelect($efc);
            $ef_sum = 0.00;
            $ef_com = 0.00;
            foreach ($ef as $efo) {
                $description = substr($efo->getDescription(), 0, 26);
                $stringfinds = 'Refill via agent';
                if (strstr($efo->getDescription(), $stringfinds)) {
                    //if($description== 'LandNCall AB Refill via agent ')
                    $ef_sum = $ef_sum + $efo->getAmount();
                    $ef_com = $ef_com + $efo->getCommissionAmount();
                }
            }
            $this->ef = $ef;
            $this->ef_sum = $ef_sum;
            $this->ef_com = $ef_com;
            /////////// SMS Registrations
            $cs = new Criteria();
            $cs->add(CustomerPeer::REFERRER_ID, $agent_company_id);
            $cs->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            $cs->add(CustomerPeer::REGISTRATION_TYPE_ID, 4);
            if ($startdate != "" && $enddate != "") {
                    $cs->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                    $cs->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
                }


            $cs->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
            $sms_customers = CustomerPeer::doSelect($cs);
            $sms_registrations = array();
            $sms_registration_earnings = 0.0;
            $sms_commission_earnings = 0.0;
            $i = 1;
            foreach ($sms_customers as $sms_customer) {
                $tc = new Criteria();
                $tc->add(TransactionPeer::CUSTOMER_ID, $sms_customer->getId());
                $tc->add(TransactionPeer::TRANSACTION_STATUS_ID, 3);
                $tc->add(TransactionPeer::DESCRIPTION, 'Registration');
                $sms_registrations[$i] = TransactionPeer::doSelectOne($tc);
                if (count($sms_registrations) >= 1) {
                    $sms_registration_earnings = $sms_registration_earnings + $sms_registrations[$i]->getAmount();
                    $sms_commission_earnings = $sms_commission_earnings + $sms_registrations[$i]->getCommissionAmount();
                }
                $i = $i + 1;
            }
            $this->sms_registrations = $sms_registrations;
            $this->sms_registration_earnings = $sms_registration_earnings;
            $this->sms_commission_earnings = $sms_commission_earnings;
            ////////// End SMS registrations


            $nc = new Criteria();
            $nc->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
            $nc->addAnd(TransactionPeer::DESCRIPTION, 'Fee for change number (' . $agent->getName() . ')');
            $nc->addAnd(TransactionPeer::TRANSACTION_STATUS_ID, 3);
            $nc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
            $number_changes = TransactionPeer::doSelect($nc);

            $numberChange_earnings = 0.00;
            $numberChange_commission = 0.00;
            foreach ($number_changes as $number_change) {
                $numberChange_earnings = $numberChange_earnings + $number_change->getAmount();
                $numberChange_commission = $numberChange_commission + $number_change->getCommissionAmount();
            }
            $this->number_changes = $number_changes;
            $this->numberChange_earnings = $numberChange_earnings;
            $this->numberChange_commission = $numberChange_commission;



            $this->sf_request = $request;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function executeRefill(sfWebRequest $request) {

        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 03/09/11 - Ahtsham


        $this->browser = new Browser();
        $this->form = new AccountRefillAgent();
        $this->target = $this->getTargetUrl();
        $this->error_msg = "";
        $this->error_mobile_number = "";
        $validated = false;

        //get Agent
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);

        //get Agent commission package
        $cpc = new Criteria();
        $cpc->add(AgentCommissionPackagePeer::ID, $agent->getAgentCommissionPackageId());
        $commission_package = AgentCommissionPackagePeer::doSelectOne($cpc);

        if ($request->getParameter('balance_error')) {
            $this->balance_error = $request->getParameter('balance_error');
        } else {
            $this->balance_error = 0;
        }

        if ($request->isMethod('post')) {
            $mobile_number = $request->getParameter('mobile_number');
            $extra_refill = $request->getParameter('extra_refill');
            $extra_refill = $extra_refill*(sfConfig::get('app_vat_percentage')+1);
            $is_recharged = true;

            $transaction = new Transaction();
            $order = new CustomerOrder();
            $customer = NULL;
            $cc = new Criteria();
            $cc->add(CustomerPeer::MOBILE_NUMBER, $mobile_number);
            $cc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            //$cc->add(CustomerPeer::FONET_CUSTOMER_ID, NULL, Criteria::ISNOTNULL);  // This Line disable becoz no need of fonet system in landncall -
            $customer = CustomerPeer::doSelectOne($cc);

            //echo $customer->getId();

            if ($customer and $mobile_number != "") {
                $validated = true;
            } else {
                $validated = false;
                $is_recharged = false;
                $this->error_mobile_number = $this->getContext()->getI18N()->__('invalid mobile number');
                return;
            }
            if ($validated) {
                $c = new Criteria();
                $c->add(CustomerProductPeer::CUSTOMER_ID, $customer->getId());
                $customer_product = CustomerProductPeer::doSelectOne($c)->getProduct();
                $order->setCustomerId($customer->getId());
                $order->setProductId($customer_product->getId());
                $order->setQuantity(1);
                $order->setExtraRefill($extra_refill);
                $order->setIsFirstOrder(false);
                $order->setOrderStatusId(sfConfig::get('app_status_new'));
                $order->save();

                $transaction->setOrderId($order->getId());
                $transaction->setCustomerId($customer->getId());
                $transaction->setAmount($extra_refill);

                //get agent name
                $transaction->setDescription($this->getContext()->getI18N()->__('Refill via agent') . '(' . $agent->getName() . ')');
                //$transaction->setDescription('Refill');
                $transaction->setAgentCompanyId($agent->getId());

                $order->setAgentCommissionPackageId($agent->getAgentCommissionPackageId());

                $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession');

                $cp = new Criteria;
                $cp->add(AgentProductPeer::AGENT_ID, $agent_company_id);
                $cp->add(AgentProductPeer::PRODUCT_ID, $order->getProductId());
                $agentproductcount = AgentProductPeer::doCount($cp);
                if ($agentproductcount > 0) {
                    $p = new Criteria;
                    $p->add(AgentProductPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
                    $p->add(AgentProductPeer::PRODUCT_ID, $order->getProductId());
                    $agentproductcomesion = AgentProductPeer::doSelectOne($p);
                    $agentcomession = $agentproductcomesion->getExtraPaymentsShareEnable();
                }

                ////////   commission setting  through  agent commision//////////////////////

                if ($agentcomession) {
                    if ($agentproductcomesion->getIsExtraPaymentsShareValuePc()) {
                        $transaction->setCommissionAmount(($transaction->getAmount() / 100) * $agentproductcomesion->getExtraPaymentsShareValue());
                    } else {
                        $transaction->setCommissionAmount($agentproductcomesion->getExtraPaymentsShareValue());
                    }
                } else {
                    if ($commission_package->getIsExtraPaymentsShareValuePc()) {
                        $transaction->setCommissionAmount(($transaction->getAmount() / 100) * $commission_package->getExtraPaymentsShareValue());
                    } else {
                        $transaction->setCommissionAmount($commission_package->getExtraPaymentsShareValue());
                    }
                }
                //calculated amount for agent commission
                if ($agent->getIsPrepaid() == true) {
                    if ($agent->getBalance() < ($transaction->getAmount() - $transaction->getCommissionAmount())) {
                        $is_recharged = false;
                        $balance_error = 1;
                    }
                }

                if ($is_recharged) {
                    $transaction->save();
                    if ($agent->getIsPrepaid() == true) {
                        $agent->setBalance($agent->getBalance() - ($transaction->getAmount() - $transaction->getCommissionAmount()));
                        $agent->save();
                        $remainingbalance = $agent->getBalance();
                        $amount = $transaction->getAmount() - $transaction->getCommissionAmount();
                        $amount = -$amount;
                        $aph = new AgentPaymentHistory();
                        $aph->setAgentId($this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
                        $aph->setCustomerId($transaction->getCustomerId());
                        $aph->setExpeneseType(2);
                        $aph->setAmount($amount);
                        $aph->setRemainingBalance($remainingbalance);
                        $aph->save();
                    }

                    $uniqueId = $customer->getUniqueid();
                    $OpeningBalance = $transaction->getAmount();
                    $OpeningBalance = $OpeningBalance/(sfConfig::get('app_vat_percentage')+1);
                    Telienta::recharge($customer, $OpeningBalance);
                    //set status
                    $order->setOrderStatusId(sfConfig::get('app_status_completed'));
                    $transaction->setTransactionStatusId(sfConfig::get('app_status_completed'));

                    $order->save();
                    $transaction->save();
                    $this->customer = $order->getCustomer();
                    //  $this->getUser()->setCulture('de');
                    $this->setPreferredCulture($this->customer);
                        emailLib::sendRefillEmail($this->customer, $order);
                    $this->updatePreferredCulture();
                    //   $this->getUser()->setCulture('en');
                    $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('%1% account is successfully refilled with %2% %3%.', array("%1%" => $customer->getMobileNumber(), "%2%" => $transaction->getAmount(), "%3%" => sfConfig::get('app_currency_code'))));
//                                      echo 'rehcarged, redirecting';
                    $this->redirect('affiliate/receipts');
                } else {
//                                        echo 'NOT rehcarged, redirecting';
                    $this->balance_error = 1;
                    $this->getUser()->setFlash('error', 'You do not have enough balance, please recharge');
                } //end else
            } else {
//                                        echo 'Form Invalid, redirecting';
                $this->balance_error = 1;
                //$this->getUser()->setFlash('message', 'Invalid mobile number');
                //$this->getUser()->setFlash('error_message', 'Customer Not Found.');
                $is_recharged = false;
                $this->error_mobile_number = $this->getContext()->getI18N()->__('invalid mobile number');
            }
        }
    }

    public function executeRegistrationstep1(sfWebRequest $request) {

        $mobile = "";

        if (isset($_REQUEST['mobileno']) && $_REQUEST['mobileno'] != "") {

            $mobile = $_REQUEST['mobileno'];

            $c = new Criteria();
            $c->addJoin(CustomerProductPeer::CUSTOMER_ID, CustomerPeer::ID, Criteria::LEFT_JOIN);
            $c->add(CustomerProductPeer::PRODUCT_ID, 7);
            $c->add(CustomerPeer::MOBILE_NUMBER, $mobile);
            $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            $customer = CustomerPeer::doSelectOne($c);

            if ($customer) {
                $this->form = new CustomerForm(CustomerPeer::retrieveByPK($customer->getId()));
            } else {
                $this->getUser()->setFlash('message', 'Customer is not a Zerocall Free customer');
                $this->redirect('affiliate/conversionform');
            }
        }


        $c = new Criteria();
        $c->add(AgentCompanyPeer::ID, $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $referrer_id = AgentCompanyPeer::doSelectOne($c); //->getId();
        // $this->form = new CustomerForm(CustomerPeer::retrieveByPK($customer->getId()));
        if ($request->isMethod('post')) {
            if ($mobile == "") {

                $this->form = new CustomerForm(CustomerPeer::retrieveByPK($_REQUEST['customer']['id']));
                $this->form->bind($request->getParameter("newCustomerForm"), $request->getFiles("newCustomerForm"));
                $this->form->setDefault('referrer_id', $referrer_id);
                //   $this->form->setDefault('registration_type_id', 2);
                unset($this->form['terms_conditions']);
                unset($this->form['password']);
                unset($this->form['password_confirm']);



                $this->processFormone($request, $this->form);
            }

            //set referrer id

            $this->form->getWidget('mobile_number')->setAttribute('readonly', 'readonly');
            $this->getUser()->getAttribute('agent_company_id', '', 'agentsession');
            $this->browser = new Browser();







            //  $this->form = new CustomerForm();
            //$this->setLayout();
            sfView::NONE;
        }
    }

    public function executeRegisterCustomer(sfWebRequest $request) {


        $this->getUser()->getAttribute('agent_company_id', '', 'agentsession');
        $this->browser = new Browser();

        $c = new Criteria();
        $c->add(AgentCompanyPeer::ID, $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $referrer_id = AgentCompanyPeer::doSelectOne($c);

        if ($request->isMethod('post')) {

            $this->form = new CustomerForm();

            $this->form->bind($request->getParameter("newCustomerForm"), $request->getFiles("newCustomerForm"));
            $this->form->setDefault('referrer_id', $referrer_id);
            unset($this->form['terms_conditions']);
            unset($this->form['imsi']);
            unset($this->form['uniqueid']);
//                        //unset($this->form['password']);
//                        unset($this->form['terms_conditions']);
            // print_r($this->form);
            //  die;

            $this->processForm($request, $this->form);
        } else {

            $this->form = new CustomerForm();
        }

        //$this->setLayout();
        sfView::NONE;
    }

    protected function processFormone(sfWebRequest $request, sfForm $form) {
        //print_r($request->getParameter($form->getName()));
        $customer = $request->getParameter($form->getName());
        $product = $customer['product'];

        //$customer['referrer_id']= $this->getUser()->getAttribute('agent_company_id', '', 'agentsession');
//echo $customer['id'];
//die;
        //  $this->form = new CustomerForm(CustomerPeer::retrieveByPK($customer['id']));
        unset($this->form['terms_conditions']);
        unset($this->form['imsi']);
        unset($this->form['uniqueid']);
        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));

        //print_r($form);
        // $this->redirect('@customer_registration_step3?customer_id='.$customer['id'].'&product_id='.$product);


        if ($form->isValid()) {
            // $customer=$customer['id'];
            //     $customer->setPlainText($request->getParameter($form->getPassword()));
            $customer = $form->save();

            $customer->setReferrerId($this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
            $customer->setRegistrationTypeId('2');

            $customer->save();

            if ($customer) {

            }

            echo "redirecting";
            $this->redirect('@customer_registration_step3?customer_id=' . $customer->getId() . '&product_id=' . $product);
            //$this->redirect(sfConfig::get('app_epay_relay_script_url').$this->getController()->genUrl('@signup_step2?customer_id='.$customer->getId().'&product_id='.$product, true));
        }
    }

    protected function processForm(sfWebRequest $request, sfForm $form) {
        //print_r($request->getParameter($form->getName()));
        $customer = $request->getParameter($form->getName());
        $product = $customer['product'];
        //$customer['referrer_id']= $this->getUser()->getAttribute('agent_company_id', '', 'agentsession');
        $plainPws = $customer["password"];


        $form->bind($request->getParameter($form->getName()), $request->getFiles($form->getName()));


        //   var_dump($customer);die;
        if ($form->isValid()) {
            //     $customer->setPlainText($request->getParameter($form->getPassword()));
            $customer = $form->save();
            $customer->setReferrerId($this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
            $customer->setRegistrationTypeId('2');
            $customer->setPlainText($plainPws);
            $customer->setBlock('0');
            $customer->save();
            if ($customer) {

            }
            $this->getUser()->setAttribute('customer_id', $customer->getId(), 'usersignup');
            $this->getUser()->setAttribute('product_id', $product, 'usersignup');
            echo "redirecting";
            $this->redirect('@customer_registration_step2?customer_id=' . $customer->getId() . '&product_id=' . $product);
            //$this->redirect(sfConfig::get('app_epay_relay_script_url').$this->getController()->genUrl('@signup_step2?customer_id='.$customer->getId().'&product_id='.$product, true));
        }
    }

    public function executeSetProductDetails(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->isAuthenticated());
        $this->updateNews = NewupdatePeer::doSelect(new Criteria());
        $this->browser = new Browser();
        $this->form = new PaymentForm();
        $this->target = $this->getTargetUrl();
        unset(
                $this->form['cardno'],
                $this->form['expmonth'],
                $this->form['expyear'],
                $this->form['cvc'],
                $this->form['cardtype']
        );


        $product_id = $request->getParameter('product_id');
        $customer_id = $request->getParameter('customer_id');

        if ($product_id == '' || $customer_id == '') {
            $this->forward404('Product id not found in session');
        }

        $order = new CustomerOrder();
        $transaction = new Transaction();

        $order->setProductId($product_id);
        $order->setCustomerId($customer_id);
        $order->setExtraRefill($order->getProduct()->getInitialBalance());

        //$extra_refil_choices = ProductPeer::getRefillChoices();
        //$order->setExtraRefill($extra_refil_choices[0]);//minumum refill amount
        $order->setIsFirstOrder(1);

        $order->save();

        $customer = CustomerPeer::retrieveByPk($customer_id);
        $customer->setReferrerId($this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $customer->save();

        $transaction->setAgentCompanyId($customer->getReferrerId());


        $transaction->setAmount($order->getProduct()->getPrice() + $order->getProduct()->getRegistrationFee() + ($order->getProduct()->getRegistrationFee() * sfConfig::get('app_vat_percentage')));
        $transaction->setDescription('Registration');

        $transaction->setOrderId($order->getId());
        $transaction->setCustomerId($customer_id);



        //$transaction->setTransactionStatusId() // default value 1

        $transaction->save();
        $this->order = $order;
        $this->forward404Unless($this->order);

        $this->order_id = $order->getId();
        $this->amount = $transaction->getAmount();

        if ($request->getParameter('balance_error') == '1') {
            $this->getUser()->setFlash('decline', 'You Do not have enough Balance, Please Recharge');
            $this->getUser()->setFlash('error_message', 'You Do not have enough Balance, Please Recharge');
            $this->balance_error = $request->getParameter('balance_error');
        } else {

            $this->balance_error = "";
        }
    }

    public function executeCompleteCustomerRegistration(sfWebRequest $request) {



        $this->forward404Unless($this->getUser()->isAuthenticated());
        $this->updateNews = NewupdatePeer::doSelect(new Criteria());
        $this->browser = new Browser();


        //debug form value
        $order_id = $request->getParameter('orderid');
        //$request->getParameter('amount');
        $order_amount = ((double) $request->getParameter('amount'));
//        echo $order_id;
//        echo '<br />';
//        echo $order_amount;
//die;
        $this->forward404Unless($order_id || $order_amount);


        $order = CustomerOrderPeer::retrieveByPK($order_id);

        //if order is already completed > 404
        $this->forward404Unless($order->getOrderStatusId() != sfConfig::get('app_status_completed'));
        $this->forward404Unless($order);

        //get agent
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        //echo $agent->getId();
        //getting agent commission
        $cc = new Criteria();
        $cc->add(AgentCommissionPackagePeer::ID, $agent->getAgentCommissionPackageId());
        $commission_package = AgentCommissionPackagePeer::doSelectOne($cc);

        //get transaction
        $c = new Criteria;
        $c->add(TransactionPeer::ORDER_ID, $order_id);
        $transaction = TransactionPeer::doSelectOne($c);

        $order->setOrderStatusId(sfConfig::get('app_status_completed', 3)); //completed
        $order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_completed', 3)); //completed
        $transaction->setTransactionStatusId(sfConfig::get('app_status_completed', 3)); //completed

        if ($transaction->getAmount() > $order_amount) {

            $order->setOrderStatusId(sfConfig::get('app_status_error', 5)); //error in amount
            $transaction->setTransactionStatusId(sfConfig::get('app_status_error', 5)); //error in amount
            $order->getCustomer()->setCustomerStatusId(sfConfig::get('app_status_error', 5)); //error in amount
        } else if ($transaction->getAmount() < $order_amount) {
            $transaction->setAmount($order_amount);
        }

        $is_transaction_completed = $transaction->getTransactionStatusId() == sfConfig::get('app_status_completed', 3);
        $agentcomession = Null;
        // if transaction ok
        if ($is_transaction_completed) {
            $product_price = $order->getProduct()->getPrice() + $order->getProduct()->getRegistrationFee();
            $product_price_vat = sfConfig::get('app_vat_percentage') * $order->getProduct()->getRegistrationFee();
            $order->setAgentCommissionPackageId($order->getCustomer()->getAgentCompany()->getAgentCommissionPackageId());
            ///////////////////////////commision calculation by agent product ///////////////////////////////////////
            $cp = new Criteria;
            $cp->add(AgentProductPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
            $cp->add(AgentProductPeer::PRODUCT_ID, $order->getProductId());
            $agentproductcount = AgentProductPeer::doCount($cp);

            if ($agentproductcount > 0) {
                $p = new Criteria;
                $p->add(AgentProductPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
                $p->add(AgentProductPeer::PRODUCT_ID, $order->getProductId());

                $agentproductcomesion = AgentProductPeer::doSelectOne($p);
                $agentcomession = $agentproductcomesion->getRegShareEnable();
            }

            ////////   commission setting  through  agent commision//////////////////////

            if ($agentcomession) {


                if ($order->getIsFirstOrder()) {
                    if ($agentproductcomesion->getIsRegShareValuePc()) {
                        $transaction->setCommissionAmount(($transaction->getAmount() / 100) * $agentproductcomesion->getRegShareValue());
                    } else {

                        $transaction->setCommissionAmount($agentproductcomesion->getRegShareValue());
                    }
                } else {
                    if ($agentproductcomesion->getIsExtraPaymentsShareValuePc()) {
                        $transaction->setAgentCommission(($transaction->getAmount() / 100) * $agentproductcomesion->getExtraPaymentsShareValue());
                    } else {
                        $transaction->setAgentCommission($agentproductcomesion->getExtraPaymentsShareValue());
                    }
                }
            } else {

                if ($order->getIsFirstOrder()) {
                    if ($commission_package->getIsRegShareValuePc()) {
                        $transaction->setCommissionAmount(($transaction->getAmount() / 100) * $commission_package->getRegShareValue());
                    } else {

                        $transaction->setCommissionAmount($commission_package->getRegShareValue());
                    }
                } else {
                    if ($commission_package->getIsExtraPaymentsShareValuePc()) {
                        $transaction->setAgentCommission(($transaction->getAmount() / 100) * $commission_package->getExtraPaymentsShareValue());
                    } else {
                        $transaction->setAgentCommission($commission_package->getExtraPaymentsShareValue());
                    }
                }
            }


            $transaction->save();

            if ($agent->getIsPrepaid() == true) {

                if ($agent->getBalance() < ($transaction->getAmount() - $transaction->getCommissionAmount())) {
                    $this->redirect('affiliate/setProductDetails?product_id=' . $order->getProductId() . '&customer_id=' . $transaction->getCustomerId() . '&balance_error=1');
                } else {
                    $agent->setBalance($agent->getBalance() - ($transaction->getAmount() - $transaction->getCommissionAmount()));
                    $agent->save();
                    ////////////////////////////////////
                    $remainingbalance = $agent->getBalance();
                    $amount = $transaction->getAmount() - $transaction->getCommissionAmount();
                    $amount = -$amount;
                    $aph = new AgentPaymentHistory();
                    $aph->setAgentId($this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
                    $aph->setCustomerId($transaction->getCustomerId());
                    $aph->setExpeneseType(1);
                    $aph->setAmount($amount);
                    $aph->setRemainingBalance($remainingbalance);
                    $aph->save();

                    ////////////////////////////////////////////
                }
            }
        }
        $order->save();

        if ($is_transaction_completed) {

            $customer_product = new CustomerProduct();

            $customer_product->setCustomer($order->getCustomer());
            $customer_product->setProduct($order->getProduct());

            $customer_product->save();

            //register to fonet
            $this->customer = $order->getCustomer();
//	  	Fonet::registerFonet($this->customer);
//	  	Fonet::recharge($this->customer, $order->getExtraRefill());
            $uniqueid = $request->getParameter('uniqueid');
            $uc = new Criteria();
            $uc->add(UniqueIdsPeer::REGISTRATION_TYPE_ID, 2);
            $uc->addAnd(UniqueIdsPeer::SIM_TYPE_ID, $this->customer->getSimTypeId());
            $uc->addAnd(UniqueIdsPeer::STATUS, 0);
            $uc->addAnd(UniqueIdsPeer::UNIQUE_NUMBER, $uniqueid);
            $availableUniqueCount = UniqueIdsPeer::doCount($uc);
            $availableUniqueId = UniqueIdsPeer::doSelectOne($uc);


            if ($availableUniqueCount == 0) {
                // Unique Ids are not avaialable.  send email to the support.
                emailLib::sendUniqueIdsIssueAgent($uniqueid, $this->customer);
            } else {
                $availableUniqueId->setStatus(1);
                $availableUniqueId->setAssignedAt(date('Y-m-d H:i:s'));
                $availableUniqueId->save();
            }
            $this->customer->setUniqueid(str_replace(' ', '', $uniqueid));
            $this->customer->save();

            $cc = new Criteria();
            $cc->add(EnableCountryPeer::ID, $this->customer->getCountryId());
            $country = EnableCountryPeer::doSelectOne($cc);

            $mobile = $country->getCallingCode() . $this->customer->getMobileNumber();

            $getFirstnumberofMobile = substr($this->customer->getMobileNumber(), 0, 1);     // bcdef
            if ($getFirstnumberofMobile == 0) {
                $TelintaMobile = substr($this->customer->getMobileNumber(), 1);
                $TelintaMobile = sfConfig::get('app_country_code') . $TelintaMobile;
            } else {
                $TelintaMobile = sfConfig::get('app_country_code') . $this->customer->getMobileNumber();
            }

            $callbacklog = new CallbackLog();
            $callbacklog->setMobileNumber($TelintaMobile);
            $callbacklog->setuniqueId($this->customer->getUniqueid());
            $callbacklog->setCheckStatus(3);
            $callbacklog->save();

            //Section For Telinta Add Cusomter

            Telienta::ResgiterCustomer($this->customer, $order->getExtraRefill());
            Telienta::createAAccount($TelintaMobile, $this->customer);
            Telienta::createCBAccount($TelintaMobile, $this->customer);
            $this->setPreferredCulture($this->customer);
            emailLib::sendCustomerRegistrationViaAgentEmail($this->customer, $order);
            $this->updatePreferredCulture();
//            $zeroCallOutSMSObject = new ZeroCallOutSMS();
//            $zeroCallOutSMSObject->toCustomerAfterReg($customer_product->getProductId(), $this->customer);
            

            $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('Customer ') . $this->customer->getMobileNumber() . $this->getContext()->getI18N()->__(' is registered successfully'));
            $this->redirect('affiliate/receipts');
        }// die('here');
        
    }

    public function executeFaq(sfWebRequest $request) {

        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham
        //----Query Get FAQs
        //get Agent
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        $country_id = $agent->getCountryId();

        //-----------------------------------
        //        $countrylng = new Criteria();
        //        $countrylng->add(EnableCountryPeer::ID, $country_id);
        //        $countrylng = EnableCountryPeer::doSelectOne($countrylng);
        //        $countryRefill = $countrylng->getRefill();


        $Faqs = new Criteria();
        //$Faqs->add(FaqsPeer::COUNTRY_ID, $country_id);
        $Faqs->add(FaqsPeer::STATUS_ID, 1);
        $Faqs = FaqsPeer::doSelect($Faqs);

        $this->Faqs = $Faqs;
        //-----------
        $this->updateNews = NewupdatePeer::doSelect(new Criteria());
        $this->browser = new Browser();
    }

    public function executeUserguide(sfWebRequest $request) {

        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham
        //----Query Get UserGuide
        //get Agent
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        $country_id = $agent->getCountryId();

        //-----------------------------------
        //        $countrylng = new Criteria();
        //        $countrylng->add(EnableCountryPeer::ID, $country_id);
        //        $countrylng = EnableCountryPeer::doSelectOne($countrylng);
        //        $countryRefill = $countrylng->getRefill();


        $Userguide = new Criteria();
        // $Userguide->add(UserguidePeer::COUNTRY_ID, $country_id);
        $Userguide->add(UserguidePeer::STATUS_ID, 1);
        $Userguide = UserguidePeer::doSelect($Userguide);

        $this->Userguide = $Userguide;
        //-----------

        $this->updateNews = NewupdatePeer::doSelect(new Criteria());
        $this->browser = new Browser();
    }

    public function executeSupportingHandset(sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham


        $this->updateNews = NewupdatePeer::doSelect(new Criteria());
        $this->browser = new Browser();
    }

    public function executeNonSupportingHandset(sfWebRequest $request) {

        
        $ch = new Criteria();
        $ch->add(HandsetsPeer::SUPPORTED,0);
        $nonsupported = HandsetsPeer::doSelect($ch);
        $this->handsets = $nonsupported;
        $this->browser = new Browser();
    }

    public function executeAccountRefill(sfWebRequest $request) {

        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham

        $this->target = $this->getTargetUrl().'affiliate/';
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        $this->agent = $agent;
        $this->forward404Unless($agent);


        if (isset($_REQUEST['error'])) {


            $agent_order_id = $request->getParameter('orderid');

            $aoc = new Criteria();
            $aoc->add(AgentOrderPeer::AGENT_ORDER_ID, $agent_order_id);
            $agent_order = AgentOrderPeer::doSelectOne($aoc);

            $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('Your Credit Card Information was not approved'));
            $this->agent_order_id = $agent_order_id;
            $this->agent_order = $agent_order;
        } else {


            $c = new Criteria();
            $agent_order = new AgentOrder();
            $agent_order->setAgentCompanyId($agent->getId());
            $agent_order->setStatus('1');
            $agent_order->save();

            $agent_order->setAgentOrderId('a0' . $agent_order->getId());
            $agent_order->save();

            $this->agent_order = $agent_order;
        }
    }

    public function executeThankyou(sfWebRequest $request) {

        $Parameters = $request->getURI();

        $email2 = new DibsCall();
        $email2->setCallurl($Parameters);
        $email2->save();

        $order_id = $request->getParameter('orderid');
        $amount = $request->getParameter('amount');

        if ($order_id and $amount) {
            $c = new Criteria();
            $c->add(AgentOrderPeer::AGENT_ORDER_ID, $order_id);
            $c->add(AgentOrderPeer::STATUS, 1);
            $agent_order = AgentOrderPeer::doSelectOne($c);

            $agent_order->setAmount($amount);
            $agent_order->setStatus(3);
            $agent_order->save();

            $agent = AgentCompanyPeer::retrieveByPK($agent_order->getAgentCompanyId());
            $agent->setBalance($agent->getBalance() + ($amount));
            $agent->save();
            $this->agent = $agent;

            $amount = $amount;
            $remainingbalance = $agent->getBalance();
            $aph = new AgentPaymentHistory();
            $aph->setAgentId($agent_order->getAgentCompanyId());
            $aph->setExpeneseType(3);
            $aph->setAmount($amount);
            $aph->setRemainingBalance($remainingbalance);
            $aph->save();

            $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('Your Credit Card recharge of %1%%2% is approved ', array("%1%" => $amount, "%2%" => sfConfig::get('app_currency_code'))));
            emailLib::sendAgentRefilEmail($this->agent, $agent_order);
            $this->redirect('affiliate/agentOrder');
        }
        $this->redirect('affiliate/agentOrder');
    }

    public function executeAgentOrder(sfRequest $request) {

        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 01/24/11 - Ahtsham


        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        $this->forward404Unless($agent);

        $this->agent = $agent;
        $c = new Criteria();
        $c->add(AgentOrderPeer::AGENT_COMPANY_ID, $agent->getId());
        $c->add(AgentOrderPeer::STATUS, 3);
        $this->agentOrders = AgentOrderPeer::doSelect($c);
    }

    public function executePrintAgentReceipt(sfWebrequest $request) {
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        $this->forward404Unless($agent);

        $aoid = $request->getParameter('aoid');
        $agent_order = AgentOrderPeer::retrieveByPk($aoid);
        $this->agent = $agent;
        $this->aoid = $aoid;
        $this->agent_order = $agent_order;

        $this->setLayout(false);
    }

    public function executePaymentHistory(sfWebrequest $request) {

        $ca = new Criteria();
        $ca->add(AgentPaymentHistoryPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentPaymentHistoryPeer::doSelect($ca);
        //$this->forward404Unless($agent);

        $this->agents = $agent;
    }

    public function executeGetmobilemodel(sfWebRequest $request) {

        if ($request->isXmlHttpRequest()) {
            // echo $request->getParameter('device_id').'pakistan';
            $device_id = (int) $request->getParameter('device_id');
            if ($device_id) {
                // Get The Mobile Model
                $Mobilemodel = new Criteria();
                $Mobilemodel->add(DevicePeer::MANUFACTURER_ID, $device_id);
                $mModel = DevicePeer::doSelect($Mobilemodel);
                //echo $mModel->getName();
                $output = '<option value=""></option>';
                foreach ($mModel as $mModels) {
                    echo $mModels->getName();
                    $output .= '<option value="' . $mModels->getId() . '">' . $mModels->getName() . '</option>';
                }
                return $this->renderText($output);
            }
        }
    }

    public function executeValidateUniqueId(sfWebRequest $request) {

        $uniqueId = $request->getParameter('uniqueid');
        $order = CustomerOrderPeer::retrieveByPK($request->getParameter('orderid'));
        $uc = new Criteria();
        $uc->add(UniqueIdsPeer::REGISTRATION_TYPE_ID, 2);
        $uc->addAnd(UniqueIdsPeer::SIM_TYPE_ID, $order->getCustomer()->getSimTypeId());
        $uc->addAnd(UniqueIdsPeer::STATUS, 0);
        $uc->addAnd(UniqueIdsPeer::UNIQUE_NUMBER, $uniqueId);
        $availableUniqueCount = UniqueIdsPeer::doCount($uc);
        if ($availableUniqueCount == 1) {
            echo "true";
        } else {
            echo "false";
        }
//echo $order->getCustomer()->getSimTypeId();die;
        return sfView::NONE;
    }

    public function executeChangeCulture(sfWebRequest $request) {
        // var_dump($request->getParameter('new'));
        $this->getUser()->setCulture($request->getParameter('new'));
        //$this->redirect('affiliate/report?show_summary=1');
        $pathArray = $request->getPathInfoArray();
        $this->redirect($pathArray['HTTP_REFERER']);
    }

    public function executeChangenumberservice(sfWebRequest $request) {

        changeLanguageCulture::languageCulture($request, $this);
        $this->browser = new Browser();
        $this->targetUrl = $this->getTargetUrl();
    }

    public function executeChangenumber(sfWebRequest $request) {
        changeLanguageCulture::languageCulture($request, $this);
        $this->targetUrl = $this->getTargetUrl();

        $mobile = "";
        $existingNumber = $request->getParameter('existingNumber');
        $this->newNumber = $request->getParameter('newNumber');
        $this->countrycode = $request->getParameter('countrycode');
        if (isset($_REQUEST['existingNumber']) && $_REQUEST['existingNumber'] != "") {
            $mobile = $_REQUEST['existingNumber'];
            $product = $_REQUEST['product'];
            $cc = new Criteria();
            $cc->add(CustomerPeer::MOBILE_NUMBER, $mobile);
            $cc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);

            $c = new Criteria();
            $c->add(ProductPeer::ID, $product);
            $product = ProductPeer::doSelectOne($c);

            if (CustomerPeer::doCount($cc) == 0) {
                $this->getUser()->setFlash('message', 'Customer Does not exist');
                $this->redirect('affiliate/refill');
            }

            $customer = CustomerPeer::doSelectOne($cc);
            if ($customer) {
                $this->customer = $customer;
                $this->product = $product;
            } else {
                $this->getUser()->setFlash('message', 'Customer Does not exist');
                $this->redirect('affiliate/refill');
            }
        }
    }

    public function executeNumberProcess(sfWebRequest $request) {

        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 03/09/11 - Ahtsham
        changeLanguageCulture::languageCulture($request, $this);

        $this->browser = new Browser();
        $this->form = new AccountRefillAgent();

        $this->error_msg = "";
        $this->error_mobile_number = "";
        $validated = false;

        //get Agent
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
//var_dump($agent);
        //get Agent commission package
        $cpc = new Criteria();
        $cpc->add(AgentCommissionPackagePeer::ID, $agent->getAgentCommissionPackageId());
        $commission_package = AgentCommissionPackagePeer::doSelectOne($cpc);

        if ($request->getParameter('balance_error')) {
            $this->balance_error = $request->getParameter('balance_error');
        } else {
            $this->balance_error = 0;
        }

        if ($request->isMethod('post')) {
            $mobile_number = $request->getParameter('mobile_number');
            $productid = $request->getParameter('productid');
            $extra_refill = $request->getParameter('extra_refill');
            $newnumber = $request->getParameter('newnumber');
            $countrycode = $request->getParameter('countrycode');

            $is_recharged = true;
            $transaction = new Transaction();
            $order = new CustomerOrder();
            $customer = NULL;
            $cc = new Criteria();
            $cc->add(CustomerPeer::MOBILE_NUMBER, $mobile_number);
            $cc->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);

            $customer = CustomerPeer::doSelectOne($cc);

            if ($customer and $mobile_number != "") {
                $validated = true;
            } else {
                $validated = false;
                $is_recharged = false;
                $this->error_mobile_number = 'invalid mobile number';
                return;
            }

            if ($validated) {

///////////////////////////////change number process///////////////////////////////////////////////////////////////////
                $order->setCustomerId($customer->getId());
                $order->setProductId($productid);
                $order->setQuantity(1);
                $order->setExtraRefill($extra_refill);
                $order->setOrderStatusId(sfConfig::get('app_status_new'));

                $order->save();

                //create transaction
                $transaction->setOrderId($order->getId());
                $transaction->setCustomerId($customer->getId());
                $transaction->setAmount($extra_refill);
                //get agent nam
                $transaction->setDescription('Fee for change number (' . $agent->getName() . ')');
                $transaction->setAgentCompanyId($agent->getId());
                //assign commission to transaction;
                /////////////////////////////////////////////////////////////////////////////////////////////////
                $order->setAgentCommissionPackageId($agent->getAgentCommissionPackageId());
                ///////////////////////////commision calculation by agent product ///////////////////////////////////////
                $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession');
                $cp = new Criteria;
                $cp->add(AgentProductPeer::AGENT_ID, $agent_company_id);
                $cp->add(AgentProductPeer::PRODUCT_ID, $order->getProductId());
                $agentproductcount = AgentProductPeer::doCount($cp);
                if ($agentproductcount > 0) {
                    $p = new Criteria;
                    $p->add(AgentProductPeer::AGENT_ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
                    $p->add(AgentProductPeer::PRODUCT_ID, $order->getProductId());

                    $agentproductcomesion = AgentProductPeer::doSelectOne($p);
                    $agentcomession = $agentproductcomesion->getExtraPaymentsShareEnable();
                }

                ////////   commission setting  through  agent commision//////////////////////

                if (isset($agentcomession) && $agentcomession != "") {

                    if ($agentproductcomesion->getIsExtraPaymentsShareValuePc()) {
                        $transaction->setCommissionAmount(($transaction->getAmount() / 100) * $agentproductcomesion->getExtraPaymentsShareValue());
                    } else {
                        $transaction->setCommissionAmount($agentproductcomesion->getExtraPaymentsShareValue());
                    }
                } else {
                    if ($commission_package->getIsExtraPaymentsShareValuePc()) {
                        $transaction->setCommissionAmount(($transaction->getAmount() / 100) * $commission_package->getExtraPaymentsShareValue());
                    } else {
                        $transaction->setCommissionAmount($commission_package->getExtraPaymentsShareValue());
                    }
                }
                //calculated amount for agent commission
                if ($agent->getIsPrepaid() == true) {
                    if ($agent->getBalance() < ($transaction->getAmount() - $transaction->getCommissionAmount())) {
                        $is_recharged = false;
                        $balance_error = 1;
                    }
                }
                // var_dump($customer);exit;

                if ($is_recharged) {

                    $transaction->save();
                    if ($customer) {
                        $getFirstnumberofMobile = substr($newnumber, 0, 1);
                        if ($getFirstnumberofMobile == 0) {
                            $newMobileNo = substr($newnumber, 1);
                            $newMobileNo = $countrycode . $newMobileNo;
                        } else {
                            $newMobileNo = $countrycode . $newnumber;
                        }

                        $customerids = $customer->getId();
                        $uniqueId = $customer->getUniqueid();
                        $customer->setMobileNumber($newnumber);
                        $customer->save();

                        $changenumberdetail = new ChangeNumberDetail();
                        $changenumberdetail->setOldNumber($mobile_number);
                        $changenumberdetail->setNewNumber($newnumber);
                        $changenumberdetail->setCustomerId($customerids);
                        $changenumberdetail->setStatus(3);
                        $changenumberdetail->save();

                        $un = new Criteria();
                        $un->add(CallbackLogPeer::UNIQUEID, $uniqueId);
                        $un->addDescendingOrderByColumn(CallbackLogPeer::CREATED);
                        $activeNumber = CallbackLogPeer::doSelectOne($un);

                        // As each customer have a single account search the previous account and terminate it.
                        $cp = new Criteria;
                        $cp->add(TelintaAccountsPeer::ACCOUNT_TITLE, 'a' . $activeNumber->getMobileNumber());
                        $cp->addAnd(TelintaAccountsPeer::STATUS, 3);

                        if (TelintaAccountsPeer::doCount($cp) > 0) {
                            $telintaAccount = TelintaAccountsPeer::doSelectOne($cp);
                            Telienta::terminateAccount($telintaAccount);
                        }

                        Telienta::createAAccount($newMobileNo, $customer);

                        $cb = new Criteria;
                        $cb->add(TelintaAccountsPeer::ACCOUNT_TITLE, 'cb' . $activeNumber->getMobileNumber());
                        $cb->addAnd(TelintaAccountsPeer::STATUS, 3);

                        if (TelintaAccountsPeer::doCount($cb) > 0) {
                            $telintaAccountsCB = TelintaAccountsPeer::doSelectOne($cb);
                            Telienta::terminateAccount($telintaAccountsCB);
                        }
                        //Telienta::createCBAccount($newMobileNo, $customer);

                        $getvoipInfo = new Criteria();
                        $getvoipInfo->add(SeVoipNumberPeer::CUSTOMER_ID, $customerids);
                        $getvoipInfo->addAnd(SeVoipNumberPeer::IS_ASSIGNED, 1);
                        $getvoipInfos = SeVoipNumberPeer::doSelectOne($getvoipInfo); //->getId();
                        if (isset($getvoipInfos)) {
                            $voipnumbers = $getvoipInfos->getNumber();
                            $voipnumbers = substr($voipnumbers, 2);

                            $tc = new Criteria();
                            $tc->add(TelintaAccountsPeer::ACCOUNT_TITLE, $voipnumbers);
                            $tc->add(TelintaAccountsPeer::STATUS, 3);
                            if (TelintaAccountsPeer::doCount($tc) > 0) {
                                $telintaAccountR = TelintaAccountsPeer::doSelectOne($tc);
                                Telienta::terminateAccount($telintaAccountR);
                            }
                            Telienta::createReseNumberAccount($voipnumbers, $customer, $newMobileNo);
                        } else {

                        }
                    }

                    $callbacklog = new CallbackLog();
                    $callbacklog->setMobileNumber($newMobileNo);
                    $callbacklog->setuniqueId($uniqueId);
                    $callbacklog->setcallingCode($countrycode);
                    $callbacklog->save();

                    $mobile_number = substr($mobile_number, 1);
                    $number = $countrycode . $mobile_number;
                    $sms = SmsTextPeer::retrieveByPK(1);
                    $sms_text = $sms->getMessageText();
                    $sms_text = str_replace(array("(oldnumber)", "(newnumber)"), array($mobile_number, $newnumber), $sms_text);

                    //ROUTED_SMS::Send($number, $sms_text,"Zapna");
                    //Send SMS ----
                    $number = $newMobileNo;
                    //ROUTED_SMS::Send($number, $sms_text,"Zapna");
                }
//exit;
                if ($agent->getIsPrepaid() == true) {
                    $agent->setBalance($agent->getBalance() - ($transaction->getAmount() - $transaction->getCommissionAmount()));
                    $agent->save();
                    $remainingbalance = $agent->getBalance();
                    $amount = $transaction->getAmount() - $transaction->getCommissionAmount();
                    $amount = -$amount;
                    $aph = new AgentPaymentHistory();
                    $aph->setAgentId($this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
                    $aph->setCustomerId($transaction->getCustomerId());
                    $aph->setExpeneseType(6);
                    $aph->setAmount($amount);
                    $aph->setRemainingBalance($remainingbalance);
                    $aph->save();
                }
                //set status
                $order->setOrderStatusId(sfConfig::get('app_status_completed'));
                $transaction->setTransactionStatusId(sfConfig::get('app_status_completed'));
                $order->save();
                $transaction->save();
                $this->customer = $order->getCustomer();
                $this->setPreferredCulture($this->customer);
                emailLib::sendChangeNumberEmail($this->customer, $order);
                $this->updatePreferredCulture();
                $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('%1% Mobile Number is changed successfully  with %2% %3%.', array("%1%" => $customer->getMobileNumber(), "%2%" => $transaction->getAmount(), "%3%" => sfConfig::get('app_currency_code'))));

                $this->redirect('affiliate/receipts');
            } else {

                $this->balance_error = 1;
                $this->getUser()->setFlash('error', 'You do not have enough balance, please recharge');
            } //end else
        } else {

            $this->balance_error = 1;
            $is_recharged = false;
            $this->error_mobile_number = 'invalid mobile number';
            $this->getUser()->setFlash('error', 'invalid mobile number');
        }
    }

    public function executeAgentRefil(sfWebRequest $request) { 
        
        $order_id = $request->getParameter('item_number');
        $item_amount = $request->getParameter('amount');

        $return_url = $this->getTargetUrl() . 'affiliate/accountRefill';
        $cancel_url = $this->getTargetUrl() . 'affiliate/thankyou/?accept=cancel';
        
        $callbackparameters = $order_id.'-'.$item_amount;
        $notify_url = sfConfig::get('app_customer_url') . 'pScripts/agentRefillThankyou?p='.$callbackparameters;

        $c = new Criteria;
        $c->add(AgentOrderPeer::AGENT_ORDER_ID, $order_id);
        $c->add(AgentOrderPeer::STATUS, 1);
        $agent_order = AgentOrderPeer::doSelectOne($c);

        $agent_order->setAmount($item_amount);
        $agent_order->save();

        $querystring = '';
        if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {

            $item_name = "Agent Refill";

            //loop for posted values and append to querystring
            foreach ($_POST as $key => $value) {
                $value = urlencode(stripslashes($value));
                $querystring .= "$key=$value&";
            }

            $querystring .= "item_name=" . urlencode($item_name) . "&";
            $querystring .= "return=" . urldecode($return_url) . "&";
            $querystring .= "cancel_return=" . urldecode($cancel_url) . "&";
            $querystring .= "notify_url=" . urldecode($notify_url);

            //$environment = "sandbox";
//        echo $querystring;
            if ($order_id && $item_amount) {
                Payment::SendPayment($querystring);
            } else {
                echo 'error';
            }
            return sfView::NONE;
            //exit();
        }
    }

    public function executeOverview(sfWebRequest $request) {

        $this->forward404Unless($this->getUser()->isAuthenticated());
        $nc = new Criteria();
        $nc->addDescendingOrderByColumn(NewupdatePeer::STARTING_DATE);
        $this->updateNews = NewupdatePeer::doSelect($nc);
        //verify if agent is already logged in
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        $this->forward404Unless($agent);
        $this->agent = $agent;

        $startdate = $request->getParameter('startdate');
        $enddate = $request->getParameter('enddate');
        if ($startdate != '') {
            $startdate = date('Y-m-d 00:00:00', strtotime($startdate));
            $this->startdate = date('Y-m-d', strtotime($startdate));
        }else{
            $startdate = date('Y-m-d 00:00:00', strtotime($this->agent->getCreatedAt()));
            $this->startdate = $startdate;
        }
        if ($enddate != '') {
            $enddate = date('Y-m-d 23:59:59', strtotime($enddate));
            $this->enddate = date('Y-m-d', strtotime($enddate));
        }else{
            $enddate = date('Y-m-d 23:59:59');
        }
        //get All customer registrations from customer table
        try {
            $c = new Criteria();
            $c->add(CustomerPeer::REFERRER_ID, $agent_company_id);
            $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            $c->add(CustomerPeer::REGISTRATION_TYPE_ID, 4, Criteria::NOT_EQUAL);
            $c->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
            $customers = CustomerPeer::doSelect($c);
            $registration_sum = 0.00;
            $registration_commission = 0.00;
            $registrations = array();
            $comregistrations = array();
            $i = 1;
            foreach ($customers as $customer) {
                $tc = new Criteria();
                //echo $customer->getId();
                $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
                $tc->add(TransactionPeer::TRANSACTION_STATUS_ID, 3);
                $tc->add(TransactionPeer::DESCRIPTION, 'Registration');
                if ($startdate != "" && $enddate != "") {
                    $tc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                    $tc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
                }
                if (TransactionPeer::doSelectOne($tc)) {
                    $registrations[$i] = TransactionPeer::doSelectOne($tc);
                }
                $i = $i + 1;
            }

            if (count($registrations) >= 1) {

                foreach ($registrations as $registration) {
                    $registration_sum = $registration_sum + $registration->getAmount();
                    if ($registration != NULL) {
                        $coc = new Criteria();
                        $coc->add(CustomerOrderPeer::ID, $registration->getOrderId());
                        $customer_order = CustomerOrderPeer::doSelectOne($coc);
                        $registration_commission = $registration_commission + ($registration->getCommissionAmount());
                    }
                }
            }
            $this->registrations = $registrations;
            $this->registration_revenue = $registration_sum;
            $this->registration_commission = $registration_commission;
            $cc = new Criteria();
            $cc->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
            $cc->addAnd(TransactionPeer::DESCRIPTION, 'Refill');
            $cc->addAnd(TransactionPeer::TRANSACTION_STATUS_ID, 3);
            if ($startdate != "" && $enddate != "") {
                $cc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                $cc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
            }
            $cc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
            $refills = TransactionPeer::doSelect($cc);
            $refill_sum = 0.00;
            $refill_com = 0.00;
            foreach ($refills as $refill) {
                $refill_sum = $refill_sum + $refill->getAmount();
                $refill_com = $refill_com + $refill->getCommissionAmount();
            }
            $this->refills = $refills;
            $this->refill_revenue = $refill_sum;
            $this->refill_com = $refill_com;
            $efc = new Criteria();
            $efc->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
            $efc->add(TransactionPeer::TRANSACTION_STATUS_ID, 3);
            if ($startdate != "" && $enddate != "") {
                $efc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                $efc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
            }
            $efc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
            $ef = TransactionPeer::doSelect($efc);
            $ef_sum = 0.00;
            $ef_com = 0.00;
            foreach ($ef as $efo) {
                $description = substr($efo->getDescription(), 0, 26);
                $stringfinds = 'Refill via agent';
                if (strstr($efo->getDescription(), $stringfinds)) {
                    //if($description== 'LandNCall AB Refill via agent ')
                    $ef_sum = $ef_sum + $efo->getAmount();
                    $ef_com = $ef_com + $efo->getCommissionAmount();
                }
            }
            $this->ef = $ef;
            $this->ef_sum = $ef_sum;
            $this->ef_com = $ef_com;
            /////////// SMS Registrations
            $cs = new Criteria();
            $cs->add(CustomerPeer::REFERRER_ID, $agent_company_id);
            $cs->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            $cs->add(CustomerPeer::REGISTRATION_TYPE_ID, 4);
            $cs->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
            $sms_customers = CustomerPeer::doSelect($cs);
            $sms_registrations = array();
            $sms_registration_earnings = 0.0;
            $sms_commission_earnings = 0.0;
            $i = 1;
            foreach ($sms_customers as $sms_customer) {
                $tc = new Criteria();
                $tc->add(TransactionPeer::CUSTOMER_ID, $sms_customer->getId());
                $tc->add(TransactionPeer::TRANSACTION_STATUS_ID, 3);
                $tc->add(TransactionPeer::DESCRIPTION, 'Registration');
                if ($startdate != "" && $enddate != "") {
                    $tc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                    $tc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
                }
                $sms_registrations[$i] = TransactionPeer::doSelectOne($tc);
                if (count($sms_registrations) >= 1) {
                    $sms_registration_earnings = $sms_registration_earnings + $sms_registrations[$i]->getAmount();
                    $sms_commission_earnings = $sms_commission_earnings + $sms_registrations[$i]->getCommissionAmount();
                }
                $i = $i + 1;
            }
            $this->sms_registrations = $sms_registrations;
            $this->sms_registration_earnings = $sms_registration_earnings;
            $this->sms_commission_earnings = $sms_commission_earnings;
            ////////// End SMS registrations
            $this->sf_request = $request;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function executePrintOverview(sfWebRequest $request) {

        $this->forward404Unless($this->getUser()->isAuthenticated());
        $nc = new Criteria();
        $nc->addDescendingOrderByColumn(NewupdatePeer::STARTING_DATE);
        $this->updateNews = NewupdatePeer::doSelect($nc);
        //verify if agent is already logged in
        $ca = new Criteria();
        $ca->add(AgentCompanyPeer::ID, $agent_company_id = $this->getUser()->getAttribute('agent_company_id', '', 'agentsession'));
        $agent = AgentCompanyPeer::doSelectOne($ca);
        $this->forward404Unless($agent);
        $this->agent = $agent;

        $startdate = $request->getParameter('startdate');
        $enddate = $request->getParameter('enddate');
        if ($startdate != '') {
            $startdate = date('Y-m-d 00:00:00', strtotime($startdate));
            $this->startdate = date('Y-m-d', strtotime($startdate));
        }
        if ($enddate != '') {
            $enddate = date('Y-m-d 23:59:59', strtotime($enddate));
            $this->enddate = date('Y-m-d', strtotime($enddate));
        }
        //get All customer registrations from customer table
        try {
            $c = new Criteria();
            $c->add(CustomerPeer::REFERRER_ID, $agent_company_id);
            $c->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            $c->add(CustomerPeer::REGISTRATION_TYPE_ID, 4, Criteria::NOT_EQUAL);
            $c->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
            $customers = CustomerPeer::doSelect($c);
            $registration_sum = 0.00;
            $registration_commission = 0.00;
            $registrations = array();
            $comregistrations = array();
            $i = 1;
            foreach ($customers as $customer) {
                $tc = new Criteria();
                //echo $customer->getId();
                $tc->add(TransactionPeer::CUSTOMER_ID, $customer->getId());
                $tc->add(TransactionPeer::TRANSACTION_STATUS_ID, 3);
                $tc->add(TransactionPeer::DESCRIPTION, 'Registration');
                if ($startdate != "" && $enddate != "") {
                    $tc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                    $tc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
                }
                if (TransactionPeer::doSelectOne($tc)) {
                    $registrations[$i] = TransactionPeer::doSelectOne($tc);
                }
                $i = $i + 1;
            }

            if (count($registrations) >= 1) {

                foreach ($registrations as $registration) {
                    $registration_sum = $registration_sum + $registration->getAmount();
                    if ($registration != NULL) {
                        $coc = new Criteria();
                        $coc->add(CustomerOrderPeer::ID, $registration->getOrderId());
                        $customer_order = CustomerOrderPeer::doSelectOne($coc);
                        $registration_commission = $registration_commission + ($registration->getCommissionAmount());
                    }
                }
            }
            $this->registrations = $registrations;
            $this->registration_revenue = $registration_sum;
            $this->registration_commission = $registration_commission;
            $cc = new Criteria();
            $cc->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
            $cc->addAnd(TransactionPeer::DESCRIPTION, 'Refill');
            $cc->addAnd(TransactionPeer::TRANSACTION_STATUS_ID, 3);
            if ($startdate != "" && $enddate != "") {
                $cc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                $cc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
            }
            $cc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
            $refills = TransactionPeer::doSelect($cc);
            $refill_sum = 0.00;
            $refill_com = 0.00;
            foreach ($refills as $refill) {
                $refill_sum = $refill_sum + $refill->getAmount();
                $refill_com = $refill_com + $refill->getCommissionAmount();
            }
            $this->refills = $refills;
            $this->refill_revenue = $refill_sum;
            $this->refill_com = $refill_com;
            $efc = new Criteria();
            $efc->add(TransactionPeer::AGENT_COMPANY_ID, $agent_company_id);
            $efc->add(TransactionPeer::TRANSACTION_STATUS_ID, 3);
            if ($startdate != "" && $enddate != "") {
                $efc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                $efc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
            }
            $efc->addDescendingOrderByColumn(TransactionPeer::CREATED_AT);
            $ef = TransactionPeer::doSelect($efc);
            $ef_sum = 0.00;
            $ef_com = 0.00;
            foreach ($ef as $efo) {
                $description = substr($efo->getDescription(), 0, 26);
                $stringfinds = 'Refill via agent';
                if (strstr($efo->getDescription(), $stringfinds)) {
                    //if($description== 'LandNCall AB Refill via agent ')
                    $ef_sum = $ef_sum + $efo->getAmount();
                    $ef_com = $ef_com + $efo->getCommissionAmount();
                }
            }
            $this->ef = $ef;
            $this->ef_sum = $ef_sum;
            $this->ef_com = $ef_com;
            /////////// SMS Registrations
            $cs = new Criteria();
            $cs->add(CustomerPeer::REFERRER_ID, $agent_company_id);
            $cs->add(CustomerPeer::CUSTOMER_STATUS_ID, 3);
            $cs->add(CustomerPeer::REGISTRATION_TYPE_ID, 4);
            $cs->addDescendingOrderByColumn(CustomerPeer::CREATED_AT);
            $sms_customers = CustomerPeer::doSelect($cs);
            $sms_registrations = array();
            $sms_registration_earnings = 0.0;
            $sms_commission_earnings = 0.0;
            $i = 1;
            foreach ($sms_customers as $sms_customer) {
                $tc = new Criteria();
                $tc->add(TransactionPeer::CUSTOMER_ID, $sms_customer->getId());
                $tc->add(TransactionPeer::TRANSACTION_STATUS_ID, 3);
                $tc->add(TransactionPeer::DESCRIPTION, 'Registration');
                if ($startdate != "" && $enddate != "") {
                    $tc->addAnd(TransactionPeer::CREATED_AT, $startdate, Criteria::GREATER_EQUAL);
                    $tc->addAnd(TransactionPeer::CREATED_AT, $enddate, Criteria::LESS_EQUAL);
                }
                $sms_registrations[$i] = TransactionPeer::doSelectOne($tc);
                if (count($sms_registrations) >= 1) {
                    $sms_registration_earnings = $sms_registration_earnings + $sms_registrations[$i]->getAmount();
                    $sms_commission_earnings = $sms_commission_earnings + $sms_registrations[$i]->getCommissionAmount();
                }
                $i = $i + 1;
            }
            $this->sms_registrations = $sms_registrations;
            $this->sms_registration_earnings = $sms_registration_earnings;
            $this->sms_commission_earnings = $sms_commission_earnings;
            ////////// End SMS registrations
            $this->sf_request = $request;
        } catch (Exception $e) {
            echo $e->getMessage();
        }

        $this->setLayout(false);
    }
    private function setPreferredCulture(Customer $customer) {
        $this->currentCulture = $this->getUser()->getCulture();
        $preferredLang = PreferredLanguagesPeer::retrieveByPK($customer->getPreferredLanguageId());
        $this->getUser()->setCulture($preferredLang->getLanguageCode());
    }

    private function updatePreferredCulture() {
        $this->getUser()->setCulture($this->currentCulture);
    }
}
