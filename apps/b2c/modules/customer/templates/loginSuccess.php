<?php use_helper('I18N') ?>
<table><tr><td align="center">
<div class="loginheading"><?php include_partial('customer/dashboard_header', array('customer'=> null, 'section'=>__('MY ACCOUNT')) ) ?></div>
<div style="clear:both;height:1px;"></div>
	<?php if ($sf_user->hasFlash('send_password_message')): ?>
	<div class="alert_bar">
		<?php echo $sf_user->getFlash('send_password_message') ?>
	</div>
	<?php endif;?>
         <div class="maintext">
            <?php echo __('Welcome to MY ACCOUNT');?><br />
            <?php echo __('Here you can:');?><br />
            <ul class="welcome">
                <li><?php echo __('Refill your account.');?></li>
                <li><?php echo __('Change your settings.');?></li>
                <li><?php echo __('See your payment and call history.');?></li>
                <li><?php echo __('Order other products.');?></li>
            </ul>
            <?php //echo __('Hello and welcome to Smartsim - my pages. To log in, use your customer number which is your mobile number and password. On my pages you can see what calls you made, fill the pot, and more.'); //echo $target; ?>
         </div>
<div style="clear:both;height:1px;"></div>
  <?php //echo $sf_user->getCulture();
          if($sf_user->getCulture()=='en'){
              $class = 'class="texten"';
              $style = 'style ="display:block;height:18px;width: 148px;"';
              $clsLogin = "class='loginblock'";
          }elseif($sf_user->getCulture()=='de'){
              $class = 'class="textde"';
              $style = 'style ="display:block;height:18px;width: 148px;"';
              $clsLogin = "";
          }else{
              $class = 'class="textes"';
              $style = 'style ="display:block;height:18px;width: 148px;"';
              $clsLogin = "class='loginblock'";
          }
        ?>              
  <div class="loginpagediv">
    <?php //include_partial('customer/navigation', array('selected'=>'', 'customer_id'=>$customer->getId())) ?>
	<div id="login-modal" style="background-repeat: repeat-x; width: 470px; margin-top: 16px; background: none;">
	<div class="login-left">
	<h4  style="text-align:left;"><?php echo __('Enter MY ACCOUNT') ?></h4>
	<form method="post" id="login_form" action="<?php echo $target; ?>customer/login">
            <div <?php echo $clsLogin;?>> 
                <label  style="display:block;text-align:left;height:18px;"><?php echo __('Enter your mobile number') ?></label>
                <input type="text"  class="input"  name="mobile_number" id="mobile_number" />
                <p class="error_msg" style="color: red; margin-bottom:1px; position: relative; top: -2px;">
                <?php
                if ($sf_user->hasFlash('error_message')): ?>
                <?php echo $sf_user->getFlash('error_message'); ?>
                <?php
                endif;?>&nbsp;</p>
                <label style="text-align:left;"><?php echo __('Password') ?></label><br />
                <input  class="input" type="password" name="password" id="password" /><br />
                </div>
                <span>
                <input type="submit" class="loginbuttun" name="submit" value="<?php echo __('Log in') ?>"></span>
             
		<!--	<button style="cursor: pointer;" ><?php //echo __('Log in') ?></button>-->
	<script language="javascript" type="text/javascript">
		jq = jQuery.noConflict();
	
		jq('#login_form').submit(function(){
			var valid = true;

			valid = jq('#login_form #mobile_number').val().length>=8?true:false;
			
			
			if (!valid) { // if email is not valid
				jq('#login_form #mobile_number').focus();
				alert('<?php echo __('Enter a valid mobile number.') ?>');
				return false;			
			}
			
			valid = jq('#login_form #password').val().length>3?true:false;
			
			if (!valid) { // if password is not valid
				jq('#login_form #password').focus();
				alert('<?php echo __('Please enter your password.') ?>');
				return false;			
			}			
			

		});
	</script>
	</form>
	</div>
	<div class="login-right"><h4><?php echo __('Did you forget your password?') ?></h4>
	<form id="forgot_password_form" method="post" action="<?php echo url_for('customer/sendPassword') ?>">
        
        <label <?php echo $class;?> <?php echo $style;?>><?php echo __('Your e-mail address.');//echo __('Write e-mail address you used for registration.<br /><br />Your password will be sent to you via this email.') ?></label>
	<input   class="input"  type="text" name="email" id="forgot_password_email" /><br />
	<?php if ($sf_user->hasFlash('send_password_error_message')): ?>
	<p class="error_msg" style="color: red; margin:6px auto;"><?php echo $sf_user->getFlash('send_password_error_message') ?></p>
	<?php endif;?>
        <p class="yourpassword"><?php echo __('Your password will be sent to the above e-mail address shortly.') ?></p>
        <input  style="cursor: pointer;"  class="loginbuttun"  type="submit" name="submit" value="<?php echo __('Send');?>" />
        
        
<!--	<button style="cursor: pointer;">Send</button>-->
	<script language="javascript" type="text/javascript">
		jq = jQuery.noConflict();
	
		jq('#forgot_password_form').submit(function(){
			
			var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
			if (reg.test(jq('#forgot_password_email').val())==false)
			{
				jq('#forgot_password_email').focus();
				alert('<?php echo __('Please enter a valid email address.') ?>');
				return false;
			}

		});
	</script>
	</form></div>
	</div>
  </div> <!-- end left-col --><div style="clear:both;height:1px;"></div>
  <?php //include_partial('customer/sidebar') ?></td></tr></table>
 