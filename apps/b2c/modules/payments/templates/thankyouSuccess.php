<?php use_helper('I18N') ?>



<table width="100%">   <tr> <td align="center"><?php include_partial('customer/dashboard_header', array('customer'=> null, 'section'=>__('Payment Confirmation')) ) ?> </td></tr>
<tr><td align="center">

	<div align="center" style="margin:20px auto">
	<?php
		echo "<p>";
                echo __("Thank you for registeration your account at %1% . You will soon receive a delivery confirmation",array('%1%'=>sfConfig::get('app_site_title')));
                echo "</p>";
                echo "<p>";
		echo __("If you have any questions please feel free to contact our customer support center at");
                echo " <a href='mailto:".sfConfig::get('app_support_email_id')."'>".sfConfig::get('app_support_email_id')."</a></p>";
               
	?>
	.

  </div> <!-- end left-col -->
  <?php //include_partial('customer/sidebar') ?>
  </td></tr>
  </table>