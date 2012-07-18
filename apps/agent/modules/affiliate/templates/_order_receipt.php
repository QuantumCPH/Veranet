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
		
		border: 2px solid #ccc;
	}

	table.receipt td, table.receipt th {
		padding:5px;
                font-size: 14px;
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
	<p><?php echo __('To') ?>&nbsp;<?php echo $customer->getFirstName();?></p>

	<p>
	<?php echo __('Thank you for your order of <b>%1%</b>.', array('%1%'=>$order->getProduct()->getName())) ?>
	</p>

	<p>
	<?php echo __('Your goods will be shipped today. You should have delivery within two days. Your customer number is ');  echo $customer->getUniqueid();?>. <?php echo __(' There, you can use in your dealings with customer service'); ?></p>

	<p>
	<?php echo __('Do not hesitate to contact us if you have any questions.') ?>
	</p>
        <p>
            <a href="mailto:<?php echo sfConfig::get('app_support_email_id');?>"><?php echo sfConfig::get('app_support_email_id');?></a>
	</p>
        <p>
	<?php echo __('Yours sincerely,') ?>
	</p>
        <p>
	<?php echo __(sfConfig::get('app_site_title')) ?>
	</p>
	<br />
<?php endif; ?>
<table width="600px">
	<tr style="border:0px solid #fff">
		<td colspan="4" align="right" style="text-align:right; border:0px solid #fff"><?php echo image_tag(sfConfig::get('app_web_url').'images/logo.png',array('width' => '170'));?></td>
	</tr>
</table>
<table class="receipt" cellspacing="0" width="600px">
  <tr bgcolor="#CCCCCC" class="receipt_header">
    <th colspan="3"><?php echo __('Order Receipt')." (".$order->getProduct()->getName().")" ?></th>
    <th><?php echo __('Order No.') ?> <?php echo $order->getId() ?></th>
  </tr>

  <tr>
    <td colspan="4" class="payer_summary">
      <?php echo __('Customer Number') ?>   <?php echo $customer->getUniqueId(); ?><br/>
      <?php echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName())?><br/>
      <?php echo $customer->getAddress() ?><br/>
      <?php echo sprintf('%s %s', $customer->getPoBoxNumber(), $customer->getCity()) ?><br/>
      <?php
	  $eC = new Criteria();
	  $eC->add(EnableCountryPeer::ID, $customer->getCountryId());
	  $eC = EnableCountryPeer::doSelectOne($eC);
	//  echo $eC->getName();
	  ?>


      <br /><br />
      <?php echo __('Mobile Number') ?>: <br />
      <?php echo $customer->getMobileNumber() ?><br />

      <?php if($agent_name!=''){ echo __('Agent Name') ?>:  <?php echo $agent_name; } ?>
    </td>
  </tr>
  <tr class="order_summary_header" bgcolor="#CCCCCC">
    <td><?php echo __('Date') ?></td>
    <td><?php echo __('Description') ?></td>
    <td><?php echo __('Quantity') ?></td>
    <td align="right" style="padding-right: 65px;"><?php echo __('Amount') ?></td>
  </tr>
  <tr>
    <td><?php echo $order->getCreatedAt('d-m-Y') ?></td>
    <td>
    <?php
        echo __("Zapna Starter Package");

    ?>
	</td>
    <td><?php echo $order->getQuantity() ?></td>
    <td align="right" style="padding-right: 65px;"><?php echo number_format($order->getProduct()->getRegistrationFee(),2); ?><?php echo sfConfig::get('app_currency_code')?></td>
  </tr>
  <?php if($order->getProduct()->getPrice() > 0){?>
  <tr>
    <td></td>
    <td>
    <?php
         echo __("Product Price");

    ?>
	</td>
    <td><?php echo $order->getQuantity() ?></td>
    <td align="right" style="padding-right: 65px;"><?php echo number_format($order->getProduct()->getPrice(),2); ?><?php echo sfConfig::get('app_currency_code')?></td>
  </tr>
 <?php } ?> 
  <tr>
  	<td colspan="4" style="border-bottom: 2px solid #c0c0c0;">&nbsp;</td>
  </tr>
  <tr class="footer">
    <td>&nbsp;</td>
    <td><?php echo __('Subtotal') ?></td>
    <td>&nbsp;</td>
    <td align="right" style="padding-right: 65px;"><?php  $subTotal = $order->getProduct()->getPrice()+$order->getProduct()->getRegistrationFee();  echo number_format($subTotal,2) ?><?php echo sfConfig::get('app_currency_code')?></td>
  </tr>
  <tr class="footer">
    <td>&nbsp;</td>
<td><?php echo __('VAT') ?><!--     (<?php //echo $vat==0?'0%':sfConfig::get('app_vat') ?>)-->  </td>
    <td>&nbsp;</td>
    <td align="right" style="padding-right: 65px;"><?php echo  number_format($vat,2) ?><?php echo sfConfig::get('app_currency_code')?></td>
  </tr>
  <tr class="footer">
    <td>&nbsp;</td>
    <td><?php echo __('Total') ?></td>
    <td>&nbsp;</td>
    <td align="right" style="padding-right: 65px;"><?php echo number_format($subTotal+$vat,2) ?><?php echo sfConfig::get('app_currency_code')?></td>
  </tr>
  <tr>
  	<td colspan="4" style="border-bottom: 2px solid #c0c0c0;">&nbsp;</td>
  </tr>
  <tr class="footer">
    <td class="payer_summary" colspan="4" style="font-weight:normal; white-space: nowrap;">
    <?php echo __('%1%',array('%1%' => sfConfig::get('app_postal_address_bottom')));?> </td>
  </tr>
</table>
 <p>
	<?php echo __('If you have any questions please feel free to contact our customer support center at '); ?>
	<a href="mailto:<?php echo sfConfig::get('app_support_email_id');?>"><?php echo sfConfig::get('app_support_email_id');?></a>
</p>

<p><?php echo __('Cheers') ?></p>

<p>
<?php echo __('Support') ?><br />
<?php echo sfConfig::get('app_site_title');?>
</p>       