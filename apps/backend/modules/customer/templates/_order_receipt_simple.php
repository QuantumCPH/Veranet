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

<?php if($wrap_content): ?>
	<p><?php echo __('Dear Customer') ?></p>

	<p>
	<?php echo __('Thank you for ordering <b>%1%</b> and becoming %2% Customer. We welcome you to a new and huge mobile world.', array('%1%'=>$order->getProduct()->getName(), '%2%'=> sfConfig::get('app_site_title'))) ?>
	</p>

	<p>
	<?php echo __('With <b>%1%</b>, you can easily call your friends and family for free.', array('%1%'=>$order->getProduct()->getName())) ?></p>

	<p>
	<?php echo __('Below is the receipt of the product indicated.') ?>
	</p>
	<br />
<?php endif; ?>
<table width="600px">
    <tr style="border:0px solid #fff">
 <td colspan="4" align="right" style="text-align:right; border:0px solid #fff"><?php echo image_tag(sfConfig::get('app_web_url').'images/logo.png');?></td>
    </tr>
</table>
<table class="receipt" cellspacing="0" width="600px">
  <tr bgcolor="#CCCCCC" class="receipt_header">
    <th colspan="3"><?php echo __('Order Receipt') ?>(
        <?php if ($order->getIsFirstOrder())
    {
		echo $order->getProduct()->getName() .
		'<br />['. $transaction->getDescription() .']';
    }
    else
    {
		echo $transaction->getDescription();
    }
    ?>
        )</th>
    <th><?php echo __('Order No.') ?> <?php echo $order->getId() ?></th>
  </tr>
  <tr>
    <td colspan="4" class="payer_summary">
      <?php echo __('Customer number') ?>   <?php echo $customer->getUniqueId(); ?><br/>
      <?php echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName())?><br/>
      <?php echo $customer->getAddress() ?><br/>
      <?php echo sprintf('%s %s', $customer->getPoBoxNumber(), $customer->getCity()) ?>
      <?php
	  /*$eC = new Criteria();
	  $eC->add(EnableCountryPeer::ID, $customer->getCountryId());
	  $eC = EnableCountryPeer::doSelectOne($eC);
	  echo $eC->getName();*/
	  //echo $customer->getCountry()->getName() ?>
      <br /><br />
      <?php echo __('Mobile Number') ?>: <br />
      <?php echo $customer->getMobileNumber() ?>   <br/>
      <?php //echo __('Paid Through'); ?> <?php //echo __('Agent'); ?>
    </td>
  </tr>
  <tr class="order_summary_header" bgcolor="#CCCCCC">
    <td><?php echo __('Date') ?></td>
    <td><?php echo __('Description') ?></td>
    <td><?php echo __('Quantity') ?></td>
    <td align="right" style="padding-right: 65px;"><?php echo __('Amount') ?><!--(<?php echo sfConfig::get('app_currency_code') ?>)--></td>
  </tr>
  <tr>
    <td><?php echo $order->getCreatedAt('d-m-Y') ?></td>
    <td>
    <?php if ($order->getIsFirstOrder())
    {
		echo $order->getProduct()->getName() .
		'<br />['. $transaction->getDescription() .']';
    }
    else
    {
		echo $transaction->getDescription();
    }
    ?>
	</td>
    <td><?php echo $order->getQuantity() ?></td>
    <td align="right" style="padding-right: 65px;"><?php echo number_format($subtotal = $transaction->getAmount()-$vat,2) //($order->getProduct()->getPrice() - $order->getProduct()->getPrice()*.2) * $order->getQuantity()) ?><?php echo sfConfig::get('app_currency_code');?></td>
  </tr>


  <tr>
  	<td colspan="4" style="border-bottom: 2px solid #c0c0c0;">&nbsp;</td>
  </tr>
  <tr class="footer">
    <td>&nbsp;</td>
    <td><?php echo __('Subtotal') ?></td>
    <td>&nbsp;</td>
    <td align="right" style="padding-right: 65px;"><?php echo number_format($subtotal,2) ?><?php echo sfConfig::get('app_currency_code');?> </td>
  </tr>
  <tr class="footer">
    <td>&nbsp;</td>
   <td><?php echo __('VAT') ?> <!-- (<?php echo $vat==0?'0%':sfConfig::get('app_vat') ?>)--></td>
    <td>&nbsp;</td>
    <td align="right" style="padding-right: 65px;"><?php echo number_format($vat,2) ?><?php echo sfConfig::get('app_currency_code');?> </td>
  </tr>
  <?php
  //echo $postalcharge.'ss';
  if(@$postalcharge && $order->getIsFirstOrder()){?>
  <tr class="footer">
    <td></td>
    <td>
    <?php
       echo __('Forsendelses omkostninger');
     ?>
    </td>
    <td><?php //echo $order->getQuantity() ?></td>
    <td><?php echo @$postalcharge ; ?></td>
  </tr>
  <?php } ?>
  <tr class="footer">
    <td>&nbsp;</td>
    <td><?php echo __('Total') ?></td>
    <td>&nbsp;</td>
    <td align="right" style="padding-right: 65px;"><?php if($postalcharge && $order->getIsFirstOrder()){ echo number_format($transaction->getAmount()+$postalcharge,2); }else{ echo number_format($transaction->getAmount(),2); } ?> <?php echo sfConfig::get('app_currency_code') ?></td>

  </tr>
  <tr>
  	<td colspan="4" style="border-bottom: 2px solid #c0c0c0;">&nbsp;</td>
  </tr>
  <tr class="footer">
    <td class="payer_summary" colspan="4" style="font-weight:normal; white-space: nowrap;">
    <?php echo __('%1%',array('%1%'=>sfConfig::get('app_postal_address_bottom')))?> </td>
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