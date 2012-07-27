<?php use_helper('I18N') ?><div id="sf_admin_container">
    <div id="sf_admin_content">
        <!-- employee/list?filters[company_id]=1 -->
        <a href="<?php echo url_for('employee/index') . '?company_id=' . $company->getId() . "&filter=filter" ?>" class="external_link" target="_self"><?php echo __('Employees') ?> (<?php echo count($company->getEmployees()) ?>)</a>
        <a href="<?php echo url_for('company/usage') . '?company_id=' . $company->getId(); ?>" class="external_link" target="_self"><?php echo __('Usage') ?></a>
        <a href="<?php echo url_for('company/paymenthistory') . '?company_id=' . $company->getId() . '&filter=filter' ?>" class="external_link" target="_self"><?php echo __('Payment History') ?></a>
        <a href="<?php echo url_for('company/invoices') . '?company_id=' . $company->getId() ?>" class="external_link" target="_self"><?php echo __('Invoices') ?></a>
    </div>
    <h1><?php echo __('Call History'); ?></h1>
    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>


        <tr class="headings">
            <th width="20%"   align="left"><?php echo __('Date & Time') ?></th>

            <th  width="20%"  align="left"><?php echo __('Phone Number') ?></th>
            <th width="10%"   align="left"><?php echo __('Duration') ?></th>
            <th  width="10%"  align="left"><?php echo __('Country') ?></th>
<!--            <th  width="10%"  align="left"><?php echo __('VAT') ?></th>-->
            <th width="20%"   align="left"><?php echo __('Cost') ?></th>
            <th  width="10%"   align="left"><?php echo __('Account ID') ?></th>
<!--            <th width="20%"   align="left"><?php echo __('Samtalstyp') ?></th>-->
        </tr>
        <?php
        $callRecords = 0;

        $amount_total = 0;

        foreach ($callHistory->xdr_list as $xdr) {
        ?>


            <tr>
                <td><?php echo $xdr->connect_time; ?></td>
                <td><?php echo $xdr->CLD; ?></td>
                <td><?php  echo  date('i:s',$xdr->charged_quantity); ?></td>
                <td><?php echo $xdr->country; ?></td>
<!--                <td><?php echo number_format($xdr->charged_amount / 4, 2); ?></td>-->
                <td><?php echo number_format($xdr->charged_amount, 2);
            $amount_total+= number_format($xdr->charged_amount, 2); ?> <?php echo sfConfig::get('app_currency_code');?></td>
                <td><?php echo $xdr->account_id; ?></td>
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
                    <td colspan="6"><p><?php echo __('There are currently no call records to show.') ?></p></td>
                </tr>
<?php } else { ?>
                <tr>
                    <td colspan="4" align="right"><strong><?php echo __('Subtotal') ?></strong></td>

                    <td><?php echo number_format($amount_total, 2, ',', '') ?> <?php echo sfConfig::get('app_currency_code')?></td>
                    <td>&nbsp;</td>
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
            <th  width="10%"  align="left"><?php echo __('Account ID') ?></th>
            <th  width="10%"  align="left"><?php echo __('Description') ?></th>
            <th  width="10%"  align="left" style="text-align: right;"><?php echo __('Amount') ?></th>
        </tr>
        <?php //var_dump($ems);
        $total_sub = 0;
         foreach ($ems as $emp) {
            $tilentaSubResult = CompanyEmployeActivation::getSubscription($emp, $fromdate . ' 00:00:00', $todate . ' 23:59:59');
            if (count($tilentaSubResult) > 0) {
                foreach ($tilentaSubResult->xdr_list as $xdr) {
                    ?> <tr>
                        <td><?php echo date("d-m-Y H:i:s", strtotime($xdr->bill_time)); ?></td>
                        <td><?php echo __($xdr->account_id); ?></td>
                        <td><?php echo __($xdr->CLD); ?></td>
                        <td aligin="right" style="text-align: right;"><?php echo number_format($xdr->charged_amount, 2); $total_sub += $xdr->charged_amount;?>&nbsp;<?php echo sfConfig::get('app_currency_code') ?></td>
                    </tr>
                <?php
                }
            } else {

                echo __('There are currently no call records to show.');
            }
         }  
        ?>
                    <tr>
                        <td colspan="3" align="right"><strong>Total</strong></td>
                        <td align="right"><?php echo number_format($total_sub,2);?><?php echo sfConfig::get('app_currency_code'); ?></td>
                    </tr>
    </table><br/><br/>
</div>