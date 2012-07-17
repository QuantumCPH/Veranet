<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>

<div id="sf_admin_container">
    <ul class="customerMenu" style="margin:10px 0;">
            <li><a class="external_link" href="allRegisteredCustomer"><?php echo  __('View All Customer') ?></a></li>
            <li><a class="external_link" href="customerDetail?id=<?php echo $_REQUEST['id'];  ?>"><?php echo  __('Customer Detail') ?></a></li>
            <li><a class="external_link"  href="callhistory?id=<?php echo $_REQUEST['id'];  ?>"><?php echo  __('Call History') ?></a></li>
        </ul>
<h1><?php echo  __('Payment History') ?></h1>
              <!--Always use tables for tabular data-->
              <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign">
                   <tr class="headings">
                       <th width="15%"  align="left"><?php echo __('Order Numer') ?></th>
                          <th width="25%"  align="left"><?php echo __('Date &amp; Time') ?></th>
                          <th width="50%"  align="left"><?php echo __('Description') ?></th>
                          <th width="10%" align="right"><?php echo __('Amount') ?>(<?php echo sfConfig::get('app_currency_code');?>)</th>
                              </tr>
                <?php 
                $amount_total = 0;
                $incrment=1;
                foreach($transactions as $transaction): ?>

                 <?php
                  if($incrment%2==0){
                 $class= 'class="even"';
                  }else{

                       $class= 'class="odd"';
                      }
 $incrment++;
                  ?>
                <tr <?php echo $class;   ?>>
                  <td><?php  echo $transaction->getOrderId() ?></td>
                  <td><?php echo  $transaction->getCreatedAt('d-m-Y H:i:s') ?></td>
                  <td><?php echo $transaction->getDescription() ?></td>
                  <td  align="right"><?php echo number_format($transaction->getAmount(),2); $amount_total += $transaction->getAmount() ?>
                            <?php
                                echo (sfConfig::get('app_currency_code'));
                          ?></td>
                
                </tr>
                <?php endforeach; ?>
                <?php if(count($transactions)==0): ?>
                <tr>
                	<td colspan="5"><p><?php echo __('There are currently no transactions to show.') ?></p></td>
                </tr>
                <?php else: ?>
                <tr>
                	<td colspan="3" align="right"><strong>Total</strong></td>
                        <td  align="right"><?php echo number_format($amount_total,2); ?>
                            <?php 
                                echo (sfConfig::get('app_currency_code'));
                       ?></td>
                	
                </tr>	
                <?php endif; ?>
              </table>
  </div> 