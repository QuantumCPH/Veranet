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
                    <td height="30"><?php echo __('IVA');?></td><td align="right"><?php echo __(number_format($refillvat=$product->getRegistrationFee() * sfConfig::get('app_vat_percentage'),2));echo sfConfig::get('app_currency_code');?></td>
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
            <form method="post" action="<?php echo $target; ?>customer/sendRefilToPaypal">
                <input type="hidden" value="<?php echo $queryString; ?>" name="qstr" />
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