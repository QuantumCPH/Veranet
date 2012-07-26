

<script type="text/javascript">
jQuery(document).ready ( function() {
    jQuery('#start_date').datepicker({
    	duration: '',
        showTime: false,
        constrainInput: false,
        time24h: false
     });
    jQuery('#end_date').datepicker({
    	duration: '',
        showTime: false,
        constrainInput: false,
        time24h: false
     });
     
 	var d = new Date();
	
	with(d)
	{
		setMonth(getMonth());
		setDate(15);
	}

	
	//jQuery('#start_date').val('<?php echo $suggested_billing_start_date; ?>');
	
	
	d = new Date();
	d.setMonth(d.getMonth()+1);
	d.setDate(14);
	
	jQuery('#end_date').val(d.getMonth()+'/'+d.getDate()+'/'+d.getFullYear());
	
});
</script>
<style type="text/css">
	body {
		font-family: arial;
	}
	
	form {
		padding:10px;
		margin:5px;
	}
</style>
<div id="sf_admin_container">

<?php if ($sf_user->hasFlash('notice')): ?>
	<div class='notice'>
	  <?php echo $sf_user->getFlash('notice') ?>
	</div>
<?php endif; ?>
<?php  // echo form_tag('invoice/generate') ?>
<?php echo form_tag('company/companyEmployeeSubscription') ?>

<label>Billing Start date/time</label>
<input type="text" id='start_date' name='start_date' value='<?php echo date('Y-m-d', strtotime('-1 Month'));?>' />
<br /><br />
<label>Billing End date/time</label>
<input type="text" id='end_date' name='end_date' value="<?php echo date('Y-m-d');?>" />

<?php echo input_hidden_tag('company_id', 1) ?>
<input type="submit" value="Generate Invoice" />
</form>
</div>