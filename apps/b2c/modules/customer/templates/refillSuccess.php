<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<?php include_partial('dashboard_header', array('customer'=> $customer, 'section'=>__('Refill') ) ) ?>
<?php
$customer_form = new CustomerForm($customer);
$customer_form->unsetAllExcept(array('auto_refill_amount', 'auto_refill_min_balance'));

$is_auto_refill_activated = $customer_form->getObject()->getAutoRefillAmount()!=null;
?>
 <?php

        $part2 = rand (99,99999);
        $part3 = date("s");
        $randomOrderId = $order->getId().$part2.$part3;
           ?>
<script type="text/javascript">



	$(document).ready(function(){
		
		$('#frmarchitrade').submit(function() {
			user_attr_2 = jQuery("#user_attr_2 option:selected").val();
			user_attr_3 = jQuery("#user_attr_3 option:selected").val();
    		jQuery('#idcallbackURLauto').val(jQuery('#idcallbackURLauto').val()+"&user_attr_2="+user_attr_2+"&user_attr_3="+user_attr_3);
  return true;
});


$('#refill').submit(function() {
			extra_refill = jQuery("#extra_refill option:selected").val();
			extra_refill = parseInt(extra_refill);
                        jQuery('#idcallbackurl').val(jQuery('#callbackurlfixed').val()+extra_refill);
			jQuery('#total').val(extra_refill);
  return true;
});

	
	});




