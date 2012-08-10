<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<?php include_stylesheets_for_form($form) ?>
<?php include_javascripts_for_form($form) ?>
<?php
$relay_script_url = sfConfig::get('app_epay_relay_script_url');
 
$customer_form = new CustomerForm();
$customer_form->unsetAllExcept(array('auto_refill_amount', 'auto_refill_min_balance'));
?>

<script type="text/javascript">
	$(document).ready(function(){
		$("#quantity").blur(function(){
			if(isNaN($("#quantity").val()) || $("#quantity").val()<1)
			{
				
				$('#quantity').val(1);
				calc();
				
			}
			else
			{
				$('#quantity_decline').hide();
				$('#quantity_ok').show();
			}
		});
	
		$('#quantity').change(function(){
			calc();
		});
		
		/* control was later changed to drop down box
		$('#user_attr_3').blur(function(){
			if ( this.value<0 || this.value>400 || isNaN(this.value) )
				this.value = 0;
		});
		*/
		
		toggleAutoRefill();
	
	});
	
	function toggleAutoRefill()
	{
		document.getElementById('user_attr_2').disabled = ! document.getElementById('user_attr_1').checked;
		document.getElementById('user_attr_3').disabled = ! document.getElementById('user_attr_1').checked;
                if(document.getElementById('user_attr_1').checked){
                $("#autorefilop").html('<input type="hidden" name="maketicket" value="foo" />');
		}else{
                    
                     $("#autorefilop").html('');  
                }
	}
	
	function checkForm()
	{
	
		calc();
		
		var objForm = document.getElementById("payment");
		var valid = true;
		
		if(isNaN(objForm.amount.value) || objForm.amount.value <=0 )
		{

			valid = false;
			
		}
		
		if(isNaN(objForm.quantity.value) || objForm.quantity.value<1)
		{
			//if (valid) //still not declarted as invaid
			objForm.quanity.focus();
			$('#quantity_decline').show();
			valid = false;
		}
		else
			$('#quantity_ok').show();
		
		
		
		//if (!valid)
		//	alert('Please complete out the payment form.');
		
		return valid;
	}
	
	function calc()
	{
		
                var total = $('#total').val();
                att2= $('#user_attr_2').val();
                att3= $('#user_attr_3').val();
                var accepturlstr = "<?php echo url_for('@epay_accept_url', true);  ?>?user_attr_2="+att2+"&user_attr_3="+att3+"&lng=<?php echo $sf_user->getCulture() ?>&accept=yes&subscriptionid=1&orderid=<?php echo $order_id; ?>&amount="+total;
                 var callbackurlstr = "<?php echo url_for('@dibs_accept_url', true);  ?>?user_attr_2="+att2+"&user_attr_3="+att3+"&lng=<?php echo  $sf_user->getCulture() ?>&accept=yes&subscriptionid=3&orderid=<?php echo $order_id; ?>&amount="+total;
                $('#idaccepturl').val(accepturlstr);

                 if(document.getElementById('user_attr_1').checked){
                $('#idcallbackurl').val(callbackurlstr);
                 }else{
                     var callbackurlstrs = "<?php echo url_for('@dibs_accept_url', true);  ?>?accept=yes&subscriptionid=1&lng=<?php echo  $sf_user->getCulture() ?>&orderid=<?php echo $order_id; ?>&amount="+total;
                    $('#idcallbackurl').val(callbackurlstrs);
                 }
	}
	
	
	
	
</script>
 <?php
                $lang = sfConfig::get('app_language_symbol');
                //$this->lang = $lang;
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
                        $postalcharge =  $postalcharges->getCharges();
                    }else{
                        $postalcharge =  0;
                    }
                }


                ?>

