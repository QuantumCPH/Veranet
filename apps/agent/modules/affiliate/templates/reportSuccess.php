<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<style type="text/css">
	table {
		margin-bottom: 10px;
	}
	
	table.summary td {
		font-size: 1.2em;
		font-weight: normal;
	}
</style>
<link href="<?php echo sfConfig::get('app_web_url'); ?>css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo sfConfig::get('app_web_url'); ?>js/jquery.min.js"></script>
<script src="<?php echo sfConfig::get('app_web_url'); ?>js/jquery-ui.min.js"></script>
<script>
    jQuery(function() {

        jQuery( "#startdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
        jQuery( "#enddate" ).datepicker({ dateFormat: 'yy-mm-dd'});


    });
</script>
<div class="report_container">

    <table cellpadding="0" cellspacing="0" class="tbldatefilter" align="center">
        <tr><td><h1><?php echo __('Date Filter') ?></h1></td></tr>
        <tr>
            <td>
                <form action="" id="searchform" method="POST" name="searchform">
                    <div class="dateBox-pt">
                        <div class="formRow-pt" style="float:left;">
                            <label class="datelable" style="text-align:left">From:</label>
                            <input type="text"   name="startdate" autocomplete="off" id="startdate" style="width: 110px;" value="<?php echo @$startdate ? $startdate : date('Y-m-d', strtotime('-15 days')); ?>" />
                        </div>
                        <div class="formRow-pt" style="float:left;">
                            <label class="datelable" style="text-align:left">To:</label>
                            <input type="text"   name="enddate" autocomplete="off" id="enddate" style="width: 110px;" value="<?php echo @$enddate ? $enddate : date('Y-m-d'); ?>" />
                        </div>
                        <span><input type="submit" name="søg" value="Search" class="datefilterBtn" /></span>
                    </div>
                </form>
            </td>
        </tr>
    </table>
</div>
<div class="report_container">
   
<?php if($sf_request->getParameter('show_summary')): ?>
  

<?php endif; ?> <!-- end summary -->

<?php if($sf_request->getParameter('show_details')): ?>
                   

<?php if (count($registrations)>0): ?>
        <div id="sf_admin_container"><h1><?php echo __('Registration Earnings') ?></h1></div>
        
        <div class="borderDiv">	
	  <table cellspacing="0" cellpadding="2" width="100%">
		<tr>

			<th>&nbsp;</th>
			<th><?php echo __('Date') ?> </th>
			<th><?php echo __('Customer name') ?></th>
			<th><?php echo __('Refill Amount') ?></th>
			<th><?php echo __('Commission Earned') ?></th>
		</tr>
		<?php
		$i = 0;
		foreach($registrations as $registration):
		?>
		<tr <?php echo 'class="'.($i%2 == 0?'odd':'even').'"' ?>>
			<td><?php echo ++$i ?>.</td>
                        <td><?php echo $registration->getCreatedAt('d-m-Y') ?></td>
			<td><?php
				$customer = CustomerPeer::retrieveByPK($registration->getCustomerId());
				//$customer2 = CustomerPeer::retrieveByPK(72);
				//echo $customer2->getFirstName();
				echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName());
				?>
			</td>
			<td >
			<?php echo BaseUtil::format_number($registration->getAmount());?><?php echo sfConfig::get('app_currency_code');?>
			</td>
			<td ><?php echo BaseUtil::format_number($registration->getCommissionAmount())?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
              <table width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td align="right"><strong><?php echo __('Total Registration Earnings:') ?></strong></td><td align="right"> <?php echo $i ?></td>
		</tr>
		<tr>
		 <td align="right"><strong><?php echo __('Total Earnings:') ?></strong></td><td align="right"> <?php echo  number_format($registration_revenue,2); ?></td>
		</tr>
		<tr>
		 <td align="right"><strong><?php echo __('Total Commission Earned:') ?></strong></td><td align="right"> <?php echo  number_format($registration_commission,2); ?></td>
		</tr>
        </table>
        </div>
	<?php endif; ?>