</script>
<?php   
if($is_auto_refill_activated){  ?>  <div class="left-col">
	   <?php include_partial('navigation', array('selected'=>'refill', 'customer_id'=>$customer->getId())) ?>
    
    <div style="width:500px;">
    
    
     <div  style="width:500px;clear:both;"> <br/> <br/>
   <b>  <?php echo __("Automatic replenishment is")?>:<span style="text-decoration:underline"> <?php echo __('Active')?></span>
   </b>
     
     <br/> <br/>


<?php echo __('If your credit card that is registered for the service of any reason is no longer active, you can disable service and then activate it again with another credit card.');?>
 
     
      </div> <br/> 
               <br/>
     
    <div  style="width:500px;">
    <div style="float:left;width:250px;font-weight:bold;"> <?php echo __('You have selected automatic replenishment when the pot is below:');?> </div>
    <div  style="margin-left: 20px;float:left;width:100px;font-weight:bold;"> <?php echo   $customer_form->getObject()->getAutoRefillMinBalance() ?> <?php echo sfConfig::get('app_currency_code')?></div>
    <div  style="float:left;width:150px;"></div> 
    </div>
  
    <div  style="width:500px;clear:both;">
               <br />
    <div  style="float:left;width:250px;font-weight:bold; "><?php echo __('The pot is filled in with:');?></div>
    <div  style="margin-left: 20px;float:left;width:100px;font-weight:bold;">  <?php echo   $customer_form->getObject()->getAutoRefillAmount() ?> <?php echo sfConfig::get('app_currency_code')?></div>
    <div class="clr"></div><br />
    <div style="margin-top: 61px; text-align: left; width: 134px;">
    <form method="post" action="<?php echo $target; ?>customer/deActivateAutoRefill">
    <input type="hidden" name="customer_id" value="<?php echo   $customer_form->getObject()->getId() ?>" />
    <div class="clr"></div><br />
                <input type="submit" class="butonsigninsmall" name="button" style="cursor: pointer; margin-left: 0px !important; margin-top: -10px;"  value="<?php echo __('Disable') ?>" />
                </form>			
    </div>
    </div>
     
</div>
</div>
    <?php
}else{ ?>
	
	
  <div class="left-col">
     
    <?php include_partial('navigation', array('selected'=>'refill', 'customer_id'=>$customer->getId())) ?>
	<div class="split-form">
          <div style="width:500px;">
              <div style='display:none;'> 
                <div> <?php echo __('The most convenient way to fill the pot is to enable automatic refilling (below), then you do not need to worry about the pot running out. Especially important is such trip abroad where it can be difficult to fill in in any other way.');?><br /><br /></div>
                <div>     <b style="text-decoration:underline;"><?php echo __('Automatic replenishment');?></b> </div>
                 <br />
                <div>   <b><?php echo __('Automatic Replenishment is: Inactive');?></b></div>
              </div>  
      <div class="fl col">
      <div class="split-form">  
   <form action="https://payment.architrade.com/paymentweb/start.action" method="post" id="frmarchitrade" style="display: none;" >
  <input type="hidden" name="merchant" value="90049676" />
  <input type="hidden" name="amount" value="1" />
      <input type="hidden" name="customerid" value="<?php echo   $customer_form->getObject()->getId() ?>" />
  <input type="hidden" name="currency" value="978" />
  <input type="hidden" name="orderid" value="<?php echo $randomOrderId; ?>" />

    <input type="hidden" name="test" value="yes" />

   <input type="hidden" name="account" value="YTIP" />
  <input type="hidden" name="lang" value="de" />
  <input type="hidden" name="preauth" value="true">
  <input type="hidden" name="cancelurl" value="<?php echo $target; ?>customer/dashboard?lng=<?php echo  $sf_user->getCulture() ?>" />
  <input type="hidden" name="callbackurl" id="idcallbackURLauto" value="<?php echo $target; ?>customer/activateAutoRefill?customerid=<?php echo   $customer_form->getObject()->getId() ?>&lng=<?php echo  $sf_user->getCulture() ?>v" />
  <input type="hidden" name="accepturl" value="<?php echo $target; ?>customer/dashboard?lng=<?php echo  $sf_user->getCulture() ?>" />
 <div style="width:348px;float:left;">
        <ul style="width: 285px;float:none;clear:both;">
            <!-- auto fill -->
                       
           
           
            <li id="user_attr_3_field">
                <label for="user_attr_3" style="margin-right: 50px;"><?php echo __('Load automatically <br /> when the pot is below:') ?></label>
                &nbsp;
			  <?php echo $customer_form['auto_refill_min_balance']->render(array(
			  										'name'=>'user_attr_3',
			  										'style'=>'width: 80px;'
			  									)) 
			  ?>  <?php echo sfConfig::get('app_currency_code')?>
            </li>
            
            
            <li id="user_attr_2_field">
                 <label for="user_attr_2" style="margin-right: 50px;"><?php echo __('Auto refill amount:') ?></label>              
		 &nbsp; <?php echo $customer_form['auto_refill_amount']->render(array(
			  													'name'=>'user_attr_2',
                                                                                                                                'style'=>'width: 80px;'
			  												)); 
			  ?>  <?php echo sfConfig::get('app_currency_code')?>&nbsp;
            </li> 
        </ul>
            </div>
 
          <div style="float:left;"><input type="submit" class="butonsigninsmall" style="width:101px;margin-left:-13px !important;" name="button" value="<?php echo __('Enable') ?>" /></div>
  </form>
  </div>
  <form action="<?php echo $target;?>customer/refilTransaction" method="post" id="refill" target="_parent">
     <div style="width:510px;">
     <div  style="width:510px;float:left;"> 
          <div class="refillhead"><?php echo __('Manual filling:') ?></div>
          <p> <?php echo __('You can refill your %1% Account with the following amounts:',array("%1%"=>sfConfig::get('app_site_title')))?></p>
         <ul class="welcome">
         	<!-- customer product -->
	<?php   
                $bonus ="";
                foreach($refillProducts as $refill){ 
                    if($refill->getBonus()) $bonus = __('PLUS %1%%2%',array("%1%"=>number_format($refill->getBonus(),2),"%2%"=>sfConfig::get('app_currency_code')));
        ?>
            <li><?php   echo number_format($refill->getRegistrationFee(),2).sfConfig::get('app_currency_code'); echo __(" (airtime value: %1%%2% %3%)",array("%1%"=>number_format($refill->getRegistrationFee(),2),"%2%"=>sfConfig::get('app_currency_code'),"%3%"=>$bonus));
                    //"&nbsp;Bonus:".$refill->getBonus()."&nbsp;Total Including Vat:".(sfConfig::get('app_vat_percentage')+1)*$refill->getRegistrationFee();?></li>
        <?php
        }       
        ?>
         </ul><br clear="both" />
         <p><?php echo __("All amounts are excl. VAT (%1%).",array("%1%"=>sfConfig::get('app_vat')));?></p>
         <p><?php echo __("The value of airtime on your account balance cannot  exceed 250.00%1% at any moment in time. The refill amount is valid for 180 days.",array("%1%"=>sfConfig::get('app_currency_code')));?></p>
         <p>&nbsp;</p>
         <ul>
          	<!-- extra_refill -->
            <?php
            $error_extra_refill = false;;
            if($form['extra_refill']->hasError())
            	$error_extra_refill = true;
            ?>
            <?php if($error_extra_refill) { ?>
            <li class="error">
            	<?php echo $form['extra_refill']->renderError() ?>
            </li>
            <?php } ?>
            <li id="selectAmt" class="refilselect">
              <label for="extra_refill" ><?php echo __('Select amount to be refilled:') ?></label>
              <span style="margin-left:99px;"><?php echo $form['extra_refill']?></span>
            </li>

            <?php if($sf_user->hasFlash('error_message')): ?>
            <li class="error" style="white-space: normal;">
            	<?php echo $sf_user->getFlash('error_message'); ?>
            </li>
            <?php endif; ?>
          </ul><br clear="both" />
          <div style="margin-top:30px;"> 
                <input type="submit" class="butonsigninsmall" name="button" style="width:101px;cursor: pointer;float: left; margin-left: -5px !important; margin-top: -5px;"  value="<?php echo __('Refill') ?>" />
          </div>
        <!-- hidden fields -->
      
        
<!--        <input type="hidden" name="amount" id="total" value="" />
        
        <input type="hidden" name="cmd" value="_xclick" /> 
        <input type="hidden" name="no_note" value="1" />
        <input type="hidden" name="lc" value="<?php echo sfConfig::get('app_language_symbol')?>" />
        <input type="hidden" name="currency_code" value="<?php echo sfConfig::get('app_currency_symbol')?>" />
        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
        <input type="hidden" name="firstName" value="<?php echo $order->getCustomer()->getFirstName();?>"  />
        <input type="hidden" name="lastName" value="<?php echo $order->getCustomer()->getLastName();?>"  />
        <input type="hidden" name="payer_email" value="<?php echo $order->getCustomer()->getEmail();?>"  />-->
        <input type="hidden" name="item_number" value="<?php echo $order->getId();?>" />
<!--        <input type="hidden" name="rm" value="2" />        -->
                    </div>
          
        </div></form> 
       </div>
      
    </div><!-- end form-split -->
  </div><div style="clear:both"></div>
</div>
 <?php
}

?>

  <?php include_partial('sidebar') ?>
