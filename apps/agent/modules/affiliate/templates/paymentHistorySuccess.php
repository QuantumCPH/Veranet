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
<div class="report_container">
<div id="sf_admin_container"><h1><?php echo __('Payment History') ?></h1></div>
        
 <div class="borderDiv">     
    <table cellspacing="0" width="100%" class="summary">
  <?php
  if($agents){
  ?> 
  <tr>
    <th width="21%"><?php echo __('Transaction Type') ?></th>
    <th width="14%" style="text-align:right;padding-right: 25px;"><?php echo __('Amount') ?> </th>
    <th width="15%" style="text-align:right;padding-right: 25px;"><?php echo __('Remaining Balance') ?></th>
    <th width="50%"><?php echo __('Date') ?> </th>
      
  </tr>
 <?php
    $i = 0;
    
    foreach($agents as $agent) {
        $i++;
        ?>

  
  <tr <?php echo 'class="'.($i%2 == 0?'odd':'even').'"' ?>>
  <td><?php  $expensetype=$agent->getExpeneseType(); 
    if($expensetype==1){  echo __("Customer Registration");  }
    if($expensetype==2){  echo __("Customer Refill");  }
    if($expensetype==3){  echo __("Agent Account Refill");  }
    if($expensetype==4){  echo __("Customer Refill from Shop");  }
    if($expensetype==6){  echo __("Change Number");  }
    if($expensetype==9){
        $ctd = new Criteria();
        $ctd->add(TransactionDescriptionPeer::ID,$agent->getOrderDescription());        
        $tdd = TransactionDescriptionPeer::doSelectOne($ctd);
        echo $tdd->getTitle();        
    }
     ?></td>
  <td style="text-align:right;padding-right: 25px;"><?php  echo number_format($agent->getAmount(),2);?><?php echo sfConfig::get('app_currency_code')?></td>
    <td style="text-align:right;padding-right: 25px;"><?php  echo number_format($agent->getRemainingBalance(),2);?><?php echo sfConfig::get('app_currency_code')?></td>
      <td><?php  echo $agent->getCreatedAt('d-m-Y');  ?></td>
      
  </tr>
  <?php  }
  }
  else{ ?>
      <tr>
          <td> <?php echo __("Payment history not found.");?></td>
      </tr>
 <?php } ?>
  </table>

  

  </div>
</div>



