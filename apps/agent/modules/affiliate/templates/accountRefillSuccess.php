<script type="text/javascript">

    function checkForm()
    {
            
        var objForm = document.getElementById("refill");
        var valid = true;

        var amounts = document.getElementById("amount").value;
        var orderids = document.getElementById("orderid").value;
        var callbackurlstr = "<?echo sfConfig::get('app_customer_url'); ?>pScripts/agentRefillThankyou?p="+orderids+"-"+amounts;
        document.getElementById("callbackurl").value = callbackurlstr;
                
       /* if(isNaN(objForm.amount.value) || objForm.amount.value < <?php echo 0//$amount  ?>)
        {
            alert("<?php echo __('amount error') ?>!");
            objForm.amount.focus();

            valid = false;
        }

        if(objForm.cardno.value.length < 16)
        {
            $('#cardno_error').show();

            if (valid) //still not declarted as invaid
                objForm.cardno.focus();
            valid = false;
        }
        else {
            $('#cardno_error').hide();
        }

        if(isNaN(objForm.cvc.value) || objForm.cvc.value.length < 3 || objForm.cardno.cvc.length > 3)
        {
            $('#cvc_error').show();

            if (valid)
                objForm.cvc.focus();
            valid = false;
        }
        else {
            $('#cvc_error').hide();
        }

        return valid;
    }

    function toggleAutoRefill()
    {
        document.getElementById('user_attr_2').disabled = ! document.getElementById('user_attr_1').checked;
        document.getElementById('user_attr_3').disabled = ! document.getElementById('user_attr_1').checked;

    }

    $('#user_attr_3').blur(function(){
        if ( this.value<0 || this.value>400 || isNaN(this.value) )
            this.value = 0;
    });

    $(document).ready(function(){
        $('#cardno_error, #cvc_error').hide();

        toggleAutoRefill();
    });*/




</script>

<div id="sf_admin_container"><h1><?php echo __('Account Refill') ?></h1></div>
        
  <div class="borderDiv"> 
<form action="https://payment.architrade.com/paymentweb/start.action"  method="post" id="refill" onsubmit="return checkForm()">

    <table>

        <tr>
            <td>
                <label for="amount"><?php echo __('Select refill amount');?></label>
            </td>
            <td>
                <select name="amount" id="amount">
                    <option value="50000">500</option>
                    <option value="100000">1000</option>
                    <option value="150000">1500</option>
                </select>
            </td>
        </tr>
        <tr>
            <td >

            </td>
            <td align="right">
<!--                <input type="hidden" name="cmd" value="_xclick" /> 
                <input type="hidden" name="no_note" value="1" />
                <input type="hidden" name="lc" value="en" />
                <input type="hidden" name="currency_code" value="<?php echo sfConfig::get('app_currency_symbol');?>" />
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                <input type="hidden" name="rm" value="2" />
                <input type="hidden" name="firstName" value="<?php echo $agent->getContactName();?>"  />
                <input type="hidden" name="lastName" value="<?php echo $agent->getContactName();?>"  />
                <input type="hidden" name="payer_email" value="<?php echo $agent->getEmail();?>"  />
                <input type="hidden" name="item_number" value="<?php echo $agent_order->getAgentOrderId() ?>" />-->
            
                
                <input type="hidden" name="merchant" value="90049676" />
		<input type="hidden" name="currency" value="752" />
		<input type="hidden" name="orderid" id="orderid" value="<?php echo $agent_order->getAgentOrderId() ?>" />
                <input type="hidden" name="calcfee" value="yes" />
		<input type="hidden" name="account" value="YTIP" />
		<input type="hidden" name="status" value="" />
                <input type="hidden" name="lang" value="en_US" />   
                <input type="hidden" name="test" value="yes" />
		<input type="hidden" name="cancelurl" value="<?echo sfConfig::get('app_agent_url'); ?>affiliate/thankyou/?accept=cancel" />
		<input type="hidden" name="callbackurl" value="<?echo sfConfig::get('app_customer_url'); ?>pScripts/agentRefillThankyou?p=<?php echo $agent_order->getAgentOrderId() ?>-50000" id="callbackurl" />
		<input type="hidden" name="accepturl" value="<?echo sfConfig::get('app_agent_url'); ?>affiliate/accountRefill">
    
                <input type="submit" value="<?php echo __('Recharge');?>" style="margin-left:26px !important;margin-top:10px;" />
            </td>
        </tr>
    </table>

</form>
  </div>