<?php if (count($sms_registrations)>0): ?>
        <div id="sf_admin_container"><h1><?php echo __('SMS Registration Earnings') ?></h1></div>
        
        <div class="borderDiv">	
	  <table cellspacing="0" width="100%">
		<tr>

			<th>&nbsp;</th>
			<th><?php echo __('Date') ?> </th>
			<th><?php echo __('Customer name') ?></th>
			<th><?php echo __('Refill Amount') ?></th>
			<th><?php echo __('Commission Earned') ?></th>
		</tr>
		<?php
		$i = 0;
		foreach($sms_registrations as $sms_registration):
		?>
		<tr <?php echo 'class="'.($i%2 == 0?'odd':'even').'"' ?>>
			<td><?php echo ++$i ?>.</td>
                        <td><?php echo $sms_registration->getCreatedAt('d-m-Y') ?></td>
			<td><?php
				$customer = CustomerPeer::retrieveByPK($sms_registration->getCustomerId());
				//$customer2 = CustomerPeer::retrieveByPK(72);
				//echo $customer2->getFirstName();
				echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName());
				?>
			</td>


                        
			<td >
			<?php echo BaseUtil::format_number($sms_registration->getAmount()) ?><?php echo sfConfig::get('app_currency_code');?>
			</td>
                        <?php if ( $sms_registration->getAmount() == 0) {?>
                            <td ><?php echo '10.00' ?>
			</td>
                        <?php }else{ ?>
                        
			<td ><?php echo BaseUtil::format_number($sms_registration->getCommissionAmount()) ?><?php echo sfConfig::get('app_currency_code');?>
			</td>
                        <?php } ?>
                        
		</tr>
		<?php endforeach; ?>
	</table>
              <table width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td align="right"><strong><?php echo __('Total SMS Registration:') ?></strong></td><td align="right"> <?php echo $i ?></td>
		</tr>
		<tr>
		 <td align="right"><strong><?php echo __('Total Earnings:') ?></strong></td><td align="right"> <?php echo  number_format($sms_registration_earnings,2) ?></td>
		</tr>
		<tr>
		 <td align="right"><strong><?php echo __('Total Commission Earned:') ?></strong></td><td align="right"> <?php echo  number_format($sms_commission_earnings,2) ?></td>
		</tr>
        </table>
        </div>    
	<?php endif; ?>


	<?php if (count($refills)>0): ?>	
        <div id="sf_admin_container"><h1><?php echo __('Refills Earnings') ?></h1></div>
        
        <div class="borderDiv">	
	 <table cellspacing="0" width="100%">
		<tr>
			
			<th>&nbsp;</th>
			<th><?php echo __('Date') ?> </th>
			<th><?php echo __('Customer name') ?></th>
			<th><?php echo __('Refill Amount') ?></th>
			<th><?php echo __('Commission Earned') ?></th>
		</tr>
		<?php
		$i = 0;
		foreach($refills as $refill):
		?>
		<tr <?php echo 'class="'.($i%2 == 0?'odd':'even').'"' ?>>
			<td><?php echo ++$i ?>.</td>
                        <td><?php echo $refill->getCreatedAt('d-m-Y') ?></td>
			<td><?php
				$customer = CustomerPeer::retrieveByPK($refill->getCustomerId());
				//$customer2 = CustomerPeer::retrieveByPK(72);
				//echo $customer2->getFirstName();
				echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName());
				?>
			</td>
			
		

			<td >
			<?php echo BaseUtil::format_number($refill->getAmount()) ?><?php echo sfConfig::get('app_currency_code');?>
			</td>
			<td ><?php echo BaseUtil::format_number($refill->getCommissionAmount())?><?php echo sfConfig::get('app_currency_code');?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
              <table width="100%" cellspacing="0" cellpadding="2">
                <tr>
                    <td align="right"><strong><?php echo __('Total Refills:') ?></strong></td><td align="right"> <?php echo $i ?></td>
		</tr>
		<tr>
		 <td align="right"><strong><?php echo __('Total Earnings:') ?></strong></td><td align="right"> <?php echo  number_format($refill_revenue,2)?><?php echo sfConfig::get('app_currency_code');?></td>
		</tr>
		<tr>
		 <td align="right"><strong><?php echo __('Total Commission Earned:') ?></strong></td><td align="right"> <?php echo  number_format($refill_com,2);?><?php echo sfConfig::get('app_currency_code');?></td>
		</tr>
        </table>
        </div>
	<?php endif; ?>

        <?php if (count($number_changes)>0): ?>
	<div id="sf_admin_container"><h1><?php echo __('Mobile Number Change Earnings') ?></h1></div>

        <div class="borderDiv">
           <table cellspacing="0" cellpadding="2" width="100%">
		<tr>
			<th>&nbsp;</th>
			<th><?php echo __('Date') ?> </th>
			<th><?php echo __('Customer name') ?></th>
			<th><?php echo __('Number Change Amount') ?></th>
			<th><?php echo __('Commission Earned') ?></th>
		</tr>
		<?php
		$i = 0;
		foreach($number_changes as $number_change):
		?>
		<tr <?php echo 'class="'.($i%2 == 0?'odd':'even').'"' ?>>
			<td><?php echo ++$i ?>.</td>
                        <td><?php echo $number_change->getCreatedAt('d-m-Y') ?></td>
			<td><?php
				$customer = CustomerPeer::retrieveByPK($number_change->getCustomerId());
				//$customer2 = CustomerPeer::retrieveByPK(72);
				//echo $customer2->getFirstName();
				echo sprintf("%s %s", $customer->getFirstName(), $customer->getLastName());
				?>
			</td>



			<td >
			<?php echo BaseUtil::format_number($number_change->getAmount())?><?php echo sfConfig::get('app_currency_code');?>
			</td>
                        <?php //if ( $number_change->getAmount() == 0) {?>
