<?php
use_helper('Number');
?>
<?php ob_start(); ?>
<html><head><title><?php echo date('dmy') . $invoice_meta->getId() ?>- <?php echo $company_meta->getName() ?></title>
<style type="text/css">.invoice { border: 1px solid #f0f0f0; }.invoice td{ border: 0 !important; }</style>
<style type="text/css">.invoice *
            {
                font-family: calibri, verdana, "Courier New", Courier, mono;
                font-size:16px;
            }
            .invoice .call_summary
            {
                margin-top: 10px;
            }
            .summary_header
            {
                /*background: #c0c0c0;*/
                font-weight: bold;
            }
            .group_header
            {
                /*background: #f0f0f0;*/
                font-weight: bold;
            }
            .summary_header, .group_header, .group_subheader
            {
                height: 30px;
                border-bottom:1px solid #000;
            }
            .group_subheader td.first
            {
                text-indent: 10px;
                font-weight: bold;
            }.group_data td.first
            {
                text-indent: 20px;
            }.footer
            {
                height:25px;
                font-style:italic;
            }
            .footer.grandtotal
            {
                background: #f0f0f0;
            }
        </style> </head> <body><table class='invoice' width="100%" style="page-break-inside: auto;">
            <tr>
                <td align="right" colspan="2">
                    <?php echo image_tag('/images/zapna_logo_small.jpg', 'absolute=true') ?>
                </td>
            </tr>
            <tr>
                <td valign="top" width="50%">
                    <?php echo $company_meta->getName() ?><br />
                    <?php echo $company_meta->getAddress() ?><br />
                    <?php echo $company_meta->getPostCode() ?>,
                    <?php echo $company_meta->getCity() ?><br />
	    Att: <?php echo $company_meta->getContactName() ?>
                </td>
                <td width="50%" align="right"> 
                    <table border="0"  class="invoice_meta" width="200">
                        <tr> <td> Zapna Aps </td></tr>
                        <tr> <td>Smedeholm 13 B</td></tr>
                        <tr> <td>2730 Herlev</td></tr>
                        <tr> <td>  Tel: +45 7027 9966</td></tr>
                        <tr> <td>  Fax: +45 7027 9965</td></tr>
                        <tr> <td>CVR: 32068219</td></tr>
                        <tr> <td>  Bank: Danske Bank</td></tr>
                        <tr> <td> Reg: 3001</td></tr>
                        <tr> <td>  Konto: 0010408563</td></tr>
                        <tr> <td></td></tr></table>
                </td>
            </tr>
            <tr>
                <td>&nbsp; </td>
                <td align="right">
                     <table class="invoice_meta">
                        <tr>
                            <td colspan="2">Faktura</td>
                        </tr>
                        <tr>
                            <td>Faktura Nummer:</td>
                            <td><?php echo $invoice_meta->getId(); ?></td>
                        </tr>
                        <tr>
                            <td>Faktura Periode:</td>
                            <td><?php echo $invoice_meta->getBillingStartingDate('d M.') . ' - ' . $invoice_meta->getBillingEndingDate('d M.') ?></td>
                        </tr>
                        <tr>
                            <td>Faktura Dato:</td>
                            <td><?php echo $invoice_meta->getCreatedAt('d M. Y') ?></td>
                        </tr>
                        <tr>
                            <td>Betalingsdato:</td>
                            <td><?php echo $invoice_meta->getDueDate('d M. Y') ?></td>
                        </tr>
                        <tr>
                            <td>Kundenummer:</td>
                            <td><?php echo $company_meta->getVatNo() ?></td>
                        </tr>
                    </table> <!-- end invoice meta -->
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="call_summary" width="100%" cellspacing="0">
                        <tr class="summary_header" style="border: 1px solid #000;">
                            <td width="50%">Produkt beskrivelse</td>
                            <td width="16%"align="left">Antal opkald enheder</td>
                            <!--                                        Produkt pris-->
                            <td align="16%">&nbsp; </td>
                            <td align="18%">Charged Amount (<?php echo sfConfig::get('app_currency_code')?>.)</td>
                        </tr>
                        <?php
                        $totalcost = 0.00;
                        $totalSubFee = 0.00;
                        $fromNumber = "";
                        $rateTableId = '';
                        //$call_rate_table = CallRateTablePeer::doSelect(new Criteria());
                        ?>
                        <?php
                        $billings = array();
                        $ratings = array();
                        $bilcharge = 00.00;
                        $invoiceFlag = false;
                        $count = 1;
                        $billing_details = array();
                        foreach ($employees as $employee) {                            
                            $subFlag = false;
                            $billingFlag = false;
                        ?>
                        <?php
                            $prdPrice = 0;
                            $startdate = strtotime($billing_start_date);
                            $enddate = strtotime($billing_end_date);
                            $employeeCreatedDate = strtotime($employee->getCreatedAt());                           
                        ?><?php
                            $bc = new Criteria();
                            $bc->add(EmployeeCallhistoryPeer::EMPLOYEE_ID, $employee->getId());
                            $bc->addAnd(EmployeeCallhistoryPeer::CONNECT_TIME, " connect_time > '" . $billing_start_date . "' ", Criteria::CUSTOM);
                            $bc->addAnd(EmployeeCallhistoryPeer::CONNECT_TIME, " connect_time  < '" . $billing_end_date . "' ", Criteria::CUSTOM);
                            $bc->addGroupByColumn(EmployeeCallhistoryPeer::COUNTRY_ID);
                            if (EmployeeCallhistoryPeer::doCount($bc) > 0) {
                                $billingFlag = true;
                            }
                        ?>
                        <?php
                            if ($subFlag || $billingFlag) {
                        ?><tr><td><br/><b><u><?php echo 'From Number: ' . $employee->getMobileNumber() ?> </u> </b><br/><br/></td></tr>
<?php } ?><?php if ($subFlag) { ?>
                                <tr>
                                    <td>
                                        Abonnement: <?php echo $employee->getProduct()->getName(); ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td><?php
                                echo $subFee; 
                                $totalSubFee +=$subFee;$totalcost+=$subFee;
                                $invoiceFlag = true;
                                ?>
                            </td>
                        </tr> <?php } ?><?php
                            if ($billingFlag) {
                                $invoiceFlag = false;
                                $billings = EmployeeCallhistoryPeer::doSelect($bc);
                                foreach ($billings as $billing) {?><tr><td>
                                <?php echo $billing->getCountry()->getName()//.'-'.$billing->getCountryId(); ?>
                                        </td>
                                        <td><?php
                                    $dc = new Criteria();
                                    $dc->add(EmployeeCallhistoryPeer::EMPLOYEE_ID, $employee->getId());
                                    $dc->add(EmployeeCallhistoryPeer::COUNTRY_ID,$billing->getCountryId());
                                    $dc->addAnd(EmployeeCallhistoryPeer::CONNECT_TIME, " connect_time > '" . $invoice_meta->getBillingStartingDate('Y-m-d 00:00:00') . "' ", Criteria::CUSTOM);
                                    $dc->addAnd(EmployeeCallhistoryPeer::CONNECT_TIME, " connect_time  < '" . $invoice_meta->getBillingEndingDate('Y-m-d 23:59:59') . "' ", Criteria::CUSTOM);
                                  //  $dc->addGroupByColumn(EmployeeCallhistoryPeer::COUNTRY_ID);
                                    $temp = EmployeeCallhistoryPeer::doSelect($dc);
                                    $minutes_count = 0;
                                    $calculated_cost = 0;
                                    foreach ($temp as $t) {
                                        $calculated_cost += $t->getChargedAmount();
                                    }
                                ?>
                                <?php //echo $minutes_count ?>
                                </td><td>&nbsp;</td>
                                <td>
                                <?php echo  number_format($temp_cost = $calculated_cost, 2) ?>
                                </td>
                            </tr><?php  $totalcost += $temp_cost;
                            }
?>
<?php }
                        }  $invoice_cost = ($invoiceFlag) ? $invoice_cost : '0.00'; ?><tr><td colspan="4"><hr/></td></tr>
                        <tr><td colspan="2">Forbrug i alt</td> <td></td><td><?php echo number_format($totalcost, 2) ?></td></tr>
<!--                        <tr><td  colspan="2">Faktura gebyr</td><td></td><td><?php echo number_format($invoice_cost, 2) ?></td></tr>-->
                        <tr><td colspan="4"><hr/></td></tr>
<!--                        <tr><td colspan="2">Forbrug i alt</td> <td></td><td><?php echo number_format($net_cost = $totalcost + $invoice_cost, 2); ?></td></tr>
                        <tr><td colspan="2">Moms</td> <td></td><td><?php echo number_format($moms = $net_cost * 25 / 100, 2); ?></td></tr>-->
                        <tr><td colspan="4"><hr/></td></tr>
                        <tr><td colspan="2">Pris inkl. Moms</td> <td></td><td><?php echo number_format($net_cost = $net_cost, 2);  ?></td></tr>
                        <tr><td colspan="4"><hr/></td></tr>
                    </table>
                </td></tr>
        </table>
    </body>
</html>
    <?php
        util::saveTotalPayment($invoice_meta->getId(),$net_cost);
        $html_content = ob_get_contents();
	util::saveHtmlToInvoice($invoice_meta->getId(), $html_content);
        $ci = new Criteria();
        $ci->add(InvoicePeer::ID,$invoice_meta->getId());
        $in = InvoicePeer::doSelectOne($ci);
       // $in->setSubscriptionFee($totalSubFee);
       // $in->setRegistrationFee($totalRegFee);
      //  $in->setMoms($moms);
     //   $in->setTotalusage($totalcost);
     //   $in->setInvoiceCost($invoice_cost);
       // $in->setNetPayment($net_cost);
        $in->setInvoiceStatusId(1);
        $in->save();
        
        $fileName = str_replace("/", "_", $in->getCompany()->getName());
        $fileName = str_replace(" ", "_", $fileName);
        
        $fileName = $in->getId().'-'.$fileName;
	
        util::html2pdf($invoice_meta->getId(),$html_content,$fileName);
?>
