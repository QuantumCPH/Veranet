<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<?php include_partial('dashboard_header', array('customer'=> $customer, 'section'=>__('Refill') ) ) ?>
<?php 
		 //echo $res_cbf;
 if($customerBalance+$order->getExtraRefill() >= 250){   ?>
    <?php echo "<div class='alert_bar'>".__("Sorry! You Cant do payment as your balance will excede from 250%1%",array("%1%"=>sfConfig::get("app_currency_code"))).'</div>'; ?>
 <?php } ?>
<br />
<div class="left-col">

    <?php include_partial('navigation', array('selected' => 'refill', 'customer_id' => $customer->getId())) ?>
    <div class="split-form">
        <div style="width:500px;" class="refillproducts">
            <h2><?php echo __("%1% Refill Payment details:",array("%1%"=>sfConfig::get("app_site_title")));?></h2>
            <br/>
            <table width="234" cellpadding="5" cellspacing="0"> 
<tr>
                    <td width="118" height="30"><?php echo __('Refill amount');?></td>
          <td width="94" align="right"><?php echo __(number_format($refillamount=$product->getRegistrationFee(),2));echo sfConfig::get('app_currency_code');?></td>
              </tr>    
                    
               <tr>  
                    <td height="30"><?php echo __('VAT');?></td><td align="right"><?php echo __(number_format($refillvat=$product->getRegistrationFee() * sfConfig::get('app_vat_percentage'),2));echo sfConfig::get('app_currency_code');?></td>
               </tr>
               <tr class="refilltotal">
                    <td height="30"><?php echo __('Total');?></td><td align="right"><?php echo __(number_format($refillamount+$refillvat,2));echo sfConfig::get('app_currency_code');?></td>
                </tr>
            </table>
            <br />
            <p>
            <?php 
             $refillbonus=0; 
             if($product->getBonus() > 0):
            $refillbonus = $product->getBonus();
          ?>
            <?php 
               $refilltext = __('Airtime value refilled on your account %1%%2% PLUS %3%%2% = %4%%2%',array("%1%"=>number_format($refillamount,2),"%2%"=>sfConfig::get('app_currency_code'),"%3%"=>number_format($refillbonus,2),"%4%"=>number_format($refillamount+$refillbonus,2)));
            else:
               $refilltext = __('Airtime value refilled on your account %1%%2%',array("%1%"=>number_format($refillamount,2),"%2%"=>sfConfig::get('app_currency_code'))); 
            ?>
            <?php  endif;?>  
              <?php echo $refilltext;?>  
            </p>
            <br />
            <p>
            <?php echo __("The refill amount is valid for 180 days.")?>
            </p>
            
            <br/>
            <form method="post" action="https://www.moneybookers.com/app/payment.pl">
      <!--  <form method="post" action="<?php echo $target; ?>customer/sendRefilToPaypal">
            <input type="hidden" value="<?php echo $queryString; ?>" name="qstr" />-->
            <!-- hidden fields -->
                <input type="hidden" name="firstname" value="<?php echo $order->getCustomer()->getFirstName();?>"  />
                <input type="hidden" name="lastname" value="<?php echo $order->getCustomer()->getLastName();?>"  />
                <input type="hidden" name="pay_from_email" value="<?php echo $order->getCustomer()->getEmail();?>"  />
                <input type="hidden" name="date_of_birth" value="<?php echo date("dmY", strtotime($order->getCustomer()->getDateOfBirth()));?>"  />
                <input type="hidden" name="address" value="<?php echo $order->getCustomer()->getAddress();?>"  />
                <input type="hidden" name="city" value="<?php echo $order->getCustomer()->getCity();?>"  />
                <input type="hidden" name="postal_code" value="<?php echo $order->getCustomer()->getPoBoxNumber();?>"  />
<!--                    <input type="hidden" name="country" value="<?php echo $order->getCustomer()->getCountry();?>"  />-->

                <input type="hidden" name="pay_to_email" value="rs@zapna.com" />
                <input type="hidden" name="language" value="<?php echo sfConfig::get('app_language_symbol')?>" />
                <input type="hidden" name="amount" id="total" value="<?php echo $amount;?>" />
                <input type="hidden" name="currency" value="<?php echo sfConfig::get('app_currency_symbol')?>" />
                <input type="hidden" name="detail1_description" value="Order Id"  />
                <input type="hidden" name="detail1_text" value="<?php echo $order->getId();?>" />
<!--                <input type="hidden" name="return_url" value="http://veranet.zerocall.com/" />-->
                <input type="hidden" name="cancel_url" value="http://veranet.zerocall.com/b2c.php">
<!--                <input type="hidden" name="cancel_url" value="<?php echo $cancel_url;?>" />
                <input type="hidden" name="return_url" value="<?php echo $return_url;?>" />
                <input type="hidden" name="status_url" value="fu@zerocall.com" />-->
                <?php if($customerBalance+$order->getExtraRefill() < 250){ ?>
                <div style="margin-top:40px;">
                    <input type="submit" class="butonsigninsmall" name="button" style="width:101px;cursor: pointer;float: left; margin-left: 1px !important; margin-top: -5px;"  value="<?php echo __('Pay') ?>" />
                </div>
                <?php }?>
            </form>
        </div>
    </div>
</div>
  <?php include_partial('sidebar') ?>