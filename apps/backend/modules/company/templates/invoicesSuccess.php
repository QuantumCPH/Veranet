<script>
     jQuery(document).ready(function(){
        jQuery("#start_date").datepicker({dateFormat: 'yy-mm-dd'});
	jQuery( "#end_date" ).datepicker({dateFormat: 'yy-mm-dd'});
      });
</script>
<div id="sf_admin_container">
    <div class="sf_admin_filters">
        <h1>Company Invoices</h1>
        <form name="frmCompanyInvlices" action="" method="post">
            <fieldset>
                <div class="form-row">
                    <label>Company Name:</label>
                    <div class="content">
                        <select name="company_id">
                            <option value="">All</option>  
                          <?php foreach($companies as $company){?>  
                              <option value="<?php echo $company->getId();?>" <?php echo ($company->getId()==$company_id)?'selected="selected"':'';?> ><?php echo $company->getName();?></option>
                          <?php }?>    
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <label>Billing Duration:</label>
                    <div class="content">
                        <select name="billingduration">
                            <option value="">All</option>
                            <?php
                            foreach($invoiceTimings as $invoiceTiming){
                            $duration_value = $invoiceTiming->getBillingStartingDate()."_".$invoiceTiming->getBillingEndingDate(); ?>
                            <option value="<?php echo $duration_value;?>" <?php echo ($duration_value==$billingduration)?'selected="selected"':''; ?>><?php echo $invoiceTiming->getBillingStartingDate("j M y")." - ".$invoiceTiming->getBillingEndingDate("j M y");?></option>
                            <?php   }?>
                        </select>
                    </div>
                </div>
            </fieldset>
            <ul class="sf_admin_actions">
                <li><input type="submit" name="callhistoryfilter" value="Filter" class="user_external_link" /></li>
            </ul>
        </form>
    </div>
    <table cellpadding="3" cellspacing="0" class="tblAlign" width="100%">
        <tr class="headings">
            <th>ID</th>
            <th>Invoice Number</th>
            <th>Billing Duration</th>
            <th>Company Name</th>
            <th style="text-align:right">Invoice Total</th>
            <th style="text-align:right">Total Payable</th>
<!--        <th>Paid Amount</th>
            <th>To be paid</th>
            <th>Status</th>-->
            <th>View HTML</th>
<!--            <th>Action</th>-->
        </tr>
<?php
    $increment = 1;
    $total = 0.00;
    $totalNet = 0.00;
    $totalpayable = 0.00;
    $records = count($invoices);
    foreach($invoices as $invoice){
        if($increment%2==0){
            $class= 'class="even"';
        }else{
            $class= 'class="odd"';
        }
?>
        <tr <?php echo $class;   ?>>
            <td><?php echo $records;?></td>
            <td><?php echo $invoice->getId();?></td>
            <td><?php echo date('j M Y',strtotime($invoice->getBillingStartingDate()));?> - <?php echo date('j M Y',strtotime($invoice->getBillingEndingDate()));?></td>
            <td><?php echo $invoice->getCompany()->getName();?></td>
            <td align="right"><?php
                    echo number_format($invoice->getTotalPayment(),2);
                    $total += $invoice->getTotalPayment();
                ?><?php echo sfConfig::get('app_currency_code');?>
            </td>
            <td align="right"><?php
                    echo number_format($invoice->getTotalPayableBalance(),2);
                    $totalpayable += $invoice->getTotalPayableBalance();
                ?><?php echo sfConfig::get('app_currency_code');?>
            </td>
<!--            <td>
                <?php echo $invoice->getPaidAmount(); ?>
            </td>
            <td>
                <?php
                    echo number_format($netPayment = $invoice->getNetPayment(),2);
                    $totalNet += $netPayment;
                ?>
            </td>
           <td style="text-transform: capitalize;">
                <?php
                    $cis = new Criteria();
                    $cis->add(InvoiceStatusPeer::ID,$invoice->getInvoiceStatusId());
                    $status = InvoiceStatusPeer::doSelectOne($cis);
                    echo $status->getName();
                ?>
            </td>-->
            <td align="center"><?php if($invoice->getInvoiceHtml()!="" && $invoice->getTotalPayment() > 0){?>
                <a href="showInvoice?id=<?php echo $invoice->getId();?>" target="_blank">View</a>
                <?php } ?>
            </td>
<!--            <td><a href="refill?id=<?php echo $invoice->getId();?>">Payment</a></td>-->
        </tr>
    <?php
        $records -=1;
        $increment += 1;
    }
    ?>
        <tr>
            <td colspan="3"></td>
            <td><strong>Total</strong></td>
            <td align="right"><strong><?php echo number_format($total,2);?><?php echo sfConfig::get('app_currency_code');?></strong></td>
            <td align="right"><strong><?php echo number_format($totalpayable,2);?><?php echo sfConfig::get('app_currency_code');?></strong></td>
           <td></td>
          <!--   <td><strong><?php echo number_format($totalNet,2);?></strong></td>
            <td colspan="4"></td>-->
        </tr>
    </table>
</div>

