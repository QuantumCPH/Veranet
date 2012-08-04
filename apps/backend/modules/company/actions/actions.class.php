<?php

require_once(sfConfig::get('sf_lib_dir') . '/company_employe_activation.class.php');
require_once(sfConfig::get('sf_lib_dir') . '/emailLib.php');

/**
 * autoCompany actions.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage autoCompany
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: actions.class.php 16948 2009-04-03 15:52:30Z fabien $
 */
class companyActions extends sfActions {

    public function executeCountrycity(sfWebRequest $request) {

        $this->country_id = $request->getParameter('country_id');

        $c = new Criteria();
        $c->add(CityPeer::COUNTRY_ID, $this->country_id);
        $c->addAscendingOrderByColumn('name');
        $Lcities = CityPeer::doSelect($c);
        $cities_List = $Lcities;
        foreach ($Lcities as $city) {

            $cities_List[$city->getId()] = $city->getName();
        }
        $this->cities_list = $cities_List;
        $this->setLayout(false);
    }

    public function executeIndex(sfWebRequest $request) {
        return $this->forward('company', 'list');
    }

    public function executeList($request) {


        $this->processSort();

        $this->processFilters();

        $this->filters = $this->getUser()->getAttributeHolder()->getAll('sf_admin/company/filters');

        // pager
        $this->pager = new sfPropelPager('Company', 1000);
        $c = new Criteria();
        $this->addSortCriteria($c);
        $this->addFiltersCriteria($c);
        $this->pager->setCriteria($c);
        $this->pager->setPage($this->getRequestParameter('page', $this->getUser()->getAttribute('page', 1, 'sf_admin/company')));
        $this->pager->init();

        // save page
        if ($this->getRequestParameter('page')) {
            $this->getUser()->setAttribute('page', $this->getRequestParameter('page'), 'sf_admin/company');
        }
    }

    public function executeCreate(sfWebRequest $request) {
        return $this->forward('company', 'edit');
    }

    public function executeSave(sfWebRequest $request) {
        return $this->forward('company', 'edit');
    }

    public function executeDeleteSelected(sfWebRequest $request) {
        $this->selectedItems = $this->getRequestParameter('sf_admin_batch_selection', array());

        try {
            foreach (CompanyPeer::retrieveByPks($this->selectedItems) as $object) {
                $object->delete();
            }
        } catch (PropelException $e) {
            $request->setError('delete', 'Could not delete the selected Companys. Make sure they do not have any associated items.');
            return $this->forward('company', 'list');
        }

        return $this->redirect('company/list');
    }

    public function executeEdit(sfWebRequest $request) {
        $this->company = $this->getCompanyOrCreate();

        if ($request->isMethod('post')) {
            $this->updateCompanyFromRequest();

            try {
                $this->saveCompany($this->company);
            } catch (PropelException $e) {
                $request->setError('edit', 'Could not save the edited Company.');
                return $this->forward('company', 'list');
            }

            $this->getUser()->setFlash('notice', 'Your modifications have been saved');

            if ($this->getRequestParameter('save_and_add')) {
                return $this->redirect('company/create');
            } else if ($this->getRequestParameter('save_and_list')) {
                return $this->redirect('company/list');
            } else {
                return $this->redirect('company/edit?id=' . $this->company->getId());
            }
        } else {
            $this->labels = $this->getLabels();
        }
    }

    public function executeDelete(sfWebRequest $request) {
        $this->company = CompanyPeer::retrieveByPk($this->getRequestParameter('id'));
        $this->forward404Unless($this->company);

        try {
            $this->deleteCompany($this->company);
        } catch (PropelException $e) {
            $request->setError('delete', 'Could not delete the selected Company. Make sure it does not have any associated items.');
            return $this->forward('company', 'list');
        }

        $currentFile = sfConfig::get('sf_upload_dir') . "//" . $this->company->getFilePath();
        if (is_file($currentFile)) {
            unlink($currentFile);
        }

        return $this->redirect('company/list');
    }

