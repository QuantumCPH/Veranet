<script type="text/javascript">
    jQuery(function() {
        jQuery( "#trigger_startdate" ).hide();
    });
</script>
<div id="sf_admin_container"><h1>Payment</h1></div>

<form id="sf_admin_form" name="sf_admin_edit_form" method="post" enctype="multipart/form-data" action="Refill">
    <div id="sf_admin_content">
        <table style="padding: 0px;"  id="sf_admin_container" class="tblAlign" cellspacing="0" cellpadding="2" >
            <tr>
                <td style="padding: 5px;">Company:</td>
                <td style="padding: 5px;">
                    <select name="company_id"   class="required" onchange="new Ajax.Updater('invoice', 'invoice', {asynchronous:true, evalScripts:false, parameters:'company_id=' + this.options[this.selectedIndex].value});">
                       <option value="">All</option>
                       <?php 
                       foreach($company as $companies){
                       ?>
<!--                        <option value="<?php echo $companies->getId();?>" <?php if($invoiceSelect!=''){echo ($companies->getId()==$invoiceSelect->getCompanyId())?'selected="selected"':'';}?>><?php echo $companies->getName();?></option>-->
                        <option value="<?php echo $companies->getId();?>"><?php echo $companies->getName();?></option>
                       <?php }?>
                    </select>
                </td>
            </tr>
<!--            <tr>
                <td style="padding: 5px;">Invoices:</td>
                <td style="padding: 5px;">
                    <select name="invoice_id" id="invoice" onchange="new Ajax.Updater('amount', 'amount', {asynchronous:true, evalScripts:false, parameters:'invoice_id=' + this.options[this.selectedIndex].value});">
                       <option value="">All</option>
                       <?php echo ($invoiceSelect!='')?'<option value="'.$invoiceSelect->getId().'"selected="selected">'.$invoiceSelect->getInvoiceNumber(). "--". date("d M Y", strtotime($invoiceSelect->getBillingStartingDate()))."-".date("d M Y", strtotime($invoiceSelect->getBillingEndingDate())).'</option>':'<option value="">Select Company</option>';?>
                    </select>
                </td>
            </tr>
            <tr>
                <td style="padding: 5px;">Amount:</td>
                <td style="padding: 5px;">
                   <span id="amount"><?php echo ($invoiceSelect!='')?$invoiceSelect->getNetPayment():'0';?></span>
                </td>
            </tr>-->
            <tr>
                <td style="padding: 5px;">Payment Received:</td>
                <td style="padding: 5px;">
                    <input type="text" id="refill" name="refill" class="required digits"><?php echo sfConfig::get('app_currency_code');?>
                </td>
            </tr>
<!--            <tr>
                <td style="padding: 5px;">Payment Received on:</td>
                <td style="padding: 5px;">
                    <?php echo input_date_tag('startdate', date("Y-m-d"), 'rich=true') ?>
                </td>
            </tr>-->
        </table>
        <div id="sf_admin_container">
            <ul class="sf_admin_actions">
                <li><input type="submit" name="save" value="save" class="sf_admin_action_save" /></li>
            </ul>
        </div>
    </div>
</form>


