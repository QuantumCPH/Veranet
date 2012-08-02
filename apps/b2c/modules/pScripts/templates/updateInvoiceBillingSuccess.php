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
                    <?php echo image_tag(sfConfig::get('app_web_url').'images/logo.png',array('width' => '170'));?>
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
                        <tr> <td>Veranet</td></tr>
                        <tr> <td></td></tr>
                        <tr> <td></td></tr>
                        <tr> <td></td></tr>
                        <tr> <td></td></tr>
                        <tr> <td></td></tr>
                        <tr> <td></td></tr>
                        <tr> <td></td></tr>
                        <tr> <td></td></tr>
                        <tr> <td></td></tr></table>
                </td>
            </tr>
            <tr>
                <td>&nbsp; </td>
                <td align="right">
                     <table class="invoice_meta">
                        <tr>
                            <td colspan="2">invoice</td>
                        </tr>
                        <tr>
                            <td>Invoice Number:</td>
                            <td><?php echo $invoice_meta->getId(); ?></td>
                        </tr>
                        <tr>
                            <td>Invoice Period:</td>
                            <td><?php echo $invoice_meta->getBillingStartingDate('d M.') . ' - ' . $invoice_meta->getBillingEndingDate('d M.') ?></td>
                        </tr>
                        <tr>
                            <td>Invoice Date:</td>
                            <td><?php echo $invoice_meta->getCreatedAt('d M. Y') ?></td>
                        </tr>
                        <tr>
                            <td>Due date:</td>
                            <td><?php echo $invoice_meta->getDueDate('d M. Y') ?></td>
                        </tr>
                        <tr>
                            <td>Customer Number:</td>
                            <td><?php echo $company_meta->getVatNo() ?></td>
                        </tr>
                    </table> <!-- end invoice meta -->
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <table class="call_summary" width="100%" cellspacing="0">
                        <?php
                        $totalcost = 0.00;
                        $totalSubFee = 0.00;
                        $totalPayments = 0.00;
                        $totalEventFee = 0.00;
                        
                       
                        //$call_rate_table = CallRateTablePeer::doSelect(new Criteria());
                        ?>
                        <tr><td>
                                <table colspan="3" border="0" width="100%" style="border: 1px solid #000;">
                                     <tr class="summary_header">
                                        <td width="50%">Calls</td>
                                        <td width="16%"align="left">Duration</td>
                                        <!--                                        Produkt pris-->
                                        <td align="16%">&nbsp;</td>
                                        <td align="18%">Charged Amount (<?php echo sfConfig::get('app_currency_code')?>.)</td>
                                    </tr>
                                  
                        <?php
                        $billings = array();
                        $ratings = array();
                        $bilcharge = 00.00;
                        $invoiceFlag = false;
                        $count = 1;
                        $billing_details = array();
                        foreach ($employees as $employee) {                            
                            $subFlag = false;
                            $regFlag = false;
                            $billingFlag = false;
                        ?>
                        <?php
                            $prdPrice = 0;
                            $startdate = strtotime($billing_start_date);
                            $enddate = strtotime($billing_end_date);
                        ?><?php
                            $bc = new Criteria();
                            $bc->add(EmployeeCustomerCallhistoryPeer::PARENT_TABLE, "employee");
                            $bc->addAnd(EmployeeCustomerCallhistoryPeer::PARENT_ID, $employee->getId());
                            $bc->addAnd(EmployeeCustomerCallhistoryPeer::CONNECT_TIME, " connect_time > '" . $billing_start_date . "' ", Criteria::CUSTOM);
                            $bc->addAnd(EmployeeCustomerCallhistoryPeer::DISCONNECT_TIME, " disconnect_time < '" . $billing_end_date . "' ", Criteria::CUSTOM);
                            $bc->addGroupByColumn(EmployeeCustomerCallhistoryPeer::COUNTRY_ID);
                            if (EmployeeCustomerCallhistoryPeer::doCount($bc) > 0) {
                                $billingFlag = true;
                            }
                        ?>
                        <?php
                            if ($billingFlag) {
                        ?>
                       
                        <tr><td><br/><b><u><?php echo 'From Number: ' . $employee->getMobileNumber() ?> </u> </b><br/><br/></td></tr>
<?php
                                $invoiceFlag = true;
                                $billings = EmployeeCustomerCallhistoryPeer::doSelect($bc);
                                foreach ($billings as $billing) {?><tr><td>
                                <?php echo $billing->getCountry()->getName()//.'-'.$billing->getCountryId(); ?>
                                        </td>
                                        <td><?php
                                    $dc = new Criteria();
                                    $dc->add(EmployeeCustomerCallhistoryPeer::PARENT_TABLE, "employee");
                                    $dc->add(EmployeeCustomerCallhistoryPeer::PARENT_ID, $employee->getId());
                                    $dc->add(EmployeeCustomerCallhistoryPeer::COUNTRY_ID,$billing->getCountryId());
                                    $dc->addAnd(EmployeeCustomerCallhistoryPeer::CONNECT_TIME, " connect_time > '" . $invoice_meta->getBillingStartingDate('Y-m-d 00:00:00') . "' ", Criteria::CUSTOM);
                                    $dc->addAnd(EmployeeCustomerCallhistoryPeer::DISCONNECT_TIME, " disconnect_time  < '" . $invoice_meta->getBillingEndingDate('Y-m-d 23:59:59') . "' ", Criteria::CUSTOM);
                                  //  $dc->addGroupByColumn(EmployeeCallhistoryPeer::COUNTRY_ID);
                                    $temp = EmployeeCustomerCallhistoryPeer::doSelect($dc);
                                    $minutes_count = 0;
                                    $calculated_cost = 0;
                                    foreach ($temp as $t) {
                                        $calculated_cost += $t->getChargedAmount();
                                        $call_duration = EmployeeCustomerCallhistoryPeer::getTotalCallDuration($employee, $billing->getCountryId());
                                    }
                                ?>
                                <?php echo $call_duration ?>
                                </td><td>&nbsp;</td>
                                <td>
                                <?php echo  number_format($calculated_cost, 2) ;
                                $temp_cost = $calculated_cost;
                                ?>
                                </td>
                            </tr><?php  $totalcost += $temp_cost;
                            }
?>
                       <?php  
                         }
                       ?>
                        
                        <?php  
                        }  $invoice_cost = ($invoiceFlag) ? $invoice_cost : '0.00'; 
                        ?>  </table>  
                           </td></tr> 
                           <tr><td><br /><br />
                             <h2>Subscriptions</h2>
                                <table colspan="3" border="0" width="100%" style="border: 1px solid #000;">
                                    <tr>
                                        <th align="left">Mobile Number</th>
                                        <th align="left">Description</th>
                                        <th align="left">Amount (<?php echo sfConfig::get('app_currency_code')?>)</th>
                                    </tr>        
                           <?php
                             foreach ($employees as $emps) { 
                              $cSub = new Criteria();
                              $cSub->add(OdrsPeer::PARENT_TABLE,'employee');
                              $cSub->addAnd(OdrsPeer::PARENT_ID,$emps->getId());
                              $cSub->addAnd(OdrsPeer::I_SERVICE,4);
                              $scount = OdrsPeer::doCount($cSub);
                              if($scount > 0){
                               $subscriptions =  OdrsPeer::doSelect($cSub);
                           ?> 
                                    <?php
                                      foreach($subscriptions as $subs){
                                    ?><tr>
                                       <td><?php echo $emps->getMobileNumber();?></td>
                                       <td><?php echo $subs->getDescription();?></td>
                                       <td><?php echo number_format($subs->getChargedAmount(),2);$totalSubFee += $subs->getChargedAmount();?></td>
                                      </tr>  
                                    <?php      
                                      }
                                    ?>
                                
                           <?php        
                              }
                             } //end employee second foreach loop   
                           ?>   </table>   
                         </td></tr> 
                           <?php
                            if(isset ($otherevents) && $otherevents !=""){
                           ?>
                            <tr><td><br /><br />
                              <h2>Other Events</h2>
                            <table colspan="3" border="0" width="100%" style="border: 1px solid #000;">
                                <tr>
                                    <th align="left">Description</th>
                                    <th align="left">Amount (<?php echo sfConfig::get('app_currency_code')?>)</th>
                                </tr>
                                <?php
                                  foreach($otherevents as $event){
                                ?><tr>
                                   <td><?php echo $event->getDescription();?></td>
                                   <td align="left"><?php echo number_format($event->getChargedAmount(),2);$totalEventFee += $event->getChargedAmount();?></td>
                                  </tr>  
                                <?php      
                                  }
                                ?>
                            </table> <br />
                           </td></tr>         
                           <?php        
                              }
                            
                           $totalcost = $totalcost + $totalSubFee + $totalEventFee;
                          ?>
                          
                        <tr><td colspan="4"><hr/></td></tr>
                        <tr><td colspan="2"><b>Total cost</b></td> <td></td><td><?php echo number_format($totalcost, 2) ?></td></tr>
                        <tr><td  colspan="2"><b>Invoice Cost</b></td><td></td><td><?php echo number_format($invoice_cost, 2) ?></td></tr>
                        <tr><td colspan="4"><hr/></td></tr>
                        <tr><td colspan="2"><b>Total Inc. invoice cost</b></td> <td></td><td><?php echo number_format($net_cost = $totalcost + $invoice_cost, 2); ?></td></tr>
                        <tr><td colspan="2"><b>Vat</b></td> <td></td><td><?php echo number_format($moms = $net_cost * sfConfig::get("app_vat_percentage"), 2); ?></td></tr>
                        <tr><td colspan="4"><hr/></td></tr>
                        <tr><td colspan="2"><b>Total Inc. Vat</b></td> <td></td><td><?php echo number_format($net_cost = $net_cost + $moms, 2);echo "&nbsp;".sfConfig::get('app_currency_code');
                        util::saveTotalPayment($invoice_meta->getId(),$net_cost); ?></td></tr>
                        <tr><td colspan="4"><hr/></td></tr>
                        <tr><td colspan="2"><b>Previous Balance</b></td> <td></td><td><?php echo number_format($netbalance , 2);  ?></td></tr>
                        <tr><td colspan="2"><b>Total Payable Balance</b></td> <td></td><td><?php echo number_format($net_cost + $netbalance , 2);  ?></td></tr>
                        
                    </table>
                </td></tr>
                 <?php
                    if(isset ($payments) && $payments !=""){
                           ?>
                    <tr><td><br /><br />  <h2>Payment History</h2>                  
                    <table colspan="3" border="0" width="100%" style="border: 1px solid #000;">
                        <tr>
                            <th align="left">Description</th>
                            <th align="left">Airtime (<?php echo sfConfig::get('app_currency_code')?>) </th>
                            <th align="left">Vat (<?php echo sfConfig::get('app_currency_code')?>) </th>
                            <th align="left">Total (<?php echo sfConfig::get('app_currency_code')?>) </th>
                        </tr>
                        <?php
                          foreach($payments as $payment){
                        ?><tr>
                           <td><?php echo $payment->getDescription();?></td>
                           <td><?php echo number_format($chargedAmount = $payment->getChargedAmount(),2);$totalPayments += $payment->getVatIncludedAmount();?></td>
                           <td><?php echo number_format($vat = $payment->getVatIncludedAmount()-$chargedAmount,2);?></td>
                           <td><?php echo number_format($payment->getVatIncludedAmount(),2);?></td>
                          </tr>  
                        <?php      
                          }
                        ?>
                    </table><br />
                   </td></tr> 
                <?php
                  }
                ?>      
                 <?php
                    if(isset ($preInvoices) && $preInvoices !=""){
                           ?>
                    <tr><td><br /> <h2>Previous Invoices</h2>                  
                    <table colspan="3" border="0" width="100%" style="border: 1px solid #000;">
                        <tr>
                            <th align="left">Bill Duration</th>
                            <th align="left">Invoice Total (<?php echo sfConfig::get('app_currency_code')?>) </th>
                        </tr>
                        <?php
                          foreach($preInvoices as $preInvoice){
                        ?><tr>
                           <td><?php echo $preInvoice->getBillingStartingDate("M d");?> - <?php echo $preInvoice->getBillingEndingDate("M d");?></td>
                           <td><?php echo number_format($preInvoice->getTotalPayment(),2);?></td>
                          </tr>  
                        <?php      
                          }
                        ?>
                    </table><br />
                   </td></tr> 
                <?php
                  }
                ?>       
        </table>
    </body>
</html>
    <?php
        $html_content = ob_get_contents();
	util::saveHtmlToInvoice($invoice_meta->getId(), $html_content);
        $ci = new Criteria();
        $ci->add(InvoicePeer::ID,$invoice_meta->getId());
        $in = InvoicePeer::doSelectOne($ci);
        $in->setSubscriptionFee($totalSubFee);
        $in->setRegistrationFee($totalEventFee);
        $in->setPaymentHistoryTotal($totalPayments);
        $in->setMoms($moms);
        $in->setTotalusage($totalcost);
        $in->setCurrentBill($net_cost);
        $in->setInvoiceCost($invoice_cost);
        $in->setNetPayment($net_cost);
        $in->setInvoiceStatusId(1);
        $in->save();
        
        $fileName = str_replace("/", "_", $in->getCompany()->getName());
        $fileName = str_replace(" ", "_", $fileName);
        
        $fileName = $in->getId().'-'.$fileName;
	
        util::html2pdf($invoice_meta->getId(),$html_content,$fileName);
?>