    public function handleErrorEdit() {
        $this->preExecute();
        $this->company = $this->getCompanyOrCreate();
        $this->updateCompanyFromRequest();

        $this->labels = $this->getLabels();

        return sfView::SUCCESS;
    }

    protected function saveCompany($company) {
        $companyData = $this->getRequestParameter('company');
        if ($company->isNew()) {
            $res = CompanyEmployeActivation::telintaRegisterCompany($company);
        }
        $company->isNew() . ":" . $res;

        if ($company->isNew() && $res) {

            //var_dump($companyData);
            //var_dump($company);
            $company->setCreditLimit('5000');
            $company->save();
          
//            $transaction = new CompanyTransaction();
//            $transaction->setAmount(5000);
//            $transaction->setCompanyId($company->getId());
//            $transaction->setExtraRefill(5000);
//            $transaction->setTransactionStatusId(3);
//            $transaction->setPaymenttype(1); //Registered
//            $transaction->setDescription('Company Registered');
//            $transaction->save();
        } elseif (!$company->isNew()) {
            $update_customer['i_customer']=$company->getICustomer();
            $update_customer['credit_limit']=($company->getCreditLimit()!='')?$company->getCreditLimit():'0';
            $res = CompanyEmployeActivation::updateCustomer($update_customer);
            $company->save();
        } elseif (!$res) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }
    }

    protected function deleteCompany($company) {
        $company->delete();
    }

    protected function updateCompanyFromRequest() {
        $company = $this->getRequestParameter('company');


        if (isset($company['name'])) {
            $this->company->setName($company['name']);
        }
        if (isset($company['vat_no'])) {
            $this->company->setVatNo($company['vat_no']);
        }
        if (isset($company['password'])) {
            $this->company->setPassword($company['password']);
        }
        if (isset($company['address'])) {
            $this->company->setAddress($company['address']);
        }
        if (isset($company['post_code'])) {
            $this->company->setPostCode($company['post_code']);
        }
        if (isset($company['city_id'])) {
            $this->company->setCityId($company['city_id'] ? $company['city_id'] : null);
        }
        if (isset($company['country_id'])) {
            $this->company->setCountryId($company['country_id'] ? $company['country_id'] : null);
        }
        if (isset($company['contact_name'])) {
            $this->company->setContactName($company['contact_name']);
        }
        if (isset($company['email'])) {
            $this->company->setEmail($company['email']);
        }
        if (isset($company['head_phone_number'])) {
            $this->company->setHeadPhoneNumber($company['head_phone_number']);
        }
        if (isset($company['fax_number'])) {
            $this->company->setFaxNumber($company['fax_number']);
        }
        if (isset($company['website'])) {
            $this->company->setWebsite($company['website']);
        }
        if (isset($company['status_id'])) {
            $this->company->setStatusId($company['status_id'] ? $company['status_id'] : null);
        }
        if (isset($company['company_size_id'])) {
            $this->company->setCompanySizeId($company['company_size_id'] ? $company['company_size_id'] : null);
        }
        if (isset($company['company_type_id'])) {
            $this->company->setCompanyTypeId($company['company_type_id'] ? $company['company_type_id'] : null);
        }
        if (isset($company['customer_type_id'])) {
            $this->company->setCustomerTypeId($company['customer_type_id'] ? $company['customer_type_id'] : null);
        }
        if (isset($company['invoice_method_id'])) {
            $this->company->setInvoiceMethodId($company['invoice_method_id'] ? $company['invoice_method_id'] : null);
        }
        if (isset($company['agent_company_id'])) {
            $this->company->setAgentCompanyId($company['agent_company_id'] ? $company['agent_company_id'] : null);
        }
        if (isset($company['credit_limit'])) {
            $this->company->setCreditLimit($company['credit_limit'] ? $company['credit_limit'] : null);
        }
        if (isset($company['registration_date'])) {
            if ($company['registration_date']) {
                try {
                    $dateFormat = new sfDateFormat($this->getUser()->getCulture());
                    if (!is_array($company['registration_date'])) {
                        $value = $dateFormat->format($company['registration_date'], 'I', $dateFormat->getInputPattern('g'));
                    } else {
                        $value_array = $company['registration_date'];
                        $value = $value_array['year'] . '-' . $value_array['month'] . '-' . $value_array['day'] . (isset($value_array['hour']) ? ' ' . $value_array['hour'] . ':' . $value_array['minute'] . (isset($value_array['second']) ? ':' . $value_array['second'] : '') : '');
                    }
                    $this->company->setRegistrationDate($value);
                } catch (sfException $e) {
                    // not a date
                }
            } else {
                $this->company->setRegistrationDate(null);
            }
        }
        /*if (isset($company['created_at'])) {
            if ($company['created_at']) {
                try {
                    $dateFormat = new sfDateFormat($this->getUser()->getCulture());
                    if (!is_array($company['created_at'])) {
                        $value = $dateFormat->format($company['created_at'], 'I', $dateFormat->getInputPattern('g'));
                    } else {
                        $value_array = $company['created_at'];
                        $value = $value_array['year'] . '-' . $value_array['month'] . '-' . $value_array['day'] . (isset($value_array['hour']) ? ' ' . $value_array['hour'] . ':' . $value_array['minute'] . (isset($value_array['second']) ? ':' . $value_array['second'] : '') : '');
                    }
                    $this->company->setCreatedAt($value);
                } catch (sfException $e) {
                    // not a date
                }
            } else {
                $this->company->setCreatedAt(null);
            }
        }*/
        $currentFile = sfConfig::get('sf_upload_dir') . "//" . $this->company->getFilePath();
        if (!$this->getRequest()->hasErrors() && isset($company['file_path_remove'])) {
            $this->company->setFilePath('');
            if (is_file($currentFile)) {
                unlink($currentFile);
            }
        }

        if (!$this->getRequest()->hasErrors() && $this->getRequest()->getFileSize('company[file_path]')) {
            $fileName = md5($this->getRequest()->getFileName('company[file_path]') . time() . rand(0, 99999));
            $ext = $this->getRequest()->getFileExtension('company[file_path]');
            if (is_file($currentFile)) {
                unlink($currentFile);
            }
            $this->getRequest()->moveFile('company[file_path]', sfConfig::get('sf_upload_dir') . "//" . $fileName . $ext);
            $this->company->setFilePath($fileName . $ext);
        }

          if (isset($company['comments']))
    {
      $this->company->setComments($company['comments']);
    }
    }

    protected function getCompanyOrCreate($id = 'id') {
        if ($this->getRequestParameter($id) === ''
                || $this->getRequestParameter($id) === null) {
            $company = new Company();
        } else {
            $company = CompanyPeer::retrieveByPk($this->getRequestParameter($id));

            $this->forward404Unless($company);
        }

        return $company;
    }

    protected function processFilters() {
        if ($this->getRequest()->hasParameter('filter')) {
            $this->getUser()->getAttributeHolder()->removeNamespace('sf_admin/company/filters');

            $filters = $this->getRequestParameter('filters');
            if (is_array($filters)) {
                $this->getUser()->getAttributeHolder()->removeNamespace('sf_admin/company');
                $this->getUser()->getAttributeHolder()->removeNamespace('sf_admin/company/filters');
                $this->getUser()->getAttributeHolder()->add($filters, 'sf_admin/company/filters');
            }
        }
    }

    protected function processSort() {
        if ($this->getRequestParameter('sort')) {
            $this->getUser()->setAttribute('sort', $this->getRequestParameter('sort'), 'sf_admin/company/sort');
            $this->getUser()->setAttribute('type', $this->getRequestParameter('type', 'asc'), 'sf_admin/company/sort');
        }

        if (!$this->getUser()->getAttribute('sort', null, 'sf_admin/company/sort')) {
            
        }
    }

    protected function addFiltersCriteria($c) {
        if (isset($this->filters['company_name_is_empty'])) {
            $criterion = $c->getNewCriterion(CompanyPeer::COMPANY_NAME, '');
            $criterion->addOr($c->getNewCriterion(CompanyPeer::COMPANY_NAME, null, Criteria::ISNULL));
            $c->add($criterion);
        } else if (isset($this->filters['id']) && $this->filters['id'] !== '') {
            $c->add(CompanyPeer::ID, $this->filters['id']);
        }
        if (isset($this->filters['vat_no_is_empty'])) {
            $criterion = $c->getNewCriterion(CompanyPeer::VAT_NO, '');
            $criterion->addOr($c->getNewCriterion(CompanyPeer::VAT_NO, null, Criteria::ISNULL));
            $c->add($criterion);
        } else if (isset($this->filters['vat_no']) && $this->filters['vat_no'] !== '') {
            $c->add(CompanyPeer::VAT_NO, $this->filters['vat_no']);
        }
    }

    protected function addSortCriteria($c) {
        if ($sort_column = $this->getUser()->getAttribute('sort', null, 'sf_admin/company/sort')) {
            // camelize lower case to be able to compare with BasePeer::TYPE_PHPNAME translate field name
            $sort_column = CompanyPeer::translateFieldName(sfInflector::camelize(strtolower($sort_column)), BasePeer::TYPE_PHPNAME, BasePeer::TYPE_COLNAME);
            if ($this->getUser()->getAttribute('type', null, 'sf_admin/company/sort') == 'asc') {

                $c->addAscendingOrderByColumn($sort_column);
            } else {
                $c->addDescendingOrderByColumn($sort_column);
            }
        }
    }

    protected function getLabels() {
        return array(
            'company{name}' => 'Name:',
            'company{vat_no}' => 'Vat no:',
            'company{password}' => 'Password:',
            'company{address}' => 'Address:',
            'company{post_code}' => 'Post code:',
            'company{city_id}' => 'City:',
            'company{country_id}' => 'Country:',
            'company{contact_name}' => 'Contact name:',
            'company{email}' => 'Email:',
            'company{head_phone_number}' => 'Head phone number:',
            'company{fax_number}' => 'Fax number:',
            'company{website}' => 'Website:',
            'company{status_id}' => 'Status:',
            'company{company_size_id}' => 'Company size:',
            'company{company_type_id}' => 'Company type:',
            'company{customer_type_id}' => 'Customer type:',
            'company{invoice_method_id}' => 'Invoice method:',
            'company{agent_company_id}' => 'Agent company:',
            'company{registration_date}' => 'Registration date:',
            'company{created_at}' => 'Created at:',
            'company{file_path}' => 'Registration Doc:',
            'company{credit_limit}' => 'Credit Limit:',
                'company{comments}' => 'Comments:',
        );
    }

    public function executeView($request) {
        $this->company = CompanyPeer::retrieveByPK($request->getParameter('id'));
        $this->balance = CompanyEmployeActivation::getBalance($this->company);
        
        $ces = new Criteria();
        $ces->add(EmployeePeer::COMPANY_ID,$this->company->getId());
        $ces->addAnd(EmployeePeer::STATUS_ID,3);
        $this->count = EmployeePeer::doCount($ces);
    }

    public function executeUsage($request) {
        $this->company = CompanyPeer::retrieveByPK($request->getParameter('company_id'));
        $fromdate = $request->getParameter('startdate');
        $todate = $request->getParameter('enddate');
        if ($fromdate!="" && $todate!="") {
            $this->fromdate = $fromdate;
            $this->todate = $todate;
        } else {
          //  $tomorrow1 = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
            $this->fromdate = date("Y-m-1");
          //  $tomorrow = mktime(0, 0, 0, date("m"), date("t") + 1, date("Y"));
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
        
        $ces = new Criteria();
        $employeeid = $request->getParameter('employee_id');
        if($employeeid !=""){
            $ces->add(EmployeePeer::ID,$employeeid);
        }
        $c = new Criteria();
        $c->add(TelintaAccountsPeer::I_CUSTOMER, $this->company->getICustomer());
        $c->addAnd(TelintaAccountsPeer::STATUS, 3);
        $this->telintaAccountObj = TelintaAccountsPeer::doSelect($c);
        
        $ces = new Criteria();
        $ces->add(EmployeePeer::COMPANY_ID,$this->company->getId());
        $ces->addAnd(EmployeePeer::STATUS_ID,3);
        $this->cnt = EmployeePeer::doCount($ces);
        if(EmployeePeer::doCount($ces)>0)  {
             $this->ems = EmployeePeer::doSelect($ces);
        }
    }

    /*public function executeRefill(sfWebRequest $request) {

        $c = new Criteria();
        $this->companys = CompanyPeer::doSelect($c);
        if ($request->isMethod('post')) {

            $company_id = $request->getParameter('company_id');
            $refill_amount = $request->getParameter('refill');

            $c1 = new Criteria();
            $c1->addAnd(CompanyPeer::ID, $company_id);
            $this->company = CompanyPeer::doSelectOne($c1);
            $companyCVR = $this->company->getVatNo();

            $transaction = new CompanyTransaction();
            $transaction->setAmount($refill_amount);
            $transaction->setCompanyId($company_id);
            $transaction->setExtraRefill($refill_amount);
            $transaction->setTransactionStatusId(1);
            $transaction->setPaymenttype(2); //Refill
            $transaction->setDescription('Company Refill');
            $transaction->save();

            if ($companyCVR != '') {
                CompanyEmployeActivation::recharge($this->company, $refill_amount);
                $transaction->setTransactionStatusId(3);
                $transaction->save();
                $this->getUser()->setFlash('message', 'B2B Company Refill Successfully');
                $this->redirect('company/paymenthistory');
            } else {

                $this->getUser()->setFlash('message', 'Please Select B2B Company');
            }
            //$telintaAddAccount='success=OK&Amount=$amount{$cust_info->{iso_4217}}';
            //parse_str($telintaAddAccount, $success);print_r($success);echo $success['success'];
        }
    }*/

    public function executePaymenthistory(sfWebRequest $request) {

        $ct = new Criteria();
        $ct->add(TransactionDescriptionPeer::ID, 10);
        $description = TransactionDescriptionPeer::doSelectOne($ct);

        $c = new Criteria();
        $companyid = $request->getParameter('company_id');
        $this->companyval = $companyid;
        $c->add(CompanyTransactionPeer::TRANSACTION_STATUS_ID, 3);
        $c->add(CompanyTransactionPeer::PAYMENTTYPE,10);
        //$c->add(CompanyTransactionPeer::DESCRIPTION, '%'.$description->getTitle().'%', Criteria::LIKE);
        //$c->add(CompanyTransactionPeer::DESCRIPTION, '%Company Refill%', Criteria::LIKE);


        if (isset($companyid) && $companyid != '') {
            $c->addAnd(CompanyTransactionPeer::COMPANY_ID, $companyid);
        }
        $c->addDescendingOrderByColumn(CompanyTransactionPeer::CREATED_AT);
        $this->transactions = CompanyTransactionPeer::doSelect($c);

        $ces = new Criteria();
        $ces->add(EmployeePeer::COMPANY_ID,$companyid);
        $ces->addAnd(EmployeePeer::STATUS_ID,3);
        $this->count = EmployeePeer::doCount($ces);
    }

    public function executeVat(sfWebRequest $request) {

        $c = new Criteria();
        $vat_no = $_POST['vat_no'];
        $c->add(CompanyPeer::VAT_NO, $vat_no);
        if (CompanyPeer::doSelectOne($c)) {

            echo "no";
        } else {
            echo "yes";
        }
    }

    public function executeIndexAll(sfWebRequest $request) {
        $c = new Criteria();
        $this->companies = CompanyPeer::doSelect($c);
    }

     public function executeEditCreditLimit(sfWebRequest $request) {
      $count=0;
      $count=count($request->getParameter('company_id'));
      $creditlimit=$request->getParameter('creditlimit');

        for($i=0; $i<$count; $i++){
            $id=$request->getParameter('company_id');

            $company = CompanyPeer::retrieveByPk($id[$i]);
            $oldcreditlimit=$company->getCreditLimit();
            $company->setCreditLimit($creditlimit);
            $company->save();
               $update_customer['i_customer']=$company->getICustomer();
            $update_customer['credit_limit']=($company->getCreditLimit()!='')?$company->getCreditLimit():'0';
          if(!CompanyEmployeActivation::updateCustomer($update_customer)){
               $company->setCreditLimit($oldcreditlimit);
            $company->save();
          }
        }

          $this->getUser()->setFlash('message', 'All Selected Companies Credit Limit is updated');
             $this->redirect('company/indexAll');
                return sfView::NONE;
    }
    public function executeCharge(sfWebRequest $request) {

        $c = new Criteria();
        $this->companys = CompanyPeer::doSelect($c);
        $ctd = new Criteria();
        $ctd->add(TransactionDescriptionPeer::TRANSACTION_TYPE_ID,2);  ///// for Charge 
        $ctd->addAnd(TransactionDescriptionPeer::TRANSACTION_SECTION_ID,1); ///// for Admin
        $ctd->addAnd(TransactionDescriptionPeer::B2B,1);
        $this->descriptions = TransactionDescriptionPeer::doSelect($ctd);
        if ($request->isMethod('post')) {

            $company_id = $request->getParameter('company_id');
            $charge_amount = $request->getParameter('charge');
            $descid        = $request->getParameter('descid');
            ///Get transaction description
            $cd = new Criteria();
            $cd->add(TransactionDescriptionPeer::ID,$descid);
            $description = TransactionDescriptionPeer::doSelectOne($cd);
            
            $c1 = new Criteria();
            $c1->addAnd(CompanyPeer::ID, $company_id);
            $this->company = CompanyPeer::doSelectOne($c1);
            $companyCVR = $this->company->getVatNo();

            $transaction = new CompanyTransaction();
            $transaction->setAmount(-$charge_amount);
            $transaction->setCompanyId($company_id);
            $transaction->setExtraRefill(-$charge_amount);
            $transaction->setTransactionStatusId(1);
            $transaction->setPaymenttype($descid); //Charged Description id
            $transaction->setDescription($description->getTitle());
            $transaction->save();
            
            
            
            if ($companyCVR != '') {
                CompanyEmployeActivation::charge($this->company, $charge_amount,$description->getTitle());
                $transaction->setTransactionStatusId(3);
                $transaction->save();
                $this->getUser()->setFlash('chargemessage', 'B2B Company Charged Successfully');
                $this->redirect('company/index');
            } else {

                $this->getUser()->setFlash('message', 'Please Select B2B Company');
            }
            //$telintaAddAccount='success=OK&Amount=$amount{$cust_info->{iso_4217}}';
            //parse_str($telintaAddAccount, $success);print_r($success);echo $success['success'];
        }
    }

   public function executeInvoices(sfWebRequest $request)
    {
       $company_id = $request->getParameter('company_id');
       $this->company_id = $company_id;
       
       $billingduration = $request->getParameter('billingduration');
       $this->statusid = $request->getParameter('statusid');

       $cco = new Criteria();
       $cco->add(CompanyPeer::STATUS_ID,1);
       
       $ci = new Criteria();
       $ic = new Criteria();
       if($company_id){
          $ic->add(InvoicePeer::COMPANY_ID,$company_id);
          $ci->addAnd(InvoicePeer::COMPANY_ID,$company_id);
       }
       $companies = CompanyPeer::doSelect($cco);
       $this->companies = $companies;
       $ic->addGroupByColumn(InvoicePeer::BILLING_STARTING_DATE);
       $ic->addDescendingOrderByColumn(InvoicePeer::BILLING_STARTING_DATE);

       

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
   public function executeRefill($request){
         //$id = $request->getParameter('id');

         /*if($id!=''){
             $invoice = new Criteria();
             $invoice->add(InvoicePeer::ID, $id);
             $this->invoiceSelect = InvoicePeer::doSelectOne($invoice);
         }*/

         $c = new Criteria();
         $this->company = CompanyPeer::doSelect($c);

         if ($request->isMethod('post')) {
             $company_id=$request->getParameter('company_id');
             $invoice_id = $request->getParameter('invoice_id');
             $recharge = $request->getParameter('refill');
             $start_date= $request->getParameter('startdate');
             $refill=$recharge+($recharge* sfConfig::get('app_vat_percentage'));

             //$recharge=($invoice_id!='')?$recharge:$refill;
             $company = CompanyPeer::retrieveByPk($company_id);

             $ct = new Criteria();
             //($invoice_id!='')?$ct->add(TransactionDescriptionPeer::ID, 9):$ct->add(TransactionDescriptionPeer::ID, 10);
             $ct->add(TransactionDescriptionPeer::ID, 10);
             $description = TransactionDescriptionPeer::doSelectOne($ct);

             if(CompanyEmployeActivation::recharge($company, $recharge, $description->getTitle()."(Airtime)")){
                 /*if($invoice_id!=''){
                     $ci = new Criteria();
                     $ci->add(InvoicePeer::ID, $invoice_id);
                     $invoices = InvoicePeer::doSelectone($ci);
                     $payment=$invoices->getTotalpayment();
                     $paid=$invoices->getPaidAmount();
                     $invoice_no=$invoices->getInvoiceNumber();
                     $total_refill=$refill+$paid;
                     $net_amount=$payment-$total_refill;

                     if($refill>=$payment){
                         $invoices->setInvoiceStatusId('2');
                     }else{
                         $invoices->setInvoiceStatusId('5');
                     }
                     $invoices->setPaidAmount($total_refill);
                     $invoices->setNetPayment($net_amount);
                     $invoices->setPaidDatetime($start_date);
                     $invoices->save();
                 }*/

                    //$invoice_no=($invoice_no!='')?$invoice_no:'';
                    $cc = new CompanyTransaction();
                    $cc->setCompanyId($company_id);
                    $cc->setAmount($refill);
                    $cc->setExtraRefill($recharge);
                    //$cc->setInvoiceNo($invoice_no);
                    $cc->setPaymentType($description->getId());//Refill Transaction Description id
                    $cc->setDescription($description->getTitle());
                    $cc->setTransactionStatusId('3');
                    //$cc->setPaidDate($start_date);
                    $cc->save();
                    //$transaction = $cc;
                    $this->getUser()->setFlash('message', 'Record has been added Successfully');
             }else{
                    $this->getUser()->setFlash('message', 'Record has not been added Successfully');
             }
               // emailLib::sendPaymentReceipt($transaction);
                $this->redirect('company/paymenthistory?company_id='.$company_id);
         }
    }

    public function executeInvoice($request){
         $company_id = $request->getParameter('company_id');
         $c = new Criteria();
         $c->add(InvoicePeer::COMPANY_ID, $company_id);
         $c->add(InvoicePeer::INVOICE_STATUS_ID, 2,  Criteria::NOT_EQUAL);
         $c->addDescendingOrderByColumn(InvoicePeer::INVOICE_NUMBER);
         $this->invoice = InvoicePeer::doSelect($c);

    }

    public function executeAmount($request){
         $invoice_id = $request->getParameter('invoice_id');
         $c = new Criteria();
         $c->add(InvoicePeer::ID, $invoice_id);
         $this->amount = InvoicePeer::doSelectOne($c);

    }

        public function executeShowReceipt (sfWebRequest $request) {
        //call Culture Method For Get Current Set Culture - Against Feature# 6.1 --- 02/28/11
        changeLanguageCulture::languageCulture($request, $this);
        $transaction_id = $request->getParameter('tid');
        $transaction = CompanyTransactionPeer::retrieveByPK($transaction_id);

        $this->renderPartial('company/refill_receipt', array(
            'company' => CompanyPeer::retrieveByPK($transaction->getCompanyId()),
            'transaction' => $transaction,
            'vat' => sfConfig::get('app_vat_percentage'),
        ));

        return sfView::NONE;
    }

}
