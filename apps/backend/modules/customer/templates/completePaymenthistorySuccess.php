<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<div id="sf_admin_container">
    <h1><?php echo  __('Payment History') ?></h1><br />
    <div class="sf_admin_filters">
    <form action="" id="searchform" method="POST" name="searchform" >
        <fieldset>
            <div class="form-row">
                <label><?php echo __('Type');?>:</label>
                <div class="content">
                    <select name="description">
                        <option value="">All</option>
                        <?php foreach($alltransactions as $alltransaction){  ?>
                        <option value="<?php  echo $alltransaction->getDescription();  ?>"><?php  echo $alltransaction->getDescription();  ?></option>
                        <?php  }?>
                     </select>
                </div>
            </div>
            <div class="form-row">
                <label><?php echo __('From');?>:</label>
                <div class="content">
                    <input type="text"   name="startdate" autocomplete="off" id="stdate" style="width: 110px;" value="<?php  if(isset($startdate)){ echo $startdate; }  ?>" />
                </div>
            </div>
            <div class="form-row">
                <label><?php echo __('To');?>:</label>
                <div class="content">
                     <input type="text"   name="enddate" autocomplete="off" id="endate" style="width: 110px;" value="<?php   if(isset($enddate)){ echo $enddate; }  ?>" />
                </div>
            </div>
        </fieldset>
        <ul class="sf_admin_actions">
           <li><input type="submit" name="Search" value="Search" class="sf_admin_action_filter" /></li>
        </ul>
    </form>
 
    <table width="100%" cellspacing="0" cellpadding="2" class="callhistory tblAlign">
       <tr class="headings">
          <th  width="15%"  class="title"><?php echo __('Order Numer') ?></th>
          <th  width="20%" class="title"><?php echo __('Date') ?></th>
          <th  width="55%" class="title"><?php echo __('Description') ?></th>
          <th width="10%" class="title"  align="right" style="padding-right: 30px;"><?php echo __('Amount') ?></th>
        </tr>
        <?php 
            $amount_total = 0;
            $incrment=1;
            foreach($transactions as $transaction):
                if($incrment%2==0){
                    $class= 'class="even"';
                }else{
                    $class= 'class="odd"';
                }
                $incrment++;
        ?>
        <tr <?php echo $class;?>>
          <td><?php  echo $transaction->getOrderId() ?></td>
          <td><?php echo  $transaction->getCreatedAt('d-m-Y') ?></td>
          <td><?php echo $transaction->getDescription() ?></td>
          <td align="right;" style="padding-right: 30px;">
              <?php
                    echo  number_format($transaction->getAmount(),2);  $amount_total += $transaction->getAmount();
                    echo (sfConfig::get('app_currency_code'));
              ?>
          </td>
       </tr>
       <?php endforeach; ?>
       <?php if(count($transactions)==0): ?>
       <tr>
          <td colspan="4"><p><?php echo __('There are currently no transactions to show.') ?></p></td>
       </tr>
       <?php else: ?>
       <tr>
          <td align="right" colspan="3"><strong>Total</strong></td>
          <td  align="right" style="padding-right: 30px;">
             <?php
                echo  number_format($amount_total,2);
                echo (sfConfig::get('app_currency_code'));
            ?>
          </td>
        </tr>	
        <?php endif; ?>
     </table>
</div>         