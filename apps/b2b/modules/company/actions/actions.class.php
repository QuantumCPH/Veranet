<?php
require_once(sfConfig::get('sf_lib_dir') . '/zerocall_out_sms.php');
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
        $c = new Criteria();
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
            $tomorrow1 = mktime(0, 0, 0, date("m"), date("d") - 3, date("Y"));
            $this->fromdate = date("Y-m-d", $tomorrow1);
            //$tomorrow = mktime(0, 0, 0, date("m"), date("d") + 1, date("Y"));
            $this->todate = date("Y-m-d");
        }
        $this->iaccount = $request->getParameter('iaccount');
        $fromdate = $this->fromdate . " 00:00:00";
        $todate = $this->todate. " 23:59:59" ;
//        if (isset($this->iaccount) && $this->iaccount != '') {
//            $ce = new Criteria();
//            $ce->add(TelintaAccountsPeer::ID, $this->iaccount);
//            $ce->addAnd(TelintaAccountsPeer::STATUS, 3);
//            $telintaAccount = TelintaAccountsPeer::doSelectOne($ce);
//
//            $this->iAccountTitle = $telintaAccount->getAccountTitle();
//
//            $this->callHistory = CompanyEmployeActivation::getAccountCallHistory($telintaAccount->getIAccount(), $this->fromdate . " 00:00:00", $this->todate . " 23:59:59");
//        } else {
//
//            $this->callHistory = CompanyEmployeActivation::callHistory($this->company, $this->fromdate . " 00:00:00", $this->todate . " 23:59:59");
//        }
          if (isset($this->iaccount) && $this->iaccount != '') {
            $ce = new Criteria();
            $ce->add(EmployeeCallhistoryPeer::ACCOUNT_ID, $this->iaccount);
            $ce->addAnd(EmployeeCallhistoryPeer::CONNECT_TIME, " connect_time > '" . $fromdate . "' ", Criteria::CUSTOM);
            $ce->addAnd(EmployeeCallhistoryPeer::CONNECT_TIME, " connect_time  < '" . $todate . " 23:59:59" . "' ", Criteria::CUSTOM);
            $this->callHistory = EmployeeCallhistoryPeer::doSelect($ce);
            
           // $this->callHistory = CompanyEmployeActivation::getAccountCallHistory($telintaAccount->getIAccount(), $this->fromdate . " 00:00:00", $this->todate . " 23:59:59");
          } else {
            
            $ce = new Criteria();
            $ce->add(EmployeeCallhistoryPeer::COMPANY_ID, $this->company->getId());
            $ce->addAnd(EmployeeCallhistoryPeer::CONNECT_TIME, " connect_time > '" . $fromdate . "' ", Criteria::CUSTOM);
            $ce->addAnd(EmployeeCallhistoryPeer::CONNECT_TIME, " connect_time  < '" . $todate . "' ", Criteria::CUSTOM);
            $this->callHistory = EmployeeCallhistoryPeer::doSelect($ce);
            
            //$this->callHistory = CompanyEmployeActivation::callHistory($this->company, $this->fromdate . " 00:00:00", $this->todate . " 23:59:59");
          }
        echo $fromdate;
        echo '<br />';
        echo $todate;
        $c = new Criteria();
        $c->add(TelintaAccountsPeer::I_CUSTOMER, $this->company->getICustomer());
        $c->addAnd(TelintaAccountsPeer::STATUS, 3);
       // echo 'icustomer---'.TelintaAccountsPeer::doCount($c);
        $this->telintaAccountObj = TelintaAccountsPeer::doSelect($c);
        //var_dump($this->telintaAccountObj);die;
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
    
    public function executeCompanyBilling(sfWebRequest $request)
    {
        $company_id = $request->getParameter('company_id');
    
     echo   $this->billing_start_date = date('Y-m-d 00:00:00', strtotime($request->getParameter('start_date')));
  echo'--';   echo   $this->billing_end_date = date('Y-m-d 23:59:59', strtotime($request->getParameter('end_date')));
        $this->forward404Unless($company_id && $this->billing_start_date && $this->billing_end_date);

        if (!($company = CompanyPeer::retrieveByPK($company_id))) {
            $this->forward404();
        }

        $billings = array();
        $ratings = array();
        $bilcharge = 00.00;

        $ec = new Criteria();
        $ec->add(EmployeePeer::COMPANY_ID, $company_id);
        $this->employees = EmployeePeer::doSelect($ec);
        
        $invoice_id = $company->getInvoiceMethodId();
        $im = new Criteria();
        $im->add(InvoiceMethodPeer::ID, $invoice_id);
        $invoice = InvoiceMethodPeer::doSelectOne($im);
        $this->invoice_cost = $invoice->getCost();

        $new_invoice = new Invoice();
        $new_invoice->setCompany($company);
        $new_invoice->setBillingStartingDate($this->billing_start_date);
        $new_invoice->setBillingEndingDate($this->billing_end_date);
        
//        $billing_due_days = $invoice->getBillingdays();
//        $due_date = date("Y-m-d H:i:s", time() + ((60 * 60) * 24) * $billing_due_days);
//
//        $new_invoice->setDueDate($due_date);
        $new_invoice->setInvoiceStatusId(4); // inactive
        $new_invoice->save();
      //  $new_invoice->setInvoiceNumber(date('dmy').$new_invoice->getId());

        $new_invoice->save();
     //   var_dump($new_invoice);
        $this->invoice_meta = $new_invoice;
        $this->company_meta = $company;

        $this->setLayout(false);    
    } 
}
