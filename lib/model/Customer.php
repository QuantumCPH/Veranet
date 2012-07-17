<?php

class Customer extends BaseCustomer
{
	public function save(PropelPDO $con = null)
	{
		
	    if (($this->isModified() && $this->isColumnModified(CustomerPeer::PASSWORD)) ||
	    	($this->isNew() && $this->getPassword())
	    	)
	    {
	    	$this->setPassword(sha1($this->getPassword()));
	    }
	    
	    parent::save($con);
	}
	
	public function registerFonet()
	{
		//Fonet number registration
	  	
	  	$country = $this->getCountry()->getName();
	  	$mobile_number = $this->getMobileNumber();
	  	$pin = substr('0000' . $this->getId(), -4);
	  	$description = 'reg for '. $this->getFirstName();
	  	$key = md5($country . $mobile_number . $pin . $description . 'itLK34gH5Dt6:-g#');
	  	
	  	$query_vars = array(
			'Cmd'=>'CallSaver_U',
			'Ctry'=>$country,
	  		'Ani'=>$mobile_number,
	  		'AniPin'=>$pin,
	  		'Key'=>$key,
	  		'Description'=>$description,
	  	);
	  	
	  	$url = 'https://www.fonet.dk/ZPN.php/?'.http_build_query($query_vars);
	
		if(BaseUtil::request_url($url)=='OK')
		{
			$this->setIsFonetSubscribed(true);
			$this->save();
			return true;
		}
		else
		{
			return false;
		}
	}
	
	public function unregisterFonet()
	{
		//Fonet number unregistration
	  	
	  	$country = $this->getCountry()->getName();
	  	$mobile_number = $this->getMobileNumber();
	  	$key = md5($country . $mobile_number . 'itLK34gH5Dt6:-g#');
	  	
	  	$query_vars = array(
			'Cmd'=>'CallSaver_D',
			'Ctry'=>$country,
	  		'Ani'=>$mobile_number,
	  		'Key'=>$key,
	  	);
	  	
	  	
	  	$url = 'https://www.fonet.dk/ZPN.php/?'.http_build_query($query_vars);
	
		if(BaseUtil::request_url($url)=='OK')
		{
			$this->setIsFonetSubscribed(true);
			$this->save();
			return true;
		}
		else
			return false;	
	}
	
	public function getProducts(){
		$c = new Criteria();
	  	$c->add(CustomerProductPeer::CUSTOMER_ID, $this->getId());
	  	$c->addJoin(ProductPeer::ID, CustomerProductPeer::PRODUCT_ID);
	  	
	  	return ProductPeer::doSelect($c);
	}
	
	public function getMobileNumberWithCallingCode()
	{
		return sprintf("%s%s", $this->getCountry()->getCallingCode(),
								BaseUtil::trimMobileNumber($this->getMobileNumber())
						);
	}
         public function getBalance()
        {

            $fonet=new Fonet();
            $balance = $fonet->getBalance($this ,true);
            if($balance)
               return $balance;
        }
        public function getNationalityTitle(){
           $nationality_title = ""; 
           $cn = new Criteria();
           $cn->add(NationalityPeer::ID,$this->getNationalityId());
           $cn->addAscendingOrderByColumn(NationalityPeer::TITLE);

           $nationality = NationalityPeer::doSelectOne($cn);
             if(NationalityPeer::doCount($cn)>0) $nationality_title = $nationality->getTitle();
             return $nationality_title;
        }
        
        public function getSimType(){
            $simTypeTitle ="";
            $cst = new Criteria();
            $cst->add(SimTypesPeer::ID,$this->getSimTypeId());
            $cst->addAscendingOrderByColumn(SimTypesPeer::TITLE);
            $simTypes = SimTypesPeer::doSelectOne($cst);
              if(SimTypesPeer::doCount($cst)>0) $simTypeTitle = $simTypes->getTitle();
            return $simTypeTitle;
        }
        
        public function getPreferredLanguage(){
            $planguage ="";
            $cpl = new Criteria();
            $cpl->add(PreferredLanguagesPeer::ID,$this->getPreferredLanguageId());
            $cpl->addAscendingOrderByColumn(PreferredLanguagesPeer::LANGUAGE);
            $languages = PreferredLanguagesPeer::doSelectOne($cpl);
              if(PreferredLanguagesPeer::doCount($cpl)>0) $planguage = $languages->getLanguage();
            return $planguage;
        }
        
        public function getProvinceName(){
            $provinceName ="";
            $cpr = new Criteria();
            $cpr->add(ProvincePeer::ID,$this->getProvinceId());
            $cpr->addAscendingOrderByColumn(ProvincePeer::PROVINCE);
            $province = ProvincePeer::doSelectOne($cpr);
              if(ProvincePeer::doCount($cpr)>0) $provinceName = $province->getProvince();
            return $provinceName;
        }
	    
}
