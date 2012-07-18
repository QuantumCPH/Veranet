<?php
use_helper('I18N');
use_helper('Number');
?>
<style>
	p {
		margin: 8px auto;
	}

	table.receipt {
		width: 600px;
		//font-family: arial;
		//font-size: .7em;

		border: 2px solid #ccc;
	}

	table.receipt td, table.receipt th {
		padding:5px;
	}

	table.receipt th {
		text-align: left;
	}

	table.receipt .payer_details {
		padding: 10px 0;
	}

	table.receipt .receipt_header, table.receipt .order_summary_header {
		font-weight: bold;
		text-transform: uppercase;
	}

	table.receipt .footer
	{
		font-weight: bold;
	}
</style>
<?php
$wrap_content  = isset($wrap)?$wrap:false;

//wrap_content also tells  wheather its a refill or
//a product order. we wrap the receipt with extra
// text only if its a product order.

 ?>

<table class="receipt" cellspacing="0" width="600px">
<tr bgcolor="#CCCCCC" class="receipt_header">
    <td colspan="4"> <?php echo sfConfig::get('app_site_title')?> 
    </td>
  </tr>
  <tr>
  <td colspan="4" class="payer_summary">
	<?php echo sfConfig::get('app_site_title')?><br />
        <?php echo sfConfig::get('app_postal_address_top');?>       
	
	<br />
  </td>
  </tr>
  <tr bgcolor="#CCCCCC" class="receipt_header">
    <th colspan="3"><?php echo __('Order Receipt') ?></th>
    <th><?php echo __('Order No.') ?> <?php echo $agent_order->getId() ?></th>
  </tr>

  <tr>
    <td colspan="4" class="payer_summary">
      <?php echo sprintf("%s ", $agent->getName())?><br/>
      <?php echo $agent->getAddress() ?><br/>
      <?php echo sprintf('%s %s', $agent->getCity(), $agent->getPostCode()) ?>
      <?php /*$eC = new Criteria();
	  $eC->add(EnableCountryPeer::ID, $agent->getCountryId());
	  $eC = EnableCountryPeer::doSelectOne($eC);
	  echo $eC->getName(); */?>


      <br /><br />
      <?php echo __('Phone Number') ?>: <br />
      <?php echo $agent->getHeadPhoneNumber() ?><br />
       <?php if($agent_order->getOrderDescription()){
               $c = new Criteria();
                $c->add(TransactionDescriptionPeer::ID,$agent_order->getOrderDescription());
                $transaction_desc = TransactionDescriptionPeer::doSelectOne($c);
                echo $transaction_desc->getTitle();
           } ?>
      
    </td>
  </tr>
  <tr class="order_summary_header" bgcolor="#CCCCCC">
    <td><?php echo __('Date') ?></td>
    <td><?php //echo __('Description') ?></td>
    <td><?php echo __('Quantity') ?></td>
    <td align="right" style="padding-right:28px;"><?php echo __('Amount') ?></td>
  </tr>
  <tr>
    <td><?php echo $agent_order->getCreatedAt('d-m-Y') ?></td>
    <td>
        
    </td>
    <td>1<?php //echo $agent_order->getQuantity() ?></td>
    <td align="right" style="padding-right:28px;"><?php echo number_format($subtotal = $agent_order->getAmount(),2) //($order->getProduct()->getPrice() - $order->getProduct()->getPrice()*.2) * $order->getQuantity()) ?><?php echo sfConfig::get('app_currency_code');?></td>
  </tr>
  <tr>
  	<td colspan="4" style="border-bottom: 2px solid #c0c0c0;">&nbsp;</td>
  </tr>
  <tr class="footer">
    <td>&nbsp;</td>
    <td><?php echo __('Subtotal') ?></td>
    <td>&nbsp;</td>
    <td align="right" style="padding-right:28px;"><?php echo number_format($subtotal,2) ?><?php echo sfConfig::get('app_currency_code');?></td>
  </tr>
  <tr class="footer">
    <td>&nbsp;</td>
    <td><?php echo __('VAT') ?></td>
    <td>&nbsp;</td>
    <td align="right" style="padding-right:28px;"><?php echo number_format(0,2) ?><?php echo sfConfig::get('app_currency_code');?></td>
  </tr>
  <tr class="footer">
    <td>&nbsp;</td>
    <td><?php echo __('Total') ?></td>
    <td>&nbsp;</td>
    <td align="right" style="padding-right:28px;"><?php echo number_format($agent_order->getAmount(),2) ?><?php echo sfConfig::get('app_currency_code');?></td>
  </tr>
</table>
<?php if($wrap_content): ?>
<br />
<p>
<?php
	$c = new  Criteria();
	$c->add(GlobalSettingPeer::NAME, 'expected_delivery_time_agent_order');

	$global_setting_expected_delivery = GlobalSettingPeer::doSelectOne($c);

	if ($global_setting_expected_delivery)
		$expected_delivery = $global_setting_expected_delivery->getValue();
	else
		$expected_delivery = "3 business days";
?>
<p>
	<?php echo __('You will receive your package within %1%.', array('%1%'=>$expected_delivery)) ?>
</p>
<?php endif; ?>
<p style="font-weight: bold;">
	<?php echo __('If you have any inquiries please contact %1% Customer Support.',array('%1%' => sfConfig::get('app_site_title'))); ?>
        <br><?php echo __('E-mail') ?>:&nbsp;
	<a href="mailto:<?php echo sfConfig::get('app_support_email_id');?>"><?php echo sfConfig::get('app_support_email_id');?></a>
        <br><?php echo __('Telephone') ?>:&nbsp;<?php echo sfConfig::get('app_phone_no');?>
</p>
<!--<p>
	<?php echo __('If you have any questions please feel free to contact our customer support center at '); ?>
	<a href="mailto:<?php echo sfConfig::get('app_support_email_id');?>"><?php echo sfConfig::get('app_support_email_id');?></a>
</p>

<p><?php echo __('Cheers') ?></p>

<p>
<?php echo __('Support') ?><br />
<?php echo sfConfig::get('app_site_title')?> 
</p>-->