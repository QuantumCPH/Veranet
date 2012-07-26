<?php
use_helper('Number');
?>
 <?php ob_start(); ?>
<html><head>
        <title><?php echo date('dmy') . $invoice_meta->getId() ?>- <?php echo $company_meta->getName() ?></title>
        <style type="text/css">
            .invoice {
                border: 1px solid #f0f0f0;
            }
            .invoice td{
                border: 0 !important;
            }
        </style>
       
        <style type="text/css">


            .invoice *
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
            }



            .group_data td.first
            {
                text-indent: 20px;
            }

            .footer
            {
                height:25px;
                font-style:italic;
            }

            .footer.grandtotal
            {
                background: #f0f0f0;

            }
        </style>
    </head>
    <body>



        <table class='invoice' width="100%">
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
                <td width="50%" align="right"> <table border="0"  class="invoice_meta" width="200">
                        <tr> <td> Veranet </td></tr>
                        <tr> <td></td></tr>
                        <tr> <td></td></tr>
                        <tr> <td>  </td></tr>
                        <tr> <td>  </td></tr>
                        <tr> <td></td></tr>
                        <tr> <td> </td></tr>
                        <tr> <td> </td></tr>
                        <tr> <td> </td></tr>
                        <tr> <td></td></tr>





                    </table>

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
                        <tr class="summary_header" style="border: 1px solid #000;">
                            <td width="50%">Product</td>
                            <td width="16%"align="left"></td>
                            <!--                                        Produkt pris-->
                            <td align="16%">&nbsp; </td>
                            <td align="18%">Charged Amount(<?php echo sfConfig::get('app_currency_code')?>.)</td>
                        </tr>
                        <?php
                        $totalcost = 0.00;
                        $totalSubFee = 0.00;
                        $totalRegFee = 0.00;
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

//                        echo $billing_start_date;
//                        echo '<br />';
//                        echo $billing_end_date;
                            
                        foreach ($employees as $employee) {
                            $regFlag = false;
                            $subFlag = false;
                            $billingFlag = false;
                        ?>

                        <?php
                            $prdPrice = 0;
                            
                            $startdate = strtotime($billing_start_date);
                            $enddate = strtotime($billing_end_date);

                            $employeeCreatedDate = strtotime($employee->getCreatedAt());
                           

                         $empRegPrd = null;
                            $ers = new Criteria();
                            $ers->add(EmployeeRegSubPeer::EMPLOYEE_ID, $employee->getId());
                            $ers->addAnd(EmployeeRegSubPeer::BILL_START, $billing_start_date);
                            $ers->addAnd(EmployeeRegSubPeer::BILL_END, $billing_end_date);

                            if (EmployeeRegSubPeer::doCount($ers) > 0) {
                                $empRegPrd = EmployeeRegSubPeer::doSelectOne($ers);
                                $prdPrice = $empRegPrd->getRegFee();
                                if ($prdPrice>0)
                                    $regFlag = true;
                                
                            } else {
                                if($employeeCreatedDate<=$enddate){
                                    emailLib::sendErrorInTelinta("Employee not found in Reg Sub Fee Table for Registartion Invoice", "Employee not found in Reg Sub Fee Table start date: $billing_start_date , Enddate: $billing_end_date , EmployeeId:". $employee->getId());
                                }
                            }




                               ?>



                        <?php if ($regFlag || $subFlag || $billingFlag) {
                        //if ($subFlag || $billingFlag) {
                        ?>
                                        <tr>
                                            <td><br/>
                                                <b><u><?php echo 'From Number: ' . $employee->getMobileNumber() ?> </u> </b>
                                                <br/><br/>
                                            </td>
                                        </tr>



                        <?php } ?>


                        <?php if ($regFlag) {
                        ?>
                                        <tr>
                                            <td>

                                                Registered Product: <?php echo $empRegPrd->getProductName(); ?>

                                            </td>
                                            <td>
                                                1
                                            </td>
                                            <td>

                                            </td>
                                            <td>
                                <?php $totalcost+=$prdPrice;
                                      $totalRegFee +=$prdPrice;
                                        echo number_format($prdPrice, 2); ?>
                                    </td>
                                </tr>
                        <?php $invoiceFlag = true; 
                        } 



                                } $invoice_cost = ($invoiceFlag)?$invoice_cost:'0.00'; ?>
                        <tr><td colspan="4"><hr/></td></tr>
                        <tr><td colspan="2">Total cost</td> <td></td><td><?php echo number_format($totalcost, 2) ?></td></tr>
                        <tr><td  colspan="2">Invoice Cost</td><td></td><td><?php echo number_format($invoice_cost, 2) ?></td></tr>
                        <tr><td colspan="4"><hr/></td></tr>
                        <tr><td colspan="2">Total Inc. invoice cost</td> <td></td><td><?php echo number_format($net_cost = $totalcost + $invoice_cost, 2); ?></td></tr>
                        <tr><td colspan="2">Vat</td> <td></td><td><?php echo number_format($moms = $net_cost * 25 / 100, 2); ?></td></tr>
                        <tr><td colspan="4"><hr/></td></tr>
                        <tr><td colspan="2">Total Inc. Vat</td> <td></td><td><?php echo number_format($net_cost = $net_cost + $moms, 2); util::saveTotalPayment($invoice_meta->getId(),$net_cost); ?></td></tr>
                        <tr><td colspan="4"><hr/></td></tr>
                    </table>
                </td></tr>
        </table>

    </body>

</html>
    <?php
  $html_content = ob_get_contents();
	//echo $html_content;
	util::saveHtmlToInvoice($invoice_meta->getId(), $html_content);

        $ci = new Criteria();
        $ci->add(InvoicePeer::ID,$invoice_meta->getId());
        $in = InvoicePeer::doSelectOne($ci);
//        $in->setSubscriptionFee($totalSubFee);
        $in->setMoms($moms);
        $in->setTotalusage($totalcost);
        $in->setRegistrationFee($totalRegFee);
        $in->setInvoiceCost($invoice_cost);
        $in->setNetPayment($net_cost);
        $in->setInvoiceStatusId(1);
        $in->save();
        
        $fileName = str_replace("/", "_", $in->getCompany()->getName());
        $fileName = str_replace(" ", "_", $fileName);
        
        $fileName = $in->getId().'-'.$fileName;
	
        util::html2pdf($invoice_meta->getId(),$html_content,$fileName);
?>

