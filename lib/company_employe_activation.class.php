<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of company_employe_activation
 *
 * @author baran
 */
set_time_limit(10000000);

class CompanyEmployeActivation {

    //put your code here
    //put your code here
    private static $iParent = 85977;                //Company Resller ID on Telinta
    private static $currency = 'RSD';
    private static $a_iProduct = 12114;
    private static $CBProduct = 12114;
    private static $VoipProduct = 12114;
    public static $telintaSOAPUrl = "https://mybilling.telinta.com";
    public static $telintaSOAPUser = 'API_login';
    public static $telintaSOAPPassword = 'ee4eriny';

    public static function telintaRegisterCompany(Company $company) {
        $tCustomer = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        
        $vatNumber = "VB2B" . $company->getVatNo();
       // $credit_limit=($company->getCreditLimit()!='')?$company->getCreditLimit():'0';

        while (!$tCustomer && $retry_count < $max_retries) {
            try {
                $tCustomer = $pb->add_customer(array('customer_info' => array(
                                'name' => $vatNumber, //75583 03344090514
                                'iso_4217' => self::$currency,
                                'i_parent' => self::$iParent,
                                'i_customer_type' => 1,
                                'opening_balance' => 0,
                                'credit_limit' => '5000',
                                'dialing_rules' => array('ip' => '00'),
                                'email' => 'okh@zapna.com'
                                )));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                    emailLib::sendErrorInTelinta("Error in Company Registration", "We have faced an issue in Company registration on telinta. this is the error for cusotmer with  id: " . $company->getId() . " and error is " . $e->faultstring . "  <br/> Please Investigate.");
                    
                    return false;
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Error in Company Registration", "We have faced an issue in Company registration on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }
        $company->setICustomer($tCustomer->i_customer);
        //$company->save();
        
        return true;
    }

    public static function telintaRegisterEmployeeCT(Employee $employee,$productId) {
        $c = new Criteria();
        $c->addAnd(ProductPeer::ID, $productId);
        $product = ProductPeer::doSelectOne($c);
        $product = BillingProductsPeer::retrieveByPK($product->getBillingProductId());
        return self::createAccount($employee, 'a', $product->getAIproduct());
    }

    public static function telintaRegisterEmployeeCB(Employee $employee) {

        return self::createAccount($employee, 'cb', self::$CBProduct);
    }

    public static function createReseNumberAccount($VOIPNumber, Company $company, $currentActiveNumber) {

        if (self::createAccount($company, $VOIPNumber, '', self::$VoipProduct, 'Y')) {

            $accounts = false;
            $max_retries = 10;
            $retry_count = 0;

            $ct = new Criteria();
            $ct->add(TelintaAccountsPeer::ACCOUNT_TITLE, $VOIPNumber);
            $ct->addAnd(TelintaAccountsPeer::STATUS, 3);
            $telintaAccount = TelintaAccountsPeer::doSelectOne($ct);
            $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
            
            while (!$accounts && $retry_count < $max_retries) {
                try {
                    $accounts = $pb->update_account_followme(array('i_account' => $telintaAccount->getIAccount(),
                                "followme_info" => array(
                                    'i_account' => $telintaAccount->getIAccount(),
                                    'period' => '',
                                    'sequence' => 'Order',
                                    'timeout' => 30
                                    )));
                } catch (SoapFault $e) {
                    if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                        emailLib::sendErrorInTelinta("Account update_account_followme: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account update_account_followme on telinta. this is the error for cusotmer with  id: " . $company->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                        
                        return false;
                    }
                }


                sleep(0.5);
                $retry_count++;
            }

            if ($retry_count == $max_retries) {
                emailLib::sendErrorInTelinta("Account update_account_followme: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account update_account_followme on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
                return false;
            }
            
            $accounts = false;
            $max_retries = 10;
            $retry_count = 0;
            while (!$accounts && $retry_count < $max_retries) {
                try {
                    $accounts = $pb->add_followme_number(array('i_account' => $telintaAccount->getIAccount(),
                                'number_info' => array(
                                    'i_account' => $telintaAccount->getIAccount(),
                                    'name' => $currentActiveNumber,
                                    'redirect_number' => $currentActiveNumber,
                                    'period' => 'always',
                                    'period_description' => 'Always',
                                    'active' => 'Y',
                                    'timeout' => 15
                                )
                            ));
                } catch (SoapFault $e) {
                    if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                        emailLib::sendErrorInTelinta("Account add_followme_number: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account Deletion on telinta. this is the error for cusotmer with  id: " . $company->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                        
                        return false;
                    }
                }
                sleep(0.5);
                $retry_count++;
            }
            if ($retry_count == $max_retries) {
                emailLib::sendErrorInTelinta("Account add_account_followme: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Customer Account add_account_followme on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
                return false;
            }
        }
        return true;
    }

    public static function recharge(Company $company, $amount, $description="Refill") {
        return self::makeTransaction($company, "Manual payment", $amount, $description);
    }

    public static function charge(Company $company, $amount,  $description="Charge") {
        return self::makeTransaction($company, "Manual charge", $amount, $description);
    }

    public static function terminateAccount(TelintaAccounts $telintaAccount) {
        $account = false;
        $max_retries = 10;
        $retry_count = 0;
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
        
        while (!$account && $retry_count < $max_retries) {
            try {

                $account = $pb->terminate_account(array('i_account' => $telintaAccount->getIAccount()));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                    emailLib::sendErrorInTelinta("Account Deletion: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Company Account Deletion on telinta. this is the error for cusotmer with  id: " . $telintaAccount->getIAccount() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                    
                    return false;
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Account Deletion: " . $telintaAccount->getIAccount() . " Error!", "We have faced an issue in Company Account Deletion on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }
        $telintaAccount->setStatus(5);
        $telintaAccount->save();
        
        return true;
    }

    public static function getAccountCallHistory($iAccount, $fromDate, $toDate) {
        $xdrList = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
        
        while (!$xdrList && $retry_count < $max_retries) {
            try {
                $xdrList = $pb->get_xdr_list(array('i_account' => $iAccount, 'from_date' => $fromDate, 'to_date' => $toDate,'i_service' => $iService));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                    emailLib::sendErrorInTelinta("Employee Call History: " . $iAccount . " Error!", "We have faced an issue with Employee while Fetching Call History  this is the error for cusotmer with ID: " . $iAccount . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                    return false;
                    
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Employee Call History: " . $iAccount . " Error!", "We have faced an issue with Employee while Fetching Call History on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }
        
        return $xdrList;
    }

    public static function getAccountInfo($iAccount) {
        $aInfo = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
        
        while (!$aInfo && $retry_count < $max_retries) {
            try {
                $aInfo = $pb->get_account_info(array(
                            'i_account' => $iAccount,
                        ));
                
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                    emailLib::sendErrorInTelinta("Employee Account info Fetching:  Error!", "We have faced an issue in Employee Account Info Fetch on telinta. this is the error for cusotmer with  account: " . $iAccount . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                    
                    return false;
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Employee Account info Fetching:  Error!", "We have faced an issue in Employee Account Info Fetch on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }
        
        return $aInfo;
    }

    public static function getBalance(Company $company) {
        $cInfo = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        
        while (!$cInfo && $retry_count < $max_retries) {
            try {

                $cInfo = $pb->get_customer_info(array(
                            'i_customer' => $company->getICustomer(),
                        ));
                $Balance = $cInfo->customer_info->balance;
                
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                    emailLib::sendErrorInTelinta("Company Balance Fetching: " . $company->getId() . " Error!", "We have faced an issue in Company Account Balance Fetch on telinta. this is the error for cusotmer with  Uniqueid: " . $company->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                    
                    return false;
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Company Balance Fetching: " . $company->getId() . " Error!", "We have faced an issue in Company Account Balance Fetch on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }
        
        if ($Balance == 0)
            return $Balance;
        else
            return $Balance;
    }

    private static function createAccount(Employee $employee,$accountType, $iProduct, $followMeEnabled='N') {
        $account = false;
        $max_retries = 10;
        $retry_count = 0;
        $company = CompanyPeer::retrieveByPK($employee->getCompanyId());
        $batch_name = "VB2B".$company->getVatNo();
       // var_dump($company->getVatNo());die;
        $mobileNumber = $employee->getCountryMobileNumber();
        
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
        $accountName = $accountType . $mobileNumber;
        while (!$account && $retry_count < $max_retries) {
            try {

                $account = $pb->add_account(array('account_info' => array(
                                'i_customer' => $company->getICustomer(),
                                'name' => $accountName, //75583 03344090514
                                'id' => $accountName,
                                'iso_4217' => self::$currency,
                                'opening_balance' => 0,
                                'credit_limit' => null,
                                'i_product' => $iProduct,
                                'i_routing_plan' => 2782,
                                'billing_model' => 1,
                                'password' => "asdf1asd",
                                'h323_password' => "asdf1asd",
                                'activation_date' => date('Y-m-d'),
                                'batch_name' => $batch_name,
                                'follow_me_enabled' => $followMeEnabled
                                )));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                    emailLib::sendErrorInTelinta("Account Creation: " . $accountName . " Error!", "We have faced an issue in Company Account Creation on telinta. this is the error for cusotmer with  id: " . $company->getId() . " and on Account " . $accountName . " and batch_name = $batch_name error is " . $e->faultstring . "  <br/> Please Investigate.");
                    
                    return false;
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Account Creation: " . $accountName . " Error!", "We have faced an issue in Company Account Creation on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }

        $telintaAccount = new TelintaAccounts();
        $telintaAccount->setAccountTitle($accountName);
        $telintaAccount->setParentId($employee->getId());
        $telintaAccount->setParentTable("employee");
        $telintaAccount->setICustomer($company->getICustomer());
        $telintaAccount->setIAccount($account->i_account);
         if($accountType==""){
         $accountType='r';
        }
        $telintaAccount->setAccountType($accountType);
        $telintaAccount->save();
        return true;
    }

    private static function makeTransaction(Company $company, $action, $amount, $description) {
        $accounts = false;
        $max_retries = 10;
        $retry_count = 0;
//echo $description;
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        
        while (!$accounts && $retry_count < $max_retries) {
            try {
                $accounts = $pb->make_transaction(array(
                            'i_customer' => $company->getICustomer(),
                            'action' => $action, //Manual payment, Manual charge
                            'amount' => $amount,
                            'visible_comment' => $description
                        ));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                    emailLib::sendErrorInTelinta("Customer Transcation: " . $company->getId() . " Error!", "We have faced an issue with Customer while making transaction " . $action . " this is the error for cusotmer with  Customer ID: " . $company->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                    
                    return false;
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Customer Transcation: " . $company->getId() . " Error!", "We have faced an issue with Customer while making transaction on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }
        
        return true;
    }

    public static function callHistory(Company $company, $fromDate, $toDate, $reseller=false, $iService=3) {
        $xdrList = false;
        $max_retries = 10;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');
        if ($reseller){
            $icustomer = $company;
        }else{
            $icustomer = $company->getICustomer();
        }
        while (!$xdrList && $retry_count < $max_retries) {
            try {
                $xdrList = $pb->get_customer_xdr_list(array('i_customer' => $icustomer, 'from_date' => $fromDate, 'to_date' => $toDate,'i_service' => $iService));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                    emailLib::sendErrorInTelinta("Company Call History: " . $company->getId() . " Error!", "We have faced an issue with Company while Fetching Call History  this is the error for cusotmer with  Company ID: " . $company->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                    return false;
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Company Call History: " . $company->getId() . " Error!", "We have faced an issue with Company while Fetching Call History on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }
        
        return $xdrList;
    }

    public static function updateCustomer($update_customer_request){
        $customer = false;
        $max_retries = 5;
        $retry_count = 0;

        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Customer');

        while (!$customer && $retry_count < $max_retries) {
            try {
                $customer = $pb->update_customer(array('customer_info' => $update_customer_request));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host') {
                    emailLib::sendErrorInTelinta("Customer Update: " . $update_customer_request["i_customer"] . " Error!", "We have faced an issue in Company updation on telinta. this is the error for comapny with icustomer: " . $update_customer_request["i_customer"] . " error is " . $e->faultstring . " <br/> Please Investigate.");

                    return false;
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Customer Update: " . $update_customer_request["i_customer"] . " Error!", "We have faced an issue in Company updation on telinta. Error is Even After Max Retries".$max_retries." <br/> Please Investigate.");
            return false;
        }
        return true;

    }

    public static function randomPrefix($length) {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "abcdefghijklmnopqrstuvwxyz";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }

    public static function randomPrefixq($length) {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "0123456789";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }

    public static function randomPrefixw($length) {
        $random = "";
        srand((double) microtime() * 1000000);
        $data = "abcdefghijklmnopqrstuvwxyz";
        for ($i = 0; $i < $length; $i++) {
            $random .= substr($data, (rand() % (strlen($data))), 1);
        }
        return $random;
    }
   
    public static function updateAccount(Employee $employee, $iProduct, $iRoutingPlan, $block='N') {
        $account = false;
        $max_retries = 10;
        $retry_count = 0;

        $accountTitle = "a".$employee->getCountryMobileNumber();
        $til = new Criteria();
        $til->add(TelintaAccountsPeer::ACCOUNT_TITLE, $accountTitle);
        $til->addAnd(TelintaAccountsPeer::STATUS, 3);
        $tilentaAccount = TelintaAccountsPeer::doSelectOne($til);

        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');

       // $pass = self::randomAlphabets(4) . self::randomNumbers(1) . self::randomAlphabets(3);
        $accountName = $accountType . $mobileNumber;
        while (!$account && $retry_count < $max_retries) {
            try {

                $account = $pb->update_account(array('account_info' => array(
                                'i_account' => $tilentaAccount->getIAccount(),
                                'i_product' => $iProduct,
                                'i_routing_plan' => $iRoutingPlan,
                                'blocked' => $block,
                                )));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                    emailLib::sendErrorInTelinta("Account Update: " . $accountTitle . " Error!", "We have faced an issue in Company Account updation on telinta. this is the error for cusotmer with id: " . $employee->getCompanyId() . " and on Account" . $accountTitle . " error is " . $e->faultstring . " <br/> Please Investigate.");

                    return false;
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Account Update: " . $accountTitle . " Error!", "We have faced an issue in Company Account updation on telinta. Error is Even After Max Retries".$max_retries." <br/> Please Investigate.");
            return false;
        }
            return true;

    }
    
    public static function getSubscription(Employee $employee, $fromDate, $toDate) {
        $xdrList = false;
        $max_retries = 10;
        $retry_count = 0;
     //   var_dump($employee);
        $cta = new Criteria();
        $cta->add(TelintaAccountsPeer::PARENT_TABLE,'employee');
        $cta->addAnd(TelintaAccountsPeer::PARENT_ID,$employee->getId());
        $cta->addAnd(TelintaAccountsPeer::STATUS,3);
        $count_ta = TelintaAccountsPeer::doCount($cta);
        if($count_ta > 0){
        $telinta_account = TelintaAccountsPeer::doSelectOne($cta);    
       // var_dump($telinta_account);die;
        $pb = new PortaBillingSoapClient(self::$telintaSOAPUrl, 'Admin', 'Account');
     //   var_dump($pb);
        while (!$xdrList && $retry_count < $max_retries) {
            try {
                $xdrList = $pb->get_xdr_list(array('i_account' => $telinta_account->getIAccount(), 'from_date' => $fromDate, 'to_date' => $toDate,'i_service'=>4));
            } catch (SoapFault $e) {
                if ($e->faultstring != 'Could not connect to host' && $e->faultstring != 'Internal Server Error') {
                    emailLib::sendErrorInTelinta("Employee Subscription: " . $employee->getId() . " Error!", "We have faced an issue with Employee while Fetching Subscription  this is the error for employee with  Employee ID: " . $employee->getId() . " error is " . $e->faultstring . "  <br/> Please Investigate.");
                    return false;
                }
            }
            sleep(0.5);
            $retry_count++;
        }
        if ($retry_count == $max_retries) {
            emailLib::sendErrorInTelinta("Employee Subscription: " . $employee->getId() . " Error!", "We have faced an issue with Employee while Fetching Subscription on telinta. Error is Even After Max Retries " . $max_retries . "  <br/> Please Investigate.");
            return false;
        }
        //var_dump($xdrList);
        return $xdrList;
        }  
    }
}

?>
