<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>


<script type="text/javascript">
function countChar(str)
{
  var chars = document.getElementById('chars');
  chars.value = "Characters: "+str.length+"/434";
}

</script>

	  <script type="text/javascript">
	    $(document).ready(function() {
	      $("#form1").validate({
	        rules: {
	          name: {
                     required: true,
                      maxlength:50
              },// simple rule, converted to {required:true}

	          email: {// compound rule
	          required: true,
	          email: true
	        },
	        message: {
	          required: true
	        },
	        phone: {
	          number: true,
                  minlength:8
	        }
	        },
	        messages: {
	          message: "Please enter a comment."
	        }
	      });
	    });

            
	  </script>
           <style type="text/css">
            

	    .submit { margin-left: 125px; margin-top: 10px;}
	    .label { display: block; float: left; width: 90px; text-align: right; margin-right: 5px; }
	    .form-row { padding: 5px 0; clear: both; width: 700px; }
	    label.error { width: 250px; display: block; float: left; color: red; padding-left: 0px;font-size:12px;}
	    input[type=text], text { width: 200px;  }
            input[type=textarea], textarea { width: 400px;  }
	    textarea { height: 80px; }
          
	  </style>


<?php include_partial('dashboard_header', array('customer'=> $customer, 'section'=>__('Refill') ) ) ?>
<?php

            if(isset ($_POST['email']) && isset ($_POST['name'])&& isset ($_POST['message'] ))
            {?>
<div class="alert_bar">
	
              <?php echo __("Your invitation to ").$_POST['name'].__(" has been sent."); ?>

            

</div>
<?php }

?>
<div class="left-col">
    <?php include_partial('navigation', array('selected'=>'', 'customer_id'=>$customer->getId())) ?>
	
  
<br/>
<br/><br/>&nbsp;<br/>&nbsp;
<div class="tipafriend">
<center>
    <h1 style="font-family: Verdana; font-size: 18px; line-height: 20px; text-align: left; padding-top: 10px;"> <?php echo __('Invite a friend to register as a %1% customer',array('%1%'=>sfConfig::get('app_site_title'))); ?></h1>
</center>
<br/>
<h3><?php echo __('Recommend %1% to your friends and earn extra airtime.',array('%1%'=>sfConfig::get('app_site_title')));?></h3>
<p style="align:justified;"><?php echo __('%1% will add 10.00%2% of airtime to your account balance for each new customer you invite and who registers as a %1% customer.',array('%1%'=>sfConfig::get('app_site_title'),'%2%'=>sfConfig::get('app_currency_code')));?></p>
<br/>
<h3><?php echo __('How?'); ?></h3>
<p style="align:justified;"><?php //echo __('You can tell your friends about %1% in two simple ways:',array('%1%'=>sfConfig::get('app_site_title'))); ?><!--<br />-->
    
<?php //echo __('Fill out the fields below and click the Send Email button - your friend will receive an Email') ?>
  <?php echo __('It is easy:');?><br />
- <?php echo __('Fill in the contact details of your friend in the fields below');?><br />
- <?php echo __('Compose your message');?><br />
- <?php echo __('Send your invitation.')?><br />
</p>
<br/>
<h3><?php echo __("Your benefits.") ?></h3>
<p style="align:justified;"><?php echo __("%1% will automatically add 10.00%2% of free airtime to your account balance, when your friend has registered as a %1% customer and paid for the Starter Package.",array('%1%'=>sfConfig::get('app_site_title'),'%2%'=>sfConfig::get('app_currency_code'))); ?> </p>
</div>
<div class="split-form">
      <div class="fl col">
	    <form  id="form1" method="POST" action="<?php echo url_for('customer/tellAFriend', true) ?>">
                <table>
                    <tr>
                        <td><?php echo __("Your friend's full name") ?></td>
                        <td><?php echo __("Your friend's Spanish mobile number") ?></td>
                    </tr>
                    <tr><td><input type="text" name="name" /><span>&nbsp;</span></td><td><input type="text" name="phone" /><br />ex. 0701234567</td>
                    </tr>
                    <tr> 
                        <td ><?php echo __("Your friend's e-mail address") ?></td>
                        <td ><?php //echo __("Your Friend's Country") ?></td>
                    </tr>
                    
                    <tr> 
                        <td ><input type="text" name="email" /></td>
                        <td style="display:none;" ><input type="text" name="country" value="Spain" disabled /></td>
                    </tr>
                    <tr>
                        <td  colspan="2" align="center"><?php echo __("Your message") ?></td>

                    </tr>
                    <tr>
                        <td  colspan="2" align="center"><textarea name="message" ><?php echo __("You can save up to 80% on international calls from your Spanish mobile telephone if you are registered as a %1% customer. It is easy to register and use, and you do not need to change your mobile number or mobile service provider. Please go to %1%'s web site: www.kimarineurope.com and read more.",array('%1%'=>sfConfig::get('app_site_title'),'%2%'=>sfConfig::get('app_currency_code')));?> </textarea></td>
                    </tr>
                </table><br />
                <input type="submit" class="butonsigninsmall" style="margin-left: 0px !important;" name="submit" value="<?php echo __('Send invitation') ?>" />
	     
	    </form>
	  
    </div>
  </div>
          </div>
 <?php include_partial('sidebar') ?>
<script>
//$(".dashboard").innerHTML("");
$('.dashboard').html('');

//$(".alert_bar").css("display","none");
</script>
