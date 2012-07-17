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
	<p><?php echo __('To') ?>&nbsp;<?php echo $recepient_name;//$customer->getFirstName();?></p>
	

	
	
	<p>
	<?php echo __('%1% has added 10.00 %2% of airtime to your account balance for inviting a friend to register as a %1% customer. Thank you.',array('%1%'=>sfConfig::get('app_site_title'), '%2%'=>sfConfig::get('app_currency_code'))); ?>
	</p>
        <p>
            <a href="mailto:<?php echo sfConfig::get('app_support_email_id');?>"><?php echo sfConfig::get('app_support_email_id');?></a>
	</p>
        <p>
	<?php echo __('Best regards,') ?>
	</p>
        <p>
	<?php echo __(sfConfig::get('app_site_title')) ?>
	</p>
	<br />
<?php endif; ?>
<table width="600px">
	<tr style="border:0px solid #fff">
		<td colspan="4" align="right" style="text-align:right; border:0px solid #fff"><?php echo image_tag(sfConfig::get('app_site_url').'images/logo.png',array('width' => '170'));?></td>
	</tr>
</table>

<table class="receipt" cellspacing="0" width="600px">
  <tr bgcolor="#CCCCCC" class="receipt_header">
    <th ><?php echo __('Order receipt') ?></th>
  
  </tr>
 <tr>
  <td><b><?php echo __('Receiver of bonus for inviting a friend') ?>:</b> <?php echo $recepient_name; ?></td>
    </tr>
   <tr>
  <td><b><?php echo __('Registered friend') ?>:</b> <?php echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName())?></td>
   </tr>
</table>
    