<?php
require_once(sfConfig::get('sf_lib_dir') . '/emailLib.php');
/**
 * agent_company actions.
 *
 * @package    zapnacrm
 * @subpackage agent_company
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 5125 2007-09-16 00:53:55Z dwhittle $
 */
class agent_companyActions extends autoagent_companyActions
{
    public function executeCountrycity($request){

		$this->country_id = 2;
		$this->city_id = $request->getParameter('city_id');

		$c = new Criteria();
                $c->add(EnableCountryPeer::STATUS ,1);
		$c->addAscendingOrderByColumn('name');
		$Lcountries = EnableCountryPeer::doSelect($c);

		$c->add(CityPeer::COUNTRY_ID, $this->country_id);
		$Lcities = CityPeer::doSelect($c);

		$cities_List = '';
		foreach($Lcountries as $country)
		{
			$countries_List[''.$country->getId()] = $country->getName();
		}

		if(isset($Lcities)){

			foreach($Lcities as $city)
			{
				$cities_List[''.$city->getId()] = $city->getName();
			}
		}

		$this->cities_list = $cities_List;
		$this->countries_list = $countries_List;


		$this->setLayout(false);

    }

    public function executeAddUser($request){
        $this->redirect('agent_user/addUser?id='.$request->getParameter('id'));
    }

    public function executeView($request){
        $this->agent_company = AgentCompanyPeer::retrieveByPk($request->getParameter('id'));
    }


	public function executeNewsUpdate(sfWebRequest $request)
  {
     $this->redirectUnless($this->getUser()->isAuthenticated(), "@homepage");
     
     $date='';
     $sdate= '';

     //echo "outside if";
     if($request->getParameter('message') and $request->getParameter('heading'))
     {

        $message=$request->getParameter('message');
        $heading=$request->getParameter('heading');

        //getting start date
        $day=$request->getParameter('day');
        $month=$request->getParameter('month');
        $year=$request->getParameter('year');

        //getting end date
        
         $eday=$request->getParameter('eday');        
         $emonth=$request->getParameter('emonth');
         $eyear=$request->getParameter('eyear');
        
	     $news  = new Newupdate();

         $date_starting = new DateTime();
         date_date_set($date_starting, $year, $month, $day);
         $news->setStartingDate( date('Y-m-d H:m:s',$date_starting->getTimestamp()));
         //echo date('Y-m-d H:m:s',$date_starting->getTimestamp());

         $date_ending = new DateTime();
         date_date_set($date_ending, $eyear, $emonth, $eday);

         $news->setExpireDate( date('Y-m-d H:m:s',$date_ending->getTimestamp()));
        // echo date('Y-m-d H:m:s',$date_ending->getTimestamp());

       
        $news->setMessage($message);
        $news->setHeading($heading);
        
//      
//        
//        $update->setExpireDate($sdate);
       if ($news->save())
		$this->redirect('agent_company/newsList');

     }
  }