<!--                            <td ><?php //echo '10.00' ?></td>-->
                        <?php //}else{ ?>

			<td ><?php echo BaseUtil::format_number($number_change->getCommissionAmount())?><?php echo sfConfig::get('app_currency_code');?>
			</td>
                        <?php //} ?>

		</tr>
		<?php endforeach; ?>
                </table>
              <table width="100%" cellspacing="0" cellpadding="2">
        <tr>
		<td align="right"><strong><?php echo __('Total Number Change Sales:') ?></strong></td><td align="right"> <?php echo $i ?></td>
		</tr>
		<tr>
		<td align="right"><strong><?php echo __('Total Earnings:') ?></strong></td><td align="right"> <?php echo BaseUtil::format_number($numberChange_earnings); ?><?php echo sfConfig::get('app_currency_code');?></td>
		</tr>
		<tr>
		<td align="right"><strong><?php echo __('Total Commission Earned:') ?> </strong></td><td align="right"> <?php echo BaseUtil::format_number($numberChange_commission); ?><?php echo sfConfig::get('app_currency_code');?></td>
		</tr>
	</table></div>
	<?php endif; ?>


        <?php else: ?>
        <div id="sf_admin_container"><h1><?php echo __('Earning Summary') ?></h1></div>
        
        <div class="borderDiv">
          <table cellspacing="0" width="60%" class="summary">
        <?php
            if($agent->getIsPrepaid()){
        ?>
    <tr>
                <td><strong><?php echo __('Your Balance is:') ?></strong></td>
		<td align="right"><?php echo  number_format($agent->getBalance(),2); ?></td>
    </tr>
        <?php } ?>
        <tr>
            <td colspan="2">
                <form name="datefilter" action="" method="post">
                    
                </form>
            </td>
        </tr>
	<tr>
		<td><b><?php echo __('Customers') ?></b> <?php echo __('registered with you:') ?></td>
		<td align="right"><?php echo count($registrations) ?></td>
	</tr>
	<tr>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td><?php echo __('Total') ?> <strong><?php echo __('revenue on registration') ?></strong></td>
		<td align="right">
		<?php echo  number_format($registration_revenue,2)

		?>
		</td>
	</tr>
	<tr>
		<td><?php echo __('Total commission earned on registration:') ?></td>
		<td align="right">
		<?php echo  number_format($registration_commission,2);

		?>
		</td>
	</tr>

	<tr>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td><?php echo __('Total') ?> <strong><?php echo __('revenue on refill') ?></strong></td>
		<td align="right">
		<?php echo  number_format($refill_revenue,2)

		?>
		</td>
	</tr>
	<tr>
		<td><?php echo __('Total commission earned on refill:') ?></td>
		<td align="right">
		<?php echo  number_format($refill_com,2);


		?>
		</td>
        </tr>

        <tr>
		<td colspan="2"></td>
	</tr>
        <tr>
            <td><?php echo __('Total') ?> <strong><?php echo __('revenue earned') ?>  </strong><?php echo __('on refill from shop:') ?></td>
		<td align="right">
		<?php echo  number_format($ef_sum,2);

		?>
		</td>
	</tr>
        <tr>
		<td><?php echo __('Total') ?> <strong><?php echo __('commission earned')?> </strong><?php echo __('on refill from shop:') ?></td>
		<td align="right">
		<?php echo  number_format($ef_com,2);?>
                </td>
        </tr>
        <tr>
		<td colspan="2"></td>
	</tr>
   <!--       <tr>
            <td><?php echo __('Total') ?> <strong>revenue </strong><?php echo __('on SMS Registeration:') ?></td>
		<td align="right">
		<?php echo  number_format($sms_registration_earnings,2);

		?>
		</td>
	</tr>
      <tr>
		<td><?php echo __('Total') ?> <strong> <?php echo __('Commission earned') ?> </strong><?php echo __('on SMS Registeration:') ?></td>
		<td align="right">
		<?php echo  number_format($sms_commission_earnings,2);?>
                </td>
        </tr>
     -->


</table>
        </div>
<p>
</p>
<div id="sf_admin_container"><h1><?php echo __('News Box') ?></h1></div>
        
        <div class="borderDiv">

<br/>
<p>
<?php
$currentDate = date('Y-m-d');
?>
<?php
foreach($updateNews as $updateNew)
{
   $sDate=$updateNew->getStartingDate();
   $eDate=$updateNew->getExpireDate();

   if($currentDate>=$sDate)
   {
           ?>


          <b><?php echo $sDate?></b><br/>
          <?php echo $updateNew->getHeading();?> :
          <?php if (strlen($updateNew->getMessage()) > 100 ) {
                  echo substr($updateNew->getMessage(),0,100);
                  echo link_to('....read more','affiliate/newsListing');
          }
          else{
          echo $updateNew->getMessage();
          }
          ?>
          <br/><br/>

<?php
   }

} ?>
<b><?php echo link_to(__('View All News & Updates'),'affiliate/newsListing'); ?> </b>
</p>


<?php endif; ?> <!--  end details -->




</div>



