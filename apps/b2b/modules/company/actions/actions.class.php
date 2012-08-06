<?php

/**
 * company actions.
 *
 * @package    zapnacrm
 * @subpackage company
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12479 2008-10-31 10:54:40Z fabien $
 */
class companyActions extends sfActions {

    /**
     * Executes index action
     *
     * @param sfRequest $request A request object
     */
    public function executeDashboard(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->getAttribute('companyname', '', 'companysession'));
        $this->company = CompanyPeer::retrieveByPK($this->getUser()->getAttribute('company_id', '', 'companysession'));
        $this->balance = CompanyEmployeActivation::getBalance($this->company);
        
        $c = new Criteria();
        $c->add(EmployeePeer::COMPANY_ID, $this->company->getId());
        $c->addAnd(EmployeePeer::STATUS_ID,3);
        $this->employees = EmployeePeer::doSelect($c);

        $nc = new Criteria();
        $nc->addDescendingOrderByColumn(NewupdatePeer::STARTING_DATE);
        $this->updateNews = NewupdatePeer::doSelect($nc);
    }

    public function executeLogin($request) {
        if ($request->getParameter('new'))
            $this->getUser()->setCulture($request->getParameter('new'));
        else
            $this->getUser()->setCulture($this->getUser()->getCulture());
        //die();
        $this->form = new CompanyLoginForm();
        if ($request->isMethod('post')) {
            $this->form->bind($request->getParameter('login'), $request->getFiles('login'));
            if ($this->form->isValid()) {
                $c = new Criteria();
                $c->Add(CompanyPeer::VAT_NO, $this->form->getValue('vat_no'));
                $c->addAnd(CompanyPeer::PASSWORD, $this->form->getValue('password'));
                $countCo = CompanyPeer::doCount($c);
//                echo $company->getStatusId();
//                var_dump($company);
//                        die;
                if($countCo > 0){ 
                    $company = CompanyPeer::doSelectOne($c); 
                    if ($company->getStatusId() == 1) {
                        $this->getUser()->setAuthenticated(true);
                        $this->getUser()->setAttribute('company_id', $company->getId(), 'companysession');
                        $this->getUser()->setAttribute('companyname', $company->getName(), 'companysession');
                        $this->company = $company;
                        $this->redirect(sfConfig::get('app_b2b_url') . 'company/dashboard');
                    } else {
                        $this->getUser()->setFlash('login_error_message', $this->getContext()->getI18N()->__('Your account is not active.'));
                    }
                }else{
                       $this->getUser()->setFlash('login_error_message', $this->getContext()->getI18N()->__('Incorrect Vat No. or Password.'));
                }     
            }
        }
    }

    public function executeLogout() {
        $this->getUser()->getAttributeHolder()->removeNamespace('companysession');
        $this->getUser()->setAuthenticated(false);
        $this->redirect(sfConfig::get('app_b2b_url') . 'company/login');
    }

    public function executeNewsListing(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->isAuthenticated());


        $c = new Criteria();
        $c->addDescendingOrderByColumn(NewupdatePeer::STARTING_DATE);
        $news = NewupdatePeer::doSelect($c);
        $this->news = $news;
    }

    public function executeView($request) {

        $this->forward404Unless($this->getUser()->getAttribute('companyname', '', 'companysession'));
        $this->company = CompanyPeer::retrieveByPK($this->getUser()->getAttribute('company_id', '', 'companysession'));
        $this->balance = CompanyEmployeActivation::getBalance($this->company);
        $c = new Criteria();
        $c->Add(EmployeePeer::COMPANY_ID, $this->company->getId());
        $this->employeesCount = EmployeePeer::doCount($c);
    }

    public function executePaymentHistory(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->getAttribute('companyname', '', 'companysession'));
        
        $ct = new Criteria();
        $ct->add(TransactionDescriptionPeer::ID, 10);
        $description = TransactionDescriptionPeer::doSelectOne($ct);
        
        $c = new Criteria();
        if($description){
            $c->add(CompanyTransactionPeer::PAYMENTTYPE,10);
           //$c->add(CompanyTransactionPeer::DESCRIPTION, '%'.$description->getTitle().'%', Criteria::LIKE);
        }   
        //$c->add(CompanyTransactionPeer::DESCRIPTION, '%Company Refill%', Criteria::LIKE);
        $c->add(CompanyTransactionPeer::TRANSACTION_STATUS_ID, 3);
        $c->addAnd(CompanyTransactionPeer::COMPANY_ID, $this->getUser()->getAttribute('company_id', '', 'companysession'));
        $c->addDescendingOrderByColumn(CompanyTransactionPeer::CREATED_AT);
        $this->transactions = CompanyTransactionPeer::doSelect($c);
    }

    public function executeShowReceipt(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->getAttribute('companyname', '', 'companysession'));
        $c = new Criteria();
        $c->add(CompanyTransactionPeer::TRANSACTION_STATUS_ID, 3);
        $c->addAnd(CompanyTransactionPeer::COMPANY_ID, $this->getUser()->getAttribute('company_id', '', 'companysession'));
        $c->addAnd(CompanyTransactionPeer::ID, $request->getParameter('tid'));
        $transactionCount = CompanyTransactionPeer::doCount($c);
        if ($transactionCount == 1) {
            $transaction = CompanyTransactionPeer::doSelectOne($c);
            $this->renderPartial('company/refill_receipt', array(
                'company' => CompanyPeer::retrieveByPK($transaction->getCompanyId()),
                'transaction' => $transaction,
                'vat' => 0,
            ));
        } else {
            die("Unable to Show the Reciept");
        }
        return sfView::NONE;
    }

    public function executeCallHisotry(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->getAttribute('companyname', '', 'companysession'));
        $this->company = CompanyPeer::retrieveByPK($this->getUser()->getAttribute('company_id', '', 'companysession'));
        if (isset($_POST['startdate']) && isset($_POST['enddate'])) {
            $this->fromdate = $request->getParameter('startdate');
            $this->todate = $request->getParameter('enddate');
        } else {
           // $tomorrow1 = mktime(0, 0, 0, date("m"), date("1") , date("Y"));
            $this->fromdate = date("Y-m-1");
            //$tomorrow = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
           $this->todate = date("Y-m-t");
        }
        $this->iaccount = $request->getParameter('iaccount');
        $fromdate = $this->fromdate . " 21:00:00";
        $fromdate = date('Y-m-d 21:00:00',  strtotime('-1 day',strtotime($fromdate)));
        $todate = $this->todate. " 21:59:59" ;
        
        if (isset($this->iaccount) && $this->iaccount != '') {
            $ce = new Criteria();
            $ce->add(TelintaAccountsPeer::ID, $this->iaccount);
            $ce->addAnd(TelintaAccountsPeer::STATUS, 3);
            $telintaAccount = TelintaAccountsPeer::doSelectOne($ce);

            $this->iAccountTitle = $telintaAccount->getAccountTitle();
            $this->empl = EmployeePeer::retrieveByPK($telintaAccount->getParentId());
            $this->callHistory = CompanyEmployeActivation::getAccountCallHistory($telintaAccount->getIAccount(), $fromdate, $todate);
        } else {

            $this->callHistory = CompanyEmployeActivation::callHistory($this->company, $fromdate, $todate);            
        }
         
        $c = new Criteria();
        $c->add(TelintaAccountsPeer::I_CUSTOMER, $this->company->getICustomer());
        $c->addAnd(TelintaAccountsPeer::STATUS, 3);
        $this->telintaAccountObj = TelintaAccountsPeer::doSelect($c);
        
        $ces = new Criteria();
        $ces->add(EmployeePeer::COMPANY_ID,$this->company->getId());
        $ces->addAnd(EmployeePeer::STATUS_ID,3);
        if(EmployeePeer::doCount($ces)>0)  {
             $this->ems = EmployeePeer::doSelect($ces);
        }

    }
    public function executeCallHistory($request){
            
            $this->forward404Unless($this->getUser()->getAttribute('companyname', '', 'companysession'));
            $this->company = CompanyPeer::retrieveByPK($this->getUser()->getAttribute('company_id', '', 'companysession'));
            $billingduration = $request->getParameter('billingduration');
            
            $companyId = $this->company->getId();
            
          // echo $this->no_days = date("t");
            
            $emp_c = new Criteria();
            $emp_c->add(EmployeePeer::COMPANY_ID,$companyId);
            $emp_c->add(EmployeePeer::STATUS_ID,3);
            $this->employees = EmployeePeer::doSelect($emp_c);            
            
            $employee_ids = array();
            foreach($this->employees as $emp){
                $employee_ids[] = $emp->getId();
            }
            $this->employee_id ="";
            
            $cb = new Criteria();
            $cb->add(EmployeeCustomerCallhistoryPeer::PARENT_TABLE,'employee');
            $cb->addAnd(EmployeeCustomerCallhistoryPeer::PARENT_ID,$employee_ids,Criteria::IN);
            if ($request->isMethod('post')) {
               $this->employee_id = $request->getParameter('employee');
               if($this->employee_id!=""){
                  $emp_c->addAnd(EmployeePeer::ID,$this->employee_id);
                  $cb->addAnd(EmployeeCustomerCallhistoryPeer::PARENT_ID,$this->employee_id); 
               }
               if($billingduration!='all'){
                 $duration = explode("_",$billingduration); 
                 $starting = $duration[0];
                 $ending   = $duration[1];
                 $cb->addAnd(EmployeeCustomerCallhistoryPeer::CONNECT_TIME, " connect_time >= '" . $starting . "' ", Criteria::CUSTOM);
                 $cb->addAnd(EmployeeCustomerCallhistoryPeer::DISCONNECT_TIME, " disconnect_time  <= '" . $ending . "' ", Criteria::CUSTOM);
                 $this->start = $starting;
                 $this->end = $ending;
              }elseif($billingduration=='all'){
                 $employee = EmployeePeer::doSelectOne($emp_c);
                 $empCreat = $employee->getCreatedAt();
                 
                 $this->start = $this->company->getCreatedAt();
                 $this->end = date('Y-m-d h:i:s');
              }
            }else{  
                 $starting = date('Y-m-01 00:00:00');
                 $ending   = date('Y-m-t 23:59:59');
                 $cb->addAnd(EmployeeCustomerCallhistoryPeer::CONNECT_TIME, " connect_time >= '" . $starting . "' ", Criteria::CUSTOM);
                 $cb->addAnd(EmployeeCustomerCallhistoryPeer::DISCONNECT_TIME, " disconnect_time  <= '" . $ending . "' ", Criteria::CUSTOM);
                 $billingduration = $starting.'_'.$ending;
                 $this->start = $starting;
                 $this->end = $ending;
                 $this->last = $starting;
                 $this->till = $ending;
            }
            
            
            $cb->addDescendingOrderByColumn(EmployeeCustomerCallhistoryPeer::ACCOUNT_ID);
            $cb->addDescendingOrderByColumn(EmployeeCustomerCallhistoryPeer::CONNECT_TIME);
            $this->callHistory = EmployeeCustomerCallhistoryPeer::doSelect($cb);

            $ic = new Criteria();
            $ic->add(InvoicePeer::COMPANY_ID,$companyId);
            $ic->addGroupByColumn(InvoicePeer::BILLING_STARTING_DATE);
            $ic->addDescendingOrderByColumn(InvoicePeer::BILLING_STARTING_DATE);

            $this->invoiceTimings = InvoicePeer::doSelect($ic);
            #$emp_c->add(EmployeePeer::CREATED_AT,$this->end,Criteria::LESS_EQUAL);
            $this->employees = EmployeePeer::doSelect($emp_c);
            $this->billingduration = $billingduration;

            $im = new Criteria();
            $im->add(InvoicePeer::BILLING_STARTING_DATE, $starting);
            $im->addAnd(InvoicePeer::BILLING_ENDING_DATE, $ending);
            $im->addAnd(InvoicePeer::COMPANY_ID,$companyId);
            $im->addSelectColumn('sum(' . InvoicePeer::INVOICE_COST. ') AS invoice_cost');
            $im->addSelectColumn('sum(' . InvoicePeer::MOMS. ') AS MOMS');
            $sum = InvoicePeer::doSelectStmt($im);
            $resultset = $sum->fetch(PDO::FETCH_OBJ);
            $this->total_invoice_cost =$resultset->invoice_cost;
            $this->total_mom = $resultset->MOMS;
             
           // print_r($this->billing);
        }
    public function executeForgotPassword(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            $c = new Criteria();
            $c->add(CompanyPeer::VAT_NO, $request->getParameter('vat_number'));
            $c->add(CompanyPeer::STATUS_ID, sfConfig::get('app_status_completed', 1));


            $company = CompanyPeer::doSelectOne($c);

            if ($company) {
                //change the password to some thing uniuque and complex
                $new_password = substr(base64_encode($company->getPassword()), 0, 8);

                $company->setPassword($new_password);
                $message_body = $this->getContext()->getI18N()->__('Hi') . ' ' . $company->getName() . '!';
                $message_body .= '<br /><br />';
                $message_body .= $this->getContext()->getI18N()->__('Your password has been changed. Please use the following information to login to your Moiize agent account.');
                $message_body .= '<br /><br />';
                $message_body .= sprintf($this->getContext()->getI18N()->__('Vat Number: %s'), $company->getVatNo());
                $message_body .= '<br />';
                $message_body .= $this->getContext()->getI18N()->__('password') . ': ' . $new_password;

                $company->save();


                $subject = $this->getContext()->getI18N()->__('Password Request');
                $sender_email = sfConfig::get('app_email_sender_email', 'rs@zapna.com');
                $sender_name = sfConfig::get('app_email_sender_name', 'support');

                $message = $message_body;


                $receipient_email = trim($company->getEmail());
                $receipient_name = sprintf('%s', $company->getContactName());

                $cc = new Criteria();
                $cc->add(CountryPeer::ID, $company->getCountryId());
                $country = CountryPeer::doSelectOne($cc);

                ////SMS Text

                $sms_txt = $this->getContext()->getI18N()->__('Hi') . ' ' . $company->getName() . '! ';
                $sms_txt .= $this->getContext()->getI18N()->__('Your new password is . ');
                $sms_txt .= sprintf($this->getContext()->getI18N()->__('Log In: %s'), $company->getVatNo());
                $sms_txt .= " " . $this->getContext()->getI18N()->__('Password') . ': ' . $new_password;


                $mobileNumber = $company->getHeadPhoneNumber();

                if (substr($mobileNumber, 0, 2) == "00") {
                    $mobileNumber = substr_replace($mobileNumber, "", 0, 2);
                }
                $mobileNumber = $country->getCallingCode() . $mobileNumber;


                emailLib::sendB2BAgentForgetPasswordEmail($company, $message, $subject);
                CARBORDFISH_SMS::Send($mobileNumber, $sms_txt, "Moiize");

                $this->getUser()->setFlash('send_password_message', $this->getContext()->getI18N()->__('Your account details have been sent to your email address and mobile number.'));
            } else {
                $this->getUser()->setFlash('send_password_error_message', $this->getContext()->getI18N()->__('No agent is registered with this vat number.'));
            }
            return $this->redirect(sfConfig::get('app_b2b_url') . 'company/login');
        }
    }

    public function executeRates(sfWebRequest $request) {
        $cr = new Criteria();
        $this->rates = RatesPeer::doSelect($cr);
    }

    public function executeChangePassword(sfWebRequest $request) {
        $this->forward404Unless($this->getUser()->getAttribute('companyname', '', 'companysession'));
        $this->company = CompanyPeer::retrieveByPK($this->getUser()->getAttribute('company_id', '', 'companysession'));
        $this->vatNo = $this->company->getVatNo();

        if ($request->isMethod('post')) {
            $oldPassword = $request->getParameter('oldPassword');
            $newPassword = $request->getParameter('newPassword');

            if ($oldPassword == $this->company->getPassword()) {
                $this->company->setPassword($newPassword);
                $this->company->save();
                $this->getUser()->setFlash('change_password_message', $this->getContext()->getI18N()->__('Your password has been changed.'));
            } else {
                $this->getUser()->setFlash('change_password_error_message', $this->getContext()->getI18N()->__('Old Password did not match'));
            }
            return $this->redirect(sfConfig::get('app_b2b_url') . 'company/view');
        }
    }
    
    public function executeSelectdate(sfWebRequest $request)
    {
             
    } 
    
    public function executeInvoices(sfWebRequest $request)
    {
       
       $this->forward404Unless($this->getUser()->getAttribute('companyname', '', 'companysession'));
       $this->company = CompanyPeer::retrieveByPK($this->getUser()->getAttribute('company_id', '', 'companysession'));
        
       $billingduration = $request->getParameter('billingduration');
       $this->statusid = $request->getParameter('statusid');     
       
      
       
       $ic = new Criteria();
       $ic->add(InvoicePeer::COMPANY_ID,$this->company->getId());
       $ic->addGroupByColumn(InvoicePeer::BILLING_STARTING_DATE);
       $ic->addDescendingOrderByColumn(InvoicePeer::BILLING_STARTING_DATE);

       $ci = new Criteria();
         
       $cis = new Criteria();
       $cis->add(InvoiceStatusPeer::ID,4 ,CRITERIA::NOT_EQUAL);
       $this->invoice_status = InvoiceStatusPeer::doSelect($cis);
       if($this->statusid !='' ){           
         $ci->add(InvoicePeer::INVOICE_STATUS_ID,$this->statusid);  /// pending,paid,expire
       }else{
         $ci->add(InvoicePeer::INVOICE_STATUS_ID,4,Criteria::NOT_EQUAL);  /// pending,paid,expire  
       }
       if($billingduration){
         $duration = explode("_",$billingduration); 
         $starting = $duration[0];
         $ending   = $duration[1];
         $ci->addAnd(InvoicePeer::BILLING_STARTING_DATE, " billing_starting_date >= '" . $starting . "' ", Criteria::CUSTOM);
         $ci->addAnd(InvoicePeer::BILLING_ENDING_DATE, " billing_ending_date  <= '" . $ending . "' ", Criteria::CUSTOM);
       }
       $ci->addAnd(InvoicePeer::COMPANY_ID,$this->company->getId());
       $ci->add(InvoicePeer::TOTALPAYMENT,1,CRITERIA::GREATER_EQUAL);  
       
       $ci->addDescendingOrderByColumn(InvoicePeer::BILLING_STARTING_DATE);
       
       $this->invoices = InvoicePeer::doSelect($ci);
       $this->billingduration = $billingduration;

       

       $this->invoiceTimings = InvoicePeer::doSelect($ic);
    }
    
    public function executeShowInvoice(sfRequest $request){
       $invoiceid = $request->getParameter('id');
       
       $invoice = InvoicePeer::retrieveByPK($invoiceid);
       $this->invoiceHtml = $invoice->getInvoiceHtml();
       $this->setLayout(false);
   }
}
