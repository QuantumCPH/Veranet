<?php use_helper('I18N') ?>
<div id="sf_admin_container">
 <?PHP
    $str=strlen($company->getId());
    $str1=strlen(sfConfig::get("app_telinta_emp"));
    $substr=$str+$str1;
 ?>
<!--<a href=?iaccount=<?php //echo $account->getIAccount()."&iaccountTitle=".$account->getAccountTitle(); ?>>-->
<h1><?php echo __('Call History'); if(isset($iAccountTitle)&&$iAccountTitle!=''){echo "($iAccountTitle)"; }?></h1>
<div class="sf_admin_filters">
    <form action="" id="searchform" method="POST" name="searchform">
        <fieldset>
            
            <div class="form-row">
            <label class="required">Employee:</label>
            <div class="content">
                <select name="employee">
                    <option value="">All</option>
                    <?php
                    foreach ($employees as $employee) {
                    ?>
                        <option value="<?php echo $employee->getId(); ?>" <?php //echo $employee_id == $employee->getId() ? " selected='selected'" : ""; ?>><?php echo $employee->getFirstName() . " - " . $employee->getMobileNumber(); ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
             </div>
            <div class="form-row">
                        <label class="required">Billing Duration:</label>
                        <div class="content">
                            <select name="billingduration">
                                <?php
                                
                                $last = date("Y-m-01 00:00:00");
                                $till = date("Y-m-t 23:59:59");
                                ?>
<!--                                <option value="<?php echo $last . '_' . $till; ?>" <?php echo ($last . '_' . $till == $billingduration) ? 'selected="selected"' : ''; ?>><?php echo date('d M Y', strtotime($last)) . ' - ' . date('d M Y', strtotime($till)); ?></option>-->

                                <?php
                                // Start date


                                foreach ($invoiceTimings as $invoiceTiming) {
                                    $duration_value = $invoiceTiming->getBillingStartingDate() . "_" . $invoiceTiming->getBillingEndingDate(); ?>
                                    <option value="<?php echo $duration_value; ?>" <?php echo ($duration_value == $billingduration) ? 'selected="selected"' : ''; ?>><?php echo $invoiceTiming->getBillingStartingDate("d M y") . " - " . $invoiceTiming->getBillingEndingDate("d M y"); ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>
                    </div>
        </fieldset>
        <ul class="sf_admin_actions">
           <li><input type="submit" class="sf_admin_action_filter" value="filter" name="filter"></li>
           <li><input type="button" class="sf_admin_action_reset_filter" value="reset" name="reset" onClick="document.location.href='<?PHP echo sfConfig::get('app_b2b_url')."company/callHisotry";?>'"></li>
        </ul>
    </form>
</div>
    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>


        <tr class="headings">
            <th width="3%"   align="left">&nbsp;</th>
            <th width="17%"   align="left"><?php echo __('Date & Time') ?></th>
            <th  width="11%" align="left"><?php echo __('Phone Number') ?></th>
            <th width="11%"  align="left"><?php echo __('Duration') ?></th>
            <th  width="15%" align="left"><?php echo __('Country') ?></th>
            <th  width="21%" align="left"><?php echo __('Description') ?></th>
            <th width="11%"  align="left"><?php echo __('Cost') ?></th>
            <th  width="11%" align="left"><?php echo __('Account ID') ?></th>
      </tr>
        <?php
        $callRecords = 0;

        $amount_total = 0;
        $amount_total_wo =0;
        $rec = 0; 
       foreach ($callHistory as $call) {
           $rec ++;
        ?>
            <tr><td><?php echo $rec;?>.</td>
                <td><?php echo $call->getConnectTime(); ?></td>
                <td><?php echo $call->getPhoneNumber(); ?></td>
                <td><?php //echo $call->getEmployeeId();
                  $duration = EmployeeCustomerCallhistoryPeer::getCallDuration($call->getId());
                  echo $duration;
                ?></td>
                <td><?php echo $call->getCountry()->getName(); ?></td>
                <td>&nbsp;<?php echo $call->getDescription(); ?></td>
                <td><?php echo number_format($call->getChargedAmount(),2);echo sfConfig::get('app_currency_code');
            $amount_total += $call->getChargedAmount(); ?></td>
            <td><?php echo $call->getAccountId(); ?></td>
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

                    <td><?php echo number_format($amount_total,2) ?><?php echo sfConfig::get('app_currency_code');?></td>
                    <td>&nbsp;</td>
                </tr>
<?php } ?>
    </table>
<br />   <?php

                             
                               $current_bill = strtotime($end);
//echo'end ---'.$end;                                    
//echo '<br/>';
//echo 'current ---'.$last;

                                if($current_bill > strtotime($last)){
                            ?>
                            <div id="company-info">
                                <h1>Registration and Subscription Fee</h1></div>
                            <table cellpadding="3" cellspacing="0" class="tblNoborder" width="100%">
            <?php
                                $regfee = 0.00;
                                $sub_Fee = 0.00;
                                $total = 0.00;
                                $startdate = $start;
                                $enddate = $end;
                                $i = 0;
                                foreach ($employees as $employee) {
                                    $regfee = 0.00;
                                    $sub_Fee = 0.00;
                                    $regFlag = false;
                                    $subFlag = false;
                                    $ers = new Criteria();
                                    $ers->add(RegistrationSubscriptionPeer::PARENT_TABLE, 'employee');
                                    $ers->add(RegistrationSubscriptionPeer::PARENT_ID, $employee->getId());
                                    $ers->addAnd(RegistrationSubscriptionPeer::BILL_START, $startdate);
                                    $ers->addAnd(RegistrationSubscriptionPeer::BILL_END, $enddate);

                                    if (RegistrationSubscriptionPeer::doCount($ers) > 0) {
                                        $empRegPrd = RegistrationSubscriptionPeer::doSelectOne($ers);
                                        $regfee = $empRegPrd->getRegFee();
                                        $sub_Fee = $empRegPrd->getSubFee();
                                        if ($regfee > 0)
                                            $regFlag = true;
                                        if ($sub_Fee > 0)
                                            $subFlag = true;
                                    }

                                    $total += $regfee + $sub_Fee;
            ?>
            <?php if ($regFlag || $subFlag) {
 ?>
                                        <tr><td>
                                                <strong><?php echo "Mobile Number " . $employee->getMobileNumber() ?> </strong></td><td>&nbsp;</td></tr>
            <?php } if ($regFlag) {
            ?>
                                        <tr><td>
            <?php echo 'Registration Fee' . " - " . $empRegPrd->getProductName();
                                        ; ?></td>
                                            <td><?php echo $regfee; ?></td></tr>
                    <?php
                                    }
                    ?>
            <?php if ($subFlag) {
 ?>
                                        <tr><td>
<?php echo 'Subscription:' . " - " . $empRegPrd->getProductName(); ?></td>
                                            <td><?php echo $sub_Fee; ?></td></tr><?php
                                    }
?>
            <?php
                                }

                                //echo $billingInvoiceFlag?'true':'false';
            ?>
            <?php if ($total > 0) {
            ?>
                                    <tr><td style="border-top:1px solid #D44D05 !important;text-align: right;"><strong>Total: </strong></td><td style="border-top:1px solid #D44D05 !important;"><strong><?php echo $total; ?></strong></td></tr>
            <?php } else { ?>
                                    <tr><td><strong>Registration/Subscription Fee not charged.</strong></td></tr>
            <?php } ?>
                            </table><br />
                            <table cellpadding="3" cellspacing="0" class="tblNoborder" width="30%" style="text-align:right !important;float:right;">
                                <tr><td><strong>Total:</strong></td><td><?php echo number_format($netTotal = $total + $amount_total, 2); ?><?php echo sfConfig::get('app_currency_code');?></td></tr>
                                <tr><td><strong>Invoice Cost:</strong></td><td><?php echo $invoice_cost = $total_invoice_cost; ?><?php echo sfConfig::get('app_currency_code');?></td></tr>
                                <tr><td><strong>Total inc. Invoice Cost:</strong></td><td><?php echo number_format($netTotal = $netTotal + $invoice_cost, 2); ?><?php echo sfConfig::get('app_currency_code');?></td></tr>
                                <tr><td><strong>Vat:</strong></td><td><?php echo number_format($total_mom, 2); ?><?php echo sfConfig::get('app_currency_code');?></td></tr>
                                <tr><td><strong>Total inc. Vat:</strong></td><td><?php echo number_format($netTotal + $total_mom, 2); ?><?php echo sfConfig::get('app_currency_code');?></td></tr>
        </table>

                            <?php } ?>
        <br clear="all" />
        <p>&nbsp;</p>
</div>