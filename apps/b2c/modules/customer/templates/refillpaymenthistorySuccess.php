<style>
    .split-form-btn {
    clear: both;
    float: left;
    padding-top: 20px;
    width: 515px;
}

</style>
<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<?php include_partial('dashboard_header', array('customer'=> $customer, 'section'=>__('Payment History')) ) ?>
  <div class="left-col">
    <?php include_partial('navigation', array('selected'=>'paymenthistory', 'customer_id'=>$customer->getId())) ?>
      
	<div class="split-form">
      <div class="fl col">
        <form>
          <ul>
<?php  ?>
            <li>
              <!--Always use tables for tabular data-->
			  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="callhistory">
                <tr>
                  <td width="91" class="title"><?php echo __('Order number') ?></td>
                  <td width="104" class="title" nowrap><?php echo __('Date and time') ?></td>
                  <td width="117" class="title"><?php echo __('Description') ?></td>
                  <td width="103" class="title" align="right"><?php echo __('Amount') ?></td>
                  <td width="82" class="title"><?php echo __('Type') ?></td>
                  <td width="18" class="title"></td>
                </tr>
                <?php 
                $amount_total = 0;
                foreach($transactions as $transaction): ?>
                <tr>
                  <td><?php  echo $transaction->getOrderId() ?></td>
                  <td ><?php echo  $transaction->getCreatedAt('d-m-Y H:i:s') ?></td>
                  <td nowrap><?php 
                  if($transaction->getDescription()=="Registrering inkl. taletid"){
                      echo "SmartSim inkludert Pott";                      
                  }else{
                        if($transaction->getDescription()=="Zapna Refill"){
                          echo __("Refill ".$transaction->getAmount());
                        }else{
                          echo __($tdescription = $transaction->getDescription());  
                        } 
                  }?></td>
                  <td align="right"><?php
                    echo number_format($transaction->getAmount(),2); $amount_total += $transaction->getAmount();?>
                            <?php 
//                            if($lang=="pl"){
//                               // echo ('plz');
//                            }else if($lang=="en"){
//                               // echo ('eur');
//                            }else{
                                echo sfConfig::get('app_currency_code');
//                            } ?></td>
                  <td><a href="#" class="receipt" onclick="javascript: window.open('<?php echo url_for('payments/showReceipt?tid='.$transaction->getId(), true) ?>')">
                            <?php //echo $tdescription;
                              if(strstr($tdescription, "bonus")){
                                echo __('Bonus');
                              }else{
                                echo $transaction->getAmount()>=0?__('Paid'):__('Charged');  
                              }  
                            ?>
                      </a></td>
<!--                  <td nowrap="nowrap"><a href="#" style=" white-space: nowrap" class="receipt" onclick="iprint(preview_<?php echo $transaction->getId();?>);return false;"><?php echo __('Print'); ?>
                  </a>
                      <iframe id="preview_<?php echo $transaction->getId();?>" name="preview_<?php echo $transaction->getId();?>" src="<?php echo url_for('payments/showReceipt?tid='.$transaction->getId(), true) ?>"  style="display:none">
                     </iframe>
                        <script>
                            function printForm() { window.focus(); window.print(); }
                            function iprint(ptarget)
                            {
                                ptarget.focus();
                                window.print();
                            } 
                        </script>-->
                      </td>
                 
                     
                 
                 
                </tr>
                <?php endforeach; ?>
                <?php if(count($transactions)==0): ?>
                <tr>
                	<td colspan="6"><p><?php echo __('There are currently no transactions to show.') ?></p></td>
                </tr>
                <?php else: ?>
                <tr>
                	<td colspan="3" align="right"><strong><?php echo __('Total') ?></strong></td>
                        <td  align="left" style="text-align:right;"><?php echo number_format($amount_total,2) ?>
                            <?php 
//                            if($lang=="pl"){
//                                echo ('plz');
//                            }else if($lang=="en"){
//                                echo ('eur');
//                            }else{
                                echo sfConfig::get('app_currency_code');
//                            } ?></td><td>&nbsp;</td>
                </tr>	
                <?php endif; ?>
              </table>
            </li>
            <?php if($total_pages>1): ?>
            <li>
            	<ul class="paging">
            	<?php for ($i=1; $i<=$total_pages; $i++): ?>
            		<li <?php echo $i==$page?'class="selected"':'' ?>><a href="<?php echo url_for('customer/refillpaymenthistory?page='.$i) ?>"><?php echo $i ?></a></li>
            	<?php endfor; ?>
            	</ul>
            </li>
            <?php endif; ?>
          </ul>
        </form>
      </div>
    </div> <!-- end split-form -->
  </div> <!-- end left-col -->
  <?php include_partial('sidebar') ?>