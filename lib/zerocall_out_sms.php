<?php
require_once(sfConfig::get('sf_lib_dir') . '/smsCharacterReplacement.php');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ZeroCallOutSMS {
/*
 * @@prductName: This will be sent in the sms.
 * @@telephoneNumber: this will be sent in the SMS.
 * @@password: password of the account to be sent in sms.
 * @@recepientMobileNumber: This needs to be a complete number where user will be receiving the sms.
 *
 */
    public function toCustomerAfterReg($productId, Customer $customer) {
        
        $product                = ProductPeer::retrieveByPK($productId);
        $productName            = $product->getName();
        $telephoneNumber        = $customer->getMobileNumber();
       // $callingCode            = $customer->getCountryCode();
        $callingCode            = sfConfig::get("app_country_code");
        $recipientMobileNumber  = $callingCode.$telephoneNumber;
        $password               = $customer->getPlainText();
        $agentid                = $customer->getReferrerId();
         if (isset($agentid) && $agentid != "") {
             $agent = AgentCompanyPeer::retrieveByPK($agentid);
             $agentMobileNumber = $agent->getMobileNumber();
             //$agentMobileNumber = "923334414765";
             $this->toAgentAfterReg($telephoneNumber, $agentMobileNumber);
        }


        //$recipientMobileNumber = "923334414765";


        $sms_dk_object = SmsTextPeer::retrieveByPK(2);
        $sms_uk_object = SmsTextPeer::retrieveByPK(3);
        $sms_dk_object2 = SmsTextPeer::retrieveByPK(4);


        $sms_text_dk = $sms_dk_object->getMessageText();
        $sms_text_dk = str_replace("(productname-zerocall-out)", $productName, $sms_text_dk);
        $sms_text_dk = str_replace("(telephonenumber)", $telephoneNumber, $sms_text_dk);
        $sms_text_dk = str_replace("(chosen-password)", $password, $sms_text_dk);
        $this->carbordfishSMS($recipientMobileNumber, $sms_text_dk);

        $sms_text_uk = $sms_uk_object->getMessageText();
        $sms_text_uk = str_replace("(siteurl)", sfConfig::get("app_site_url"), $sms_text_uk);
        $sms_text_uk = str_replace("(productname-zerocall-out)", $productName, $sms_text_uk);
        $sms_text_uk = str_replace("(telephonenumber)", $telephoneNumber, $sms_text_uk);
        $sms_text_uk = str_replace("(chosen-password)", $password, $sms_text_uk);
        $this->carbordfishSMS($recipientMobileNumber, $sms_text_uk);

        $sms_text_dk2 = $sms_dk_object2->getMessageText();
        $this->carbordfishSMS($recipientMobileNumber, $sms_text_dk2);
        
    }

/*
 * @@customerMobileNumber: to be sent in sms.
 * @@agentMobileNumber: This needs to be a complete number where user will be receiving the sms.
 *
 */

    public function toAgentAfterReg($customerMobileNumber,$agentMobileNumber) {
        //$sms_dk_object = SmsTextPeer::retrieveByPK(13);
        $sms_dk_object = SmsTextPeer::retrieveByPK(5);
        $sms_text_dk = $sms_dk_object->getMessageText();
        $sms_text_dk = str_replace("(customer-telephone-number)", $customerMobileNumber, $sms_text_dk);
        $sms_text_dk = str_replace("(datetime)", date('H:i d-m-Y'), $sms_text_dk);
       
        $this->carbordfishSMS($agentMobileNumber, $sms_text_dk);
    }
    
    public function toCustomerForgotPassword(Customer $customer){
        
        $customerCell           = $customer->getMobileNumber();
        $customerPassword       = $customer->getPlainText();
        $callingCode            = $customer->getCountryCode();
        $MobileNumber           = $callingCode.$customerCell;
        
        $sms_dk_object    = SmsTextPeer::retrieveByPK(20);
        $sms_text         = $sms_dk_object->getMessageText();
        $sms_text         = str_replace("(mobilenumber)", $customerCell, $sms_text);
        $sms_text         = str_replace("(password)", $customerPassword, $sms_text);

        $this->carbordfishSMS($MobileNumber, $sms_text);
    }
    
    public function toWebSMSCustomer(Customer $customer,$productId,$setupFee=true) {
        
        $p = ProductPeer::retrieveByPK($productId);
        
        $countryCode = $customer->getCountryCode();
        $cMobile = $customer->getMobileNumber();
        $da = $countryCode.$cMobile;



       $sms_dk_object = SmsTextPeer::retrieveByPK(27);
       $sms_text_dk = $sms_dk_object->getMessageText();
       $sms_text_dk = str_replace("(product)", $p->getName(), $sms_text_dk);
       $sms_text_dk = str_replace("(initial-balance)", $p->getInitialBalance(), $sms_text_dk);
       $this->carbordfishSMS($da, $sms_text_dk);

       if($setupFee)
            $sms_dk_object = SmsTextPeer::retrieveByPK(28);
       else
            $sms_dk_object =  SmsTextPeer::retrieveByPK(29);
       
       $sms_text_dk = $sms_dk_object->getMessageText();
       $sms_text_dk = str_replace("(setup-fee)", $p->getPrice(), $sms_text_dk);
       $sms_text_dk = str_replace("(subscription-fee)", $p->getSubscriptionFee(), $sms_text_dk);
       $sms_text_dk = str_replace("(total-fee)", $p->getSubscriptionFee()+$p->getPrice(), $sms_text_dk);
       $sms_text_dk = str_replace("(product)", $p->getName(), $sms_text_dk);
       $this->carbordfishSMS($da, $sms_text_dk);
    }

    public function toZerocallFree($customer){
        
        $countryCode = $customer->getCountryCode();
        $cMobile = $customer->getMobileNumber();
        $da = $countryCode.$cMobile;
        
        $sms_dk_object = SmsTextPeer::retrieveByPK(30);
        $sms_text_dk = $sms_dk_object->getMessageText();
        $this->carbordfishSMS($da, $sms_text_dk);
    }

    private  function carbordfishSMS($mobile_number,$sms_text){
        $data1 = array(
            'S' => 'H',
            'UN' => 'zapna1',
            'P' => 'Zapna2010',
            'DA' => $mobile_number,
            'SA' => 'Zerocall',
            'M' => $sms_text,
            'ST' => '5'
        );

        $queryString = http_build_query($data1, '', '&');
        $queryString = smsCharacter::smsCharacterReplacement($queryString);
        $res = file_get_contents('http://sms1.cardboardfish.com:9001/HTTPSMS?' . $queryString);
        sleep(0.25);
    }
}

?>