<form action="https://payment.architrade.com/paymentweb/start.action"   method="post" id="payment" onsubmit="return checkForm()" target="_parent">
  <div class="left-col">
    <div class="split-form-sign-up">
      <div class="step-details"> <strong><?php echo __('Become a Customer') ?> <span class="inactive">- <?php echo __('Step 1') ?>: <?php echo __('Register') ?> </span><span class="active">- <?php echo __('Step 2') ?>: <?php echo __('Payment') ?></span></strong> </div>
      <div class="fl col">
          <p style="color: red; margin-bottom:1px; position: relative; top: -2px;">
	<?php
	if ($sf_user->hasFlash('error_payment')): ?>
	<?php echo $sf_user->getFlash('error_payment'); ?>
	<?php
        endif;?>&nbsp;</p>
          <ul>
            <!-- payment details -->
            <li>
              <label class="prodname"><?php echo $order->getProduct()->getName() ?> <?php echo __('Payment details') ?>:</label>
            </li>
            <li>
              <label> <?php echo __('Zapna Starter Package') ?> <br />
				<?php //echo __('Product price') ?> </label><label class="fr ac"><span class="product_price_span"><?php echo  number_format($order->getProduct()->getRegistrationFee(),2);?></span><?php echo sfConfig::get('app_currency_code')?><br /><span id="extra_refill_span"><?php //echo  number_format($order->getProduct()->getPrice(),2); ?></span><?php //echo sfConfig::get('app_currency_code')?></label><!--<input type="hidden" id="product_price" value="<?php  $product_price_vat = ($order->getProduct()->getRegistrationFee()+$postalcharge)*sfConfig::get('app_vat_percentage');$product_price = ($order->getProduct()->getPrice()+$order->getProduct()->getRegistrationFee());echo $product_price;	?>" />-->
              <input type="hidden" id="extra_refill" value="<?php $extra_refill = $order->getExtraRefill(); echo $extra_refill; ?>" />
            </li>
            <?php
            $error_quantity = false;;
            if($form['quantity']->hasError())
            	$error_quantity = true;
            ?>
             <?php if($error_quantity) { ?>
            <li class="error">
            	<?php echo $form['quantity']->renderError() ?>
            </li>
            <?php } ?>  
          
            <li style="display:none">
              <?php echo $form['quantity']->renderLabel() ?>
			  <?php echo $form['quantity'] ?>
			  <span id="quantity_ok" class="alert">
			  	<?php echo image_tag('../zerocall/images/ok.png', array('absolute'=>true)) ?>
			  </span>
			  <span id="quantity_decline" class="alert">
			  	<?php echo image_tag('../zerocall/images/decl.gif', array('absolute'=>true)) ?>
			  </span>
            </li>
            <li>
              <label>

              
              <?php echo __('Delivery charges') ?> <br />
              <?php echo __('VAT') ?> <!--(<?php echo sfConfig::get('app_vat')?>)--><br />
              <?php echo __('Total amount') ?>



              </label><input type="hidden" id="vat" value="<?php echo $product_price_vat; ?>" /><input type="hidden" id="postal" value="<?php  echo $postalcharge; ?>" /><label class="fr ac" > <?php echo  number_format($postalcharge,2);  ?><?php echo sfConfig::get('app_currency_code')?><br /><span id="vat_span">
                    <?php echo  number_format($product_price_vat,2); ?></span><?php echo sfConfig::get('app_currency_code')?><br /><?php $total = $product_price + $postalcharge + $product_price_vat ?><span id="total_span"><?php echo  number_format($total,2) ?></span><?php echo sfConfig::get('app_currency_code')?></label>
            </li>
	<li><input type="submit"  class="butonsigninsmall"  name="paybutan"  style="cursor: pointer;margin-left: 0px !important;" value="<?php echo __('Pay') ?>" /></li>  
          </ul>
        <!-- hidden fields -->
	<?php echo $form->renderHiddenFields() ?>
        <input type="hidden" name="merchant" value="90049676" />
        <input type="hidden" name="currency" value="941" />
        <input type="hidden" name="orderid" value="<?php echo $order_id; ?>" />
        <input type="hidden" name="amount" value="<?php echo $total*100 ?>" />
        <input type="hidden" name="calcfee" value="yes" />
        <input type="hidden" name="account" value="YTIP" />
        <input type="hidden" name="status" value="" />
        <input type="hidden" name="lang" value="en_US" />   
        <input type="hidden" name="test" value="yes" />
        <input type="hidden" name="cancelurl" value="<?php echo $cancel_url?>" />
        <input type="hidden" name="callbackurl" value="<?php echo $callback_url?>" />
        <input type="hidden" name="accepturl" value="<?php echo $accept_url?>" >
		
      </div>
      <div class="fr col">
          
        <ul style="display: none;">
            <!-- auto fill -->
            <li>
              <label><?php echo __('Auto refill details:') ?></label>
            </li>
            <li>
            <li>
            	<input type="checkbox" class="fl" style="width:20px;" onchange="toggleAutoRefill()" name="user_attr_1" id="user_attr_1" checked="checked" />
 				<label for="user_attr_1" style="padding-top:0; text-indent: 5px;"><?php echo __('I want to activate auto refill feature') ?></label>
            </li>
            <li id="user_attr_3_field">
                <label for="user_attr_3" style="margin-right: 90px;"><?php echo __('Auto refill minimum balance:') ?>&nbsp;</label>
			  <?php echo $customer_form['auto_refill_min_balance']->render(array(
			  										'name'=>'user_attr_3',
                                                                                                        'id'=>'user_attr_3',
			  										'style'=>'width: 80px;'
			  									)) 
                                  ?><?php echo sfConfig::get('app_currency_code')?>
            </li>
           <li id="user_attr_2_field">
              <label for="user_attr_2" style="margin-right: 90px;"><?php echo __('Auto refill amount:') ?></label>
     <?php echo $customer_form['auto_refill_amount']->render(array(
                                                            'name'=>'user_attr_2',
         'id'=>'user_attr_2',
         'style'=>'width:80;',
                                                                                                                                                                      'style'=>'width: 80px;'
                 ));  
     ?>
            </li>
            <li id="" style="border-style:solid;border-width:3px;width: 320px; padding-left: 10px;">
                <br /><b align="justfy">  <?php  echo __("%1% recommends to activate this service so you <br /> do not have to manually refill when your account<br /> balance runs low. 100 or 200%2% each  when the <br /> balances reaches 25 or 50%2% this facility is <br /> added to your account in minutes.",array('%1%'=>sfConfig::get('app_site_title'),'%2%'=>sfConfig::get('app_currency_code')))?></b>



                <br /><br />
                                
            </li>
            
        </ul>	       
      </div>
    </div>
  </div>
</form>