  public function executeNewsList(sfWebRequest $request)
  {
	  $c=new Criteria();
      $this->messages= NewupdatePeer::doSelect($c);
  }

public function executeNewsDelete(sfWebRequest $request)
  {
           
          
         // return sfView::NONE;
          if($request->getMethod('post'))
          {


            $id= $request->getParameter('id');
            $value= $request->getParameter('value');
            
                //echo $id."<br />";
                //echo $value."<br />";
                $cid=new Criteria();
                $cid->add(NewupdatePeer::ID ,$id);
                $this->view=NewupdatePeer::doDelete($id , null);
                

				$this->redirect('agent_company/newsList');
            
          }
          
            
  }

public function executeNewsEdit(sfWebRequest $request)
{
   $this->redirectUnless($this->getUser()->isAuthenticated(), "@homepage");
   
   
  //  echo "here";


     $id= $request->getParameter('id');
    // echo $id;
     $value= $request->getParameter('value');
     
     $this->message=NewupdatePeer::retrieveByPK($id);
     
     




     $months = array(1=>'January',2=>'February',3=>'March',4=>'April',5=>'May',6=>'June',7=>'July',8=>'August',9=>'September',10=>'October',11=>'November',12=>'December');
     $this->months = $months;
     $this->id =$id;
           
    $this->count==0;


     if($value=="update")
     {
        $update=NewupdatePeer::retrieveByPK($id);
          
        $message=$request->getParameter('message');
        $heading=$request->getParameter('heading');


        //getting start date
        $day=$request->getParameter('day');
        $month=$request->getParameter('month');
        $year=$request->getParameter('year');


        //getting end date


//         $eday=$request->getParameter('eday');
//         $emonth=$request->getParameter('emonth');
//         $eyear=$request->getParameter('eyear');






         $date_starting = new DateTime();
         date_date_set($date_starting, $year, $month, $day);
         $update->setStartingDate( date('Y-m-d H:m:s',$date_starting->getTimestamp()));
         //echo date('Y-m-d H:m:s',$date_starting->getTimestamp());


//         $date_ending = new DateTime();
//         date_date_set($date_ending, $eyear, $emonth, $eday);


        // $update->setExpireDate( date('Y-m-d H:m:s',$date_ending->getTimestamp()));
        // echo date('Y-m-d H:m:s',$date_ending->getTimestamp());




        $update->setMessage($message);
        $update->setHeading($heading);


//
//
//        $update->setExpireDate($sdate);
       $update->save();         

	   $this->redirect('agent_company/newsList');
     }
     
    //return sfView::NONE;


}
public function executeRefilAgentCompany($request){


    $agentId = $request->getParameter('agent_company_id');
    $amount = $request->getParameter('refill_amount');
    $transaction_desc_id = $request->getParameter('transaction_description');
    
    $c = new Criteria();
    $agent_order = new AgentOrder();
    $agent_order->setAgentCompanyId($agentId);
    $agent_order->setStatus('1');
    $agent_order->save();

    $agent_order->setAgentOrderId('a0' . $agent_order->getId());
    $agent_order->save();

    $order_id =  $agent_order->getAgentOrderId();
    

if ($order_id and $amount) {
    $c = new Criteria();
    $c->add(AgentOrderPeer::AGENT_ORDER_ID, $order_id);
    $c->add(AgentOrderPeer::STATUS, 1);
    $agent_order = AgentOrderPeer::doSelectOne($c);
    $agent_order->setAmount($amount);
    $agent_order->setOrderDescription($transaction_desc_id);
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
    $aph->setExpeneseType(9);
    $aph->setOrderDescription($transaction_desc_id);
    $aph->setAmount($amount);
    $aph->setRemainingBalance($remainingbalance);
    $aph->save();

    $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('Your Selected Agent Company is Refill Successful %1%%2% is approved',array('%1%'=>$amount,'%2%'=>sfConfig::get('app_currency_code'))));

    emailLib::sendAdminRefilEmail($this->agent, $agent_order);


   //  emailLib::sendAdminRefillEmail($this->agent, $agent_order);

 
    $this->redirect('agent_company/agentCompanyPayment');

   }else{
    $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('Enter all required fields.'));
    $this->redirect('agent_company/selectCompany');        
   }
}
public function executeAgentCompanyPayment(sfWebrequest $request) {

        $agentId=0;
        $c = new Criteria();
        $c->add(AgentCompanyPeer::IS_PREPAID,1);
        $Lcompanies = AgentCompanyPeer::doSelect($c);
        $this->Lcompanies=$Lcompanies;

        $ctd = new Criteria();        
        $this->transactionDesc = TransactionDescriptionPeer::doSelect($ctd);
        
        
        $refilltype = $request->getParameter('refilltype');
        $this->refilltype=$refilltype;
        $agentId = $request->getParameter('agent_company_id');
        $this->agentidd=$agentId;

        $ca = new Criteria();
        if($agentId>0){
            $ca->add(AgentOrderPeer::AGENT_COMPANY_ID,$agentId);
        }
        if($refilltype==0){

        }else{
              $ca->add(AgentOrderPeer::ORDER_DESCRIPTION,$refilltype);
        }
        

        $ca->addAnd(AgentOrderPeer::STATUS, 3);
        $ca->addDescendingOrderByColumn(AgentOrderPeer::CREATED_AT);
        $agent = AgentOrderPeer::doSelect($ca);

        if($agentId>0){
            $this->agent_company = AgentCompanyPeer::retrieveByPK($agentId);
        }
        $this->agents = $agent;
    }
    public function executePrintAgentReceipt(sfWebrequest $request) {
        $ca = new Criteria();
        $aoid = $request->getParameter('aoid');
        $agent_order = AgentOrderPeer::retrieveByPk($aoid);
        $this->agent = AgentCompanyPeer::retrieveByPk($agent_order->getAgentCompanyId());
        $this->aoid = $aoid;
        $this->agent_order = $agent_order;

        $this->setLayout(false);
    }
  public function executeSelectCompany($request){


        $c = new Criteria();
        $c->add(AgentCompanyPeer::IS_PREPAID,1);
        $Lcompanies = AgentCompanyPeer::doSelect($c);
        $this->Lcompanies=$Lcompanies;
        
        $ct = new Criteria();
        $ct->add(TransactionDescriptionPeer::TRANSACTION_TYPE_ID,1); // For refill
        $ct->addAnd(TransactionDescriptionPeer::TRANSACTION_SECTION_ID,1); // 1, Description is for Admin and 2, for  Agent
        $this->transactionDescriptions = TransactionDescriptionPeer::doSelect($ct);
        

     }
 
  public function executeChargeCompany($request){
        $c = new Criteria();
        $c->add(AgentCompanyPeer::IS_PREPAID,1);
        $Lcompanies = AgentCompanyPeer::doSelect($c);
        $this->Lcompanies=$Lcompanies;
        
        $ct = new Criteria();
        $ct->add(TransactionDescriptionPeer::TRANSACTION_TYPE_ID,2); // For charge
        $ct->addAnd(TransactionDescriptionPeer::TRANSACTION_SECTION_ID,1); // 1, Description is for Admin and 2, for  Agent
        $this->transactionDescriptions = TransactionDescriptionPeer::doSelect($ct);
    }
  
  public function executeChargeAgentCompany($request){


    $agentId = $request->getParameter('agent_company_id');
    $amount = $request->getParameter('charge_amount');
    $transaction_desc_id = $request->getParameter('transaction_description');
    
    $c = new Criteria();
    $agent_order = new AgentOrder();
    $agent_order->setAgentCompanyId($agentId);
    $agent_order->setStatus('1');
    $agent_order->save();

    $agent_order->setAgentOrderId('a0' . $agent_order->getId());
    $agent_order->save();

    $order_id =  $agent_order->getAgentOrderId();
    

  if ($order_id and $amount) {
    $c = new Criteria();
    $c->add(AgentOrderPeer::AGENT_ORDER_ID, $order_id);
    $c->add(AgentOrderPeer::STATUS, 1);
    $agent_order = AgentOrderPeer::doSelectOne($c);
    $agent_order->setAmount(-$amount);
    $agent_order->setOrderDescription($transaction_desc_id);
    $agent_order->setStatus(3);
    $agent_order->save();

    $agent = AgentCompanyPeer::retrieveByPK($agent_order->getAgentCompanyId());
    $agent->setBalance($agent->getBalance() - ($amount));
    $agent->save();
    $this->agent = $agent;

    $amount = $amount;
    $remainingbalance = $agent->getBalance();
    $aph = new AgentPaymentHistory();
    $aph->setAgentId($agent_order->getAgentCompanyId());
    $aph->setExpeneseType(9);
    $aph->setAmount(-$amount);
    $aph->setOrderDescription($transaction_desc_id);
    $aph->setRemainingBalance($remainingbalance);
    $aph->save();

    $this->getUser()->setFlash('message', 'Your Selected Agent Company is Charged Successfully ' . -$amount . sfConfig::get('app_currency_code') .' is approved');
 
    emailLib::sendAdminRefilEmail($this->agent, $agent_order);



    $this->redirect('agent_company/agentCompanyPayment');

   }else{
    $this->getUser()->setFlash('message', $this->getContext()->getI18N()->__('Enter all required fields.'));
    $this->redirect('agent_company/chargeCompany');        
   }
  }

}
