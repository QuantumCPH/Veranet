<?php use_helper('I18N') ?>
<script>
  jQuery(function(){
      jQuery('#trigger_startdate').hide();
      jQuery('#trigger_enddate').hide();
  });
</script>
<div id="sf_admin_container">
    <div id="sf_admin_content">
        <!-- employee/list?filters[company_id]=1 -->
        <a href="<?php echo url_for('employee/index') . '?company_id=' . $company->getId() . "&filter=filter" ?>" class="external_link" target="_self"><?php echo __('Employees') ?> (<?php echo $cnt ?>)</a>
        <a href="<?php echo url_for('company/usage') . '?company_id=' . $company->getId(); ?>" class="external_link" target="_self"><?php echo __('Usage') ?></a>
        <a href="<?php echo url_for('company/paymenthistory') . '?company_id=' . $company->getId() . '&filter=filter' ?>" class="external_link" target="_self"><?php echo __('Receipts') ?></a>
        <a href="<?php echo url_for('company/invoices') . '?company_id=' . $company->getId() ?>" class="external_link" target="_self"><?php echo __('Invoices') ?></a>
    </div>
    <div class="sf_admin_filters">
        <form action="" id="searchform" method="POST" name="searchform">
            <input type="hidden" value="<?php echo $company->getId()?>" name="company_id" />
            <fieldset>
                <div class="form-row">
                    <label><?php echo __('Select Employee to Filter'); ?>:</label>
                    <div class="content">
                        <select name="iaccount" id="account">
                            <option value =''></option>
                            <?php
                            if (count($telintaAccountObj) > 0) {
                                foreach ($telintaAccountObj as $account) {
                                    $employeeid = $account->getParentId();
                                    $cn = new Criteria();
                                    $cn->add(EmployeePeer::ID, $employeeid);
                                    $employees = EmployeePeer::doSelectOne($cn);
                                    ?>
                            <option value="<?PHP echo $account->getId(); ?>" <?PHP echo ($account->getId() == $iaccount) ? 'selected="selected"' : '' ?>><?php echo $employees->getFirstName() . " -- " . $employees->getMobileNumber(); ?></option>
                                <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <label><?php echo __('From'); ?>:</label>
                    <div class="content">
                        <?php echo input_date_tag('startdate', $fromdate, 'rich=true') ?>
                    </div>
                </div>
                <div class="form-row">
                    <label><?php echo __('To'); ?>:</label>
                    <div class="content">
                        <?php echo input_date_tag('enddate', $todate, 'rich=true') ?>
                    </div>
                </div>
            </fieldset>
            <ul class="sf_admin_actions">
                <li><input type="submit" class="sf_admin_action_filter" value="filter" name="filter"></li>
                <li><input type="button" class="sf_admin_action_reset_filter" value="reset" name="reset" onclick="document.location.href='<?PHP echo sfConfig::get('app_admin_url') . "company/usage?company_id=".$company->getId(); ?>'"></li>
            </ul>
        </form>
    </div><br><br /><br />
    <h1><?php echo __('Call History'); ?></h1>
    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>


        <tr class="headings">
            <th width="20%"   align="left"><?php echo __('Date & Time') ?></th>
            <th  width="10%"   align="left"><?php echo __('Emp Mobile') ?></th>
            <th  width="20%"  align="left"><?php echo __('Phone Number') ?></th>
            <th width="10%"   align="left"><?php echo __('Duration') ?></th>
            <th  width="10%"  align="left"><?php echo __('Country') ?></th>
<!--            <th  width="10%"  align="left"><?php echo __('VAT') ?></th>-->
            <th  width="10%"  align="left"><?php echo __('Description') ?></th>
            <th width="20%"   align="left" style="text-align: right;"><?php echo __('Cost') ?></th>
            
<!--            <th width="20%"   align="left"><?php echo __('Samtalstyp') ?></th>-->
        </tr>
        <?php
        $callRecords = 0;

        $amount_total = 0;

        foreach ($callHistory->xdr_list as $xdr) {
        ?>


            <tr>
                <td><?php echo date("Y-m-d H:i:s", strtotime($xdr->connect_time)); ?></td>
                <td><?php echo substr($xdr->account_id,4); ?></td>
                <td><?php echo $xdr->CLD; ?></td>
                <td><?php  echo  date('i:s',$xdr->charged_quantity); ?></td>
                <td><?php echo $xdr->country; ?></td>
                <td><?php echo $xdr->description; ?></td>
<!--                <td><?php echo number_format($xdr->charged_amount / 4, 2); ?></td>-->
                <td align="right"><?php echo number_format($xdr->charged_amount, 2);
            $amount_total+= $xdr->charged_amount; ?><?php echo sfConfig::get('app_currency_code');?></td>
                
<!--            <td><?php
                $typecall = substr($xdr->account_id, 0, 1);
                if ($typecall == 'a') {
                    echo "Int.";
                }
                if ($typecall == '4') {
                    echo "R";
                }
                if ($typecall == 'c') {
                    if ($CLI == '**24') {
                        echo "Cb M";
                    } else {
                        echo "Cb S";
                    }
                } ?> </td>-->
        </tr>

        <?php
                $callRecords = 1;
            }
        ?>        <?php if ($callRecords == 0) {
 ?>
                <tr>
                    <td colspan="7"><p><?php echo __('There are currently no call records to show.') ?></p></td>
                </tr>
<?php } else { ?>
                <tr>
                    <td colspan="6" align="right"><strong><?php echo __('Subtotal') ?></strong></td>

                    <td align="right"><?php echo number_format($amount_total, 2) ?><?php echo sfConfig::get('app_currency_code')?></td>
<!--                    <td>&nbsp;</td>-->
                </tr>
<?php } ?>

<!--            <tr><td colspan="6" align="left"><?php echo __('Call type detail') ?> <br/> <?php echo __('Int. = International calls') ?><br/>
                <?php echo __('Cb M = Callback mottaga')  ?><br/>
                <?php echo __('Cb S = Callback samtal')  ?><br/>
<?php echo __('R = resenummer samtal')    ?><br/>
            </td></tr>-->
  
    </table>
    <br /><br />
    <h1><?php echo __('Subscription'); ?></h1>
    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>
        <tr class="headings">
            <th  width="10%"  align="left"><?php echo __('Date & time') ?></th>
            <th  width="10%"  align="left"><?php echo __('Mobile Number');?><?php //echo __('Account ID') ?></th>
            <th  width="10%"  align="left"><?php echo __('Description') ?></th>
            <th  width="10%"  align="left" style="text-align: right;"><?php echo __('Amount') ?></th>
        </tr>
        <?php //var_dump($ems);
         $total_sub = 0;
         $regfee = 0;
         $fromdate = date('Y-m-d 21:00:00', strtotime("-1 day",strtotime($fromdate)));
         $todate = date('Y-m-d 21:59:59', strtotime($todate));
         
          echo    $fromdate;
          echo '<br />';
          echo    $todate;   
         if(isset($empl)){
           $tilentaSubResult = CompanyEmployeActivation::getSubscription($empl, $fromdate, $todate);
          // var_dump($tilentaSubResult);
            if (count($tilentaSubResult) > 0) {
                foreach ($tilentaSubResult->xdr_list as $xdr) {
                    ?> <tr>
                        <td><?php echo date("d-m-Y H:i:s", strtotime($xdr->bill_time)); ?></td>
                        <td><?php echo __($xdr->account_id); ?></td>
                        <td><?php echo __($xdr->CLD); ?></td>
                        <td align="right"><?php echo number_format($xdr->charged_amount, 2); $total_sub += $xdr->charged_amount; ?>&nbsp;<?php echo sfConfig::get('app_currency_code') ?></td>
                    </tr>
                <?php
                }
            } 
         }else{   
             foreach ($ems as $emp) {         
            $tilentaSubResult = CompanyEmployeActivation::getSubscription($emp, $fromdate , $todate);
         //   var_dump($tilentaSubResult);
            if (count($tilentaSubResult) > 0) {
                foreach ($tilentaSubResult->xdr_list as $xdr) {
                    ?> <tr>
                        <td><?php //echo $xdr->bill_time;
                      echo  date("Y-m-d H:i:s", strtotime($xdr->bill_time)); ?></td>
                        <td><?php //echo __($xdr->account_id); ?><?php echo $emp->getMobileNumber();?></td>
                        <td><?php echo __($xdr->CLD); ?></td>
                        <td align="right"><?php echo number_format($xdr->charged_amount, 2); $total_sub += $xdr->charged_amount;?><?php echo sfConfig::get('app_currency_code') ?></td>
                    </tr>
                <?php
                }
            } 
         }
       }
        ?>
                    
    <tr>
        <td colspan="3" align="right"><strong><?php echo __('Subtotal')?></strong></td>
        <td align="right"><?php echo number_format($total_sub+$regfee,2);?><?php echo sfConfig::get('app_currency_code'); ?></td>
    </tr>
    </table><br/><br/>
    <h1><?php echo __("Other events"); ?> </h1>
    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>
        <tr class="headings">
            <th  width="10%"  align="left"><?php echo __('Date and time') ?></th>
            <th  width="10%"  align="left"><?php echo __('Description') ?></th>
            <th  width="10%"  align="left" style="text-align: right;"><?php echo __('Amount') ?></th>
       </tr>
        <?php
        $othertotal = 0;
  
      //  foreach ($ems as $emp) {
         $otherEvents = CompanyEmployeActivation::callHistory($company, $fromdate, $todate, false, 1);   
        if(count($otherEvents)>0){
        foreach ($otherEvents->xdr_list as $xdr) {
         ?>
            <tr>
                <td><?php echo date("Y-m-d H:i:s", strtotime($xdr->bill_time)); ?><?php //echo $emp->getId();?></td>
                <td><?php echo __($xdr->CLD); ?></td>
                <td align="right"><?php echo number_format($xdr->charged_amount,2); $othertotal +=$xdr->charged_amount;?><?php echo sfConfig::get('app_currency_code')?></td>
            </tr>
            <?php } }else {
             ?>
                    <tr>
                        <td>
             <?php
                echo __('There are currently no records to show.'); ?>
                        </td>
                    </tr>
          <?php                  
            }
      //  }  ?>
            <tr align="right">
                <td colspan="2"><strong><?php echo __('Subtotal');?></strong></td><td><?php echo number_format($othertotal,2)?><?php echo sfConfig::get('app_currency_code')?></td>
            </tr>         
            <tr align="right">
            <td colspan="2"><strong><?php echo __('Total');?></strong></td><td><strong><?php echo number_format($amount_total+$total_sub+$othertotal,2)?><?php echo sfConfig::get('app_currency_code')?></strong></td>
        </tr> 
        </table><br/><br/>
        <h1><?php echo __("Payment History"); ?> </h1>
    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>
        <tr class="headings">
            <th  width="10%"  align="left"><?php echo __('Date and time') ?></th>
            <th  width="10%"  align="left"><?php echo __('Description') ?></th>
            <th  width="10%"  align="left" style="text-align: right;"><?php echo __('Amount') ?></th>
       </tr>
        <?php
        $paymenttotal = 0;
        $otherEvent = CompanyEmployeActivation::callHistory($company, $fromdate, $todate , false, 2);
       // var_dump($otherEvents);
        if(count($otherEvent)>0){
        foreach ($otherEvent->xdr_list as $xdr) {
         ?>
            <tr>
                <td><?php echo date("Y-m-d H:i:s", strtotime($xdr->bill_time)); ?></td>
                <td><?php echo __($xdr->CLD); ?></td>
                <td align="right"><?php echo number_format(-1 * $xdr->charged_amount,2); $paymenttotal +=$xdr->charged_amount;?>&nbsp;<?php echo sfConfig::get('app_currency_code')?></td>
            </tr>
            <?php } 
            
            }else {
             ?>
                    <tr>
                        <td>
             <?php
                echo __('There are currently no records to show.'); ?>
                        </td>
                    </tr>
          <?php                  
            }
      ?>
        <tr align="right">
                <td colspan="2"><strong><?php echo __('Total');?></strong></td><td><?php echo number_format(-1 * $paymenttotal,2);?><?php echo sfConfig::get('app_currency_code')?></td>
        </tr>
       
        </table><br/><br/>
</div>