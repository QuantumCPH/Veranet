<option value="">All</option>
<?php 
    foreach($invoice as $invoices){
?>
<option value="<?php echo $invoices->getId();?>"><?php echo $invoices->getInvoiceNumber(). "--". date("d M Y", strtotime($invoices->getBillingStartingDate()))."-".date("d M Y", strtotime($invoices->getBillingEndingDate()));?></option>
<?php }?>
                   