
  <div class="left-col">
	<div align="center" style="margin:50px auto">
	<?php
		echo "<p>" .__("Thank you for recharging your account at %1%. You will receive a confirmation email along with invoice in few moments.", array('%1%' =>sfConfig::get('app_site_title')))."</p>";
		echo "<p>" .__("Your account balance will be updated as soon as possible.")."</p>";
		echo "<p>" .__("For any questions please feel free to contact us at");
	?>
	<a href="mailto:<?php echo sfConfig::get('app_support_email_id');?>"><?php echo sfConfig::get('app_support_email_id');?></a>.
	</div>
  </div> <!-- end left-col -->
