<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>

<?Php if($companyval!=''){?><div id="sf_admin_container">
	<div id="sf_admin_content">
            <a href="<?php echo url_for('employee/index').'?company_id='.$companyval."&filter=filter" ?>" class="external_link" target="_self"><?php echo __('Employees') ?> (<?php echo $count ?>)</a>
            <a href="<?php echo url_for('company/usage').'?company_id='.$companyval; ?>" class="external_link" target="_self"><?php echo __('Usage') ?></a>
            <a href="<?php echo url_for('company/paymenthistory').'?company_id='.$companyval.'&filter=filter' ?>" class="external_link" target="_self"><?php echo __('Receipts') ?></a>
            <a href="<?php echo url_for('company/invoices') . '?company_id=' . $companyval?>" class="external_link" target="_self"><?php echo __('Invoices') ?></a>
        </div>
    </div>
<?php } ?>

<div id="sf_admin_container">
    <h1><?php echo __('Receipts') ?></h1>
    <?php if ($sf_user->hasFlash('message')): ?>
    <div class="save-ok">
      <h2><?php echo __($sf_user->getFlash('message')) ?></h2>
    </div>
    <?php endif; ?>
</div>
<div id="sf_admin_header"></div>
<table width="75%" cellspacing="0" cellpadding="2" class="tblAlign">
<tr class="headings">
    <th><?php echo __('Date') ?></th>
    <th><?php echo __('Company & Name') ?></th>
    <th><?php echo __('Description') ?></th>
    <th align="right"><?php echo __('Amount') ?> (<?php echo sfConfig::get('app_currency_code');?>)</th>
    <th><?php echo __('Reciept') ?></th>
</tr>
<?php 
$amount_total = 0;
$incrment=1;
foreach($transactions as $transaction):

if($incrment%2==0){
  $colorvalue="#FFFFFF";
  $class= 'class="even"';
  }else{
    $class= 'class="odd"';
    $colorvalue="#FCD9C9";
 }
//                  
$incrment++;
?>
<tr  <?php echo $class;?>>
    <td><?php echo  $transaction->getCreatedAt('d-m-Y') ?></td>
    <td><?php echo ($transaction->getCompany()?$transaction->getCompany():'N/A')?></td>
    <td><?php echo __($transaction->getDescription()) ?></td>
    <td align="right"><?php echo number_format($transaction->getAmount(),2); $amount_total += $transaction->getAmount(); ?><?php echo sfConfig::get('app_currency_code');?></td>
    <td><a href="<?php echo sfConfig::get('app_admin_url'). "company/ShowReceipt?tid=".$transaction->getId()?>" target="_blank"> <img src="/sf/sf_admin/images/default_icon.png" title=<?php echo __("view")?> alt=<?php echo __("view")?>></a></td>
</tr>
<?php endforeach; ?>
<?php if(count($transactions)==0): ?>
<tr>
    <td colspan="5"><p><?php echo __('There are currently no transactions to show.') ?></p></td>
</tr>
<?php else: ?>
<tr><td>&nbsp;</td>
    <td colspan="2" align="right"><strong><?php echo __('Total:') ?>&nbsp;&nbsp;</strong></td>
    <td align="right"><?php echo number_format($amount_total,2);  ?><?php echo sfConfig::get('app_currency_code');?></td>
    <td>&nbsp;</td>
</tr>	
<?php endif; ?>
</table>
