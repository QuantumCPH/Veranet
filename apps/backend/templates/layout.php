<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
  <link rel="shortcut icon" href="<?php echo sfConfig::get('app_web_url');?>images/favicon.ico" type="image/x-icon" />
    
    <script type="text/javascript">
    <!--
        // Copyright 2006-2007 javascript-array.com

        var timeout	= 500;
        var closetimer	= 0;
        var ddmenuitem	= 0;

        // open hidden layer
        function mopen(id)
        {
                // cancel close timer
                mcancelclosetime();

                // close old layer
                if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';

                // get new layer and show it
                ddmenuitem = document.getElementById(id);
                ddmenuitem.style.visibility = 'visible';

        }
        // close showed layer
        function mclose()
        {
                if(ddmenuitem) ddmenuitem.style.visibility = 'hidden';
        }

        // go close timer
        function mclosetime()
        {
                closetimer = window.setTimeout(mclose, timeout);
        }

        // cancel close timer
        function mcancelclosetime()
        {
                if(closetimer)
                {
                        window.clearTimeout(closetimer);
                        closetimer = null;
                }
        }

        // close layer when click-out
        document.onclick = mclose;
    -->

    </script>
  </head>
  <body>
    <?php 
    
      $modulName = $sf_context->getModuleName();
   
      $actionName = $sf_context->getActionName();
//     echo $modulName;
//     echo '<br />';
//     echo $actionName;
?>
  	<div id="wrapper">
  	<div id="header">  
         <div class="logo">
  		<?php echo image_tag('/images/logo.jpg') ?>
            </div>       
            <div class="clr"></div>
  	</div>
        <div class="clr"></div>
            <div style="width:75%;margin:0 auto;text-align: right;">
               <?php //echo link_to(image_tag('/images/german.png'), 'user/changeCulture?new=de'); ?>
               <?php //echo link_to(image_tag('/images/english.png'), 'user/changeCulture?new=en'); ?>
            </div>
      <?php if($sf_user->isAuthenticated()): 
          $sf_user->setCulture('en'); ?>
     <div class="topNav" align="center">  
      <ul id="sddm">
             <li><a href="#"
                onmouseover="mopen('m2')"
                onmouseout="mclosetime()" <?php echo $modulName=='company'||$modulName=='employee'? 'class = "current"':''?>><?php echo __('B2B') ?></a>
                <div id="m2"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">                    
                    <?php 
                    if($actionName=='list' && $modulName=="company"){
                       echo link_to(__('Companies list'), 'company/index', array('class'=>'subSelect'));
                    }else{
                       echo link_to(__('Companies list'), 'company/index'); 
                    }          
                    ?>                    
                    <?php 
                      if($actionName=='index' && $modulName=="employee"){
                          echo link_to(__('Employee lists'), 'employee/index', array('class'=>'subSelect'));
                      }else{
                          echo link_to(__('Employee lists'), 'employee/index');
                      }
                    ?>                    
                    <?php 
                      if($actionName=='paymenthistory' && $modulName=="company"){
                         echo link_to(__('Payment History'), 'company/paymenthistory', array('class'=>'subSelect'));
                      }else{
                         echo link_to(__('Payment History'), 'company/paymenthistory'); 
                      }?>
                    <?php 
                      if($actionName=='refill'){
                         echo link_to(__('Refill'), 'company/refill', array('class'=>'subSelect'));    
                      }else{
                          echo link_to(__('Refill'), 'company/refill');                          
                      } ?>
                </div>
            </li>
            <li>
                <a href="#"
                onmouseover="mopen('m5')"
                onmouseout="mclosetime()" <?php echo $modulName=='customer'? 'class = "current"':''?>>B2C<?php //echo __(sfConfig::get('app_site_title')) ?></a>
                <div id="m5"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
                    <?php 
                     if($actionName=="allRegisteredCustomer"){
                         echo link_to(__('All Registered Customer'), 'customer/allRegisteredCustomer', array('class'=>'subSelect')); 
                     }else{
                         echo link_to(__('All Registered Customer'), 'customer/allRegisteredCustomer');
                     }?>
                      <?php
                     if($actionName=="allBlockedCustomer"){
                         echo link_to(__('All Blocked Customer'), 'customer/allBlockedCustomer', array('class'=>'subSelect'));
                     }else{
                         echo link_to(__('All Blocked Customer'), 'customer/allBlockedCustomer');
                     }?>
                       <?php
                    if($actionName=='selectChargeCustomer' && $modulName=="customer"){
                       echo link_to(__('Charge Customer'), 'customer/selectChargeCustomer', array('class'=>'subSelect'));
                    }else{
                       echo link_to(__('Charge Customer'), 'customer/selectChargeCustomer');
                    }
                    ?>
                       <?php
                    if($actionName=='selectRefillCustomer' && $modulName=="customer"){
                       echo link_to(__('Refill Customers'), 'customer/selectRefillCustomer', array('class'=>'subSelect'));
                    }else{
                       echo link_to(__('Refill Customers'), 'customer/selectRefillCustomer');
                    }
                    ?>
                                           <?php
                    if($actionName=='completePaymenthistory' && $modulName=="customer"){
                       echo link_to(__('Payment History'), 'customer/completePaymenthistory', array('class'=>'subSelect'));
                    }else{
                       echo link_to(__('Payment History'), 'customer/completePaymenthistory');
                    }
                    ?>
                </div>
            </li>

          <li>
                <a href="#"
                onmouseover="mopen('m3')"
                onmouseout="mclosetime()" <?php echo $modulName=="agent_user" || $modulName=="agent_company" || $modulName=="agent_commission" || $modulName=="agent_commission_package" ?'class="current"':''?>><?php echo __('Agents') ?></a>
                <div id="m3" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
                    <?php 
                     if($actionName=="list" && $modulName=="agent_company"){
                       echo link_to(__('Company List'), 'agent_company/index', array('class'=>'subSelect'));
                     }else{
                       echo link_to(__('Company List'), 'agent_company/index');
                     }  
                     ?>
                    <?php 
                      if($actionName=="list" && $modulName=="agent_user"){
                       echo link_to(__('User List'), 'agent_user/index', array('class'=>'subSelect'));
                      }else{
                       echo link_to(__('User List'), 'agent_user/index');
                      } 
                    ?>

                    <?php 
                     if($actionName=="selectCompany" && $modulName=="agent_commission"){  
                       echo link_to(__('Agent Per Product'), 'agent_commission/selectCompany', array('class'=>'subSelect'));
                     }else{
                       echo link_to(__('Agent Per Product'), 'agent_commission/selectCompany'); 
                     }
                     ?>

                    <?php 
                      if($actionName=="list" && $modulName=="agent_commission_package"){
                        echo link_to(__('Agent Commission Package'), 'agent_commission_package/index', array('class'=>'subSelect'));
                      }else{
                        echo link_to(__('Agent Commission Package'), 'agent_commission_package/index');
                      }?>

                     <?php
                      if($actionName=="selectCompany" && $modulName=="agent_company"){
                        echo link_to(__('Refil Agent Company'), 'agent_company/selectCompany', array('class'=>'subSelect'));
                      }else{
                        echo link_to(__('Refil Agent Company'), 'agent_company/selectCompany');
                      }?>

  <?php
  if($actionName=="chargeCompany" && $modulName=="agent_company"){
                        echo link_to(__('Charge Agent Company'), 'agent_company/chargeCompany', array('class'=>'subSelect'));
                      }else{
                        echo link_to(__('Charge Agent Company'), 'agent_company/chargeCompany');
                      }?>

                     <?php
  if($actionName=="agentCompanyPayment" && $modulName=="agent_company"){
                        echo link_to(__('Payment History'), 'agent_company/agentCompanyPayment', array('class'=>'subSelect'));
                      }else{
                        echo link_to(__('Payment History'), 'agent_company/agentCompanyPayment');
                      }?>
                  
                </div>
            </li>



            <li>
                <a href="#"
                onmouseover="mopen('m7')"
                onmouseout="mclosetime()" <?php echo $modulName=='newupdate' ||  $modulName=='faqs' || $modulName=='userguide'? 'class = "current"':''?>><?php echo __('Updates') ?></a>
                <div id="m7"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">


                      <?php
//                    if($actionName=='newsList' && $modulName=="agent_company"){
//                      echo link_to(__('List All Updates'), 'agent_company/newsList', array('class'=>'subSelect'));
//                    }else{
//                      echo link_to(__('List All Updates'), 'agent_company/newsList');
//                    }
                    ?>



                    <?php 
                    if($actionName=='list' && $modulName=="newupdate"){  
                      echo link_to(__('News Updates'), 'newupdate/index', array('class'=>'subSelect'));
                    }else{
                      echo link_to(__('News Updates'), 'newupdate/index');
                    }
                    ?>
                    <?php 
                    if($actionName=='list' && $modulName=="faqs"){
                        echo link_to(__('FAQ'), 'faqs/index', array('class'=>'subSelect'));
                    }else{
                        echo link_to(__('FAQ'), 'faqs/index');
                    }
                    ?>
                    <?php 
                    if($actionName=='index' && $modulName=="userguide"){
                        echo link_to(__('User Guide'), 'userguide/index', array('class'=>'subSelect'));
                    }else{
                        echo link_to(__('User Guide'), 'userguide/index');
                    }?>

                </div>
            </li>
            <li style="display:none"><a href="#"
                onmouseover="mopen('m2')"
                onmouseout="mclosetime()"><?php echo __('Company') ?></a>
                <div id="m2"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
                    <?php echo link_to(__('companies list'), 'company/index') ?>
                    <?php echo link_to(__('employee lists'), 'employee/index') ?>
                    <?php echo link_to(__('sale activity'), 'sale_activity/index'); ?>
                    <?php echo link_to(__('support activity'), 'support_activity/index'); ?>
                    <?php echo link_to(__('usage'), 'cdr/index'); ?>
                    <?php echo link_to(__('invoices'), 'invoice/index'); ?>
                    <?php echo link_to(__('product orders'), 'product_order/index') ?>
                </div>
            </li>




           
            <li>
                <a href="#"
                onmouseover="mopen('m11')"
                onmouseout="mclosetime()" <?php echo $modulName=='invoice'? 'class = "current"':''?>><?php echo __('Reports') ?></a>
                <div id="m11"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
                    <?php
                     if($actionName=='selectIntervalAlert' && $modulName=="invoice"){
                        echo link_to(__('Low Credit Alert Report'), 'invoice/selectIntervalAlert', array('class'=>'subSelect'));
                     }else{
                        echo link_to(__('Low Credit Alert Report'), 'invoice/selectIntervalAlert');
                     }
                     ?>

                </div>
            </li>






            <li>
                <a href="#"
                onmouseover="mopen('m4')"
                onmouseout="mclosetime()" <?php echo $modulName=='user'? 'class = "current"':''?>><?php echo __('Admin Users') ?></a>
                <div id="m4"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
                    <?php 
                     if($actionName=='list' && $modulName=="user"){
                        echo link_to(__('User'), 'user/index', array('class'=>'subSelect'));
                     }else{
                        echo link_to(__('User'), 'user/index');
                     }
                     ?>

                </div>
            </li>
          
           <li>
                <a href="#"
                onmouseover="mopen('m9')"
                onmouseout="mclosetime()" <?php echo $modulName=='client_documents'? 'class = "current"':''?>><?php echo __('Download') ?></a>
                <div id="m9"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
                    <?php
                     if($actionName=='index' && $modulName=="client_documents"){
                        echo link_to(__('Downlaod User Guide'), 'client_documents/index', array('class'=>'subSelect'));
                     }else{
                        echo link_to(__('Downlaod User Guide'), 'client_documents/index');
                     }
                     ?>
                </div>
            </li>
          



<li><a href="#"
                onmouseover="mopen('m1')"
                onmouseout="mclosetime()"
                <?php echo $modulName=="device" || $modulName=="manufacturer" || $modulName=="telecom_operator" || $modulName=="postal_charges" ||$modulName=="product" || $modulName=="enable_country" || $modulName=="city" || $modulName=="sms_text" || $modulName=="simTypes" || $modulName=="nationality" || $modulName=="preferredLanguages" || $modulName=="handsets" || $modulName=="province" || $modulName=="usage_alert" || $modulName=="usage_alert_sender" || $modulName=="telecom_operator" ?'class="current"':''?>
                ><?php echo __('Settings') ?></a>
                <div id="m1"
                    onmouseover="mcancelclosetime()"
                    onmouseout="mclosetime()">
                      <?php
                        // As per Omair Instruction - He need these changes - kmmalik - 08/17/2011
                        ?>
                        <?php
                        // As per Omair Instruction - He need these changes - kmmalik - 08/17/2011
                         //echo link_to('<b>Zerocall Setting</b>', '') ?>
                        <a href="javascript:;" class="label"><b><?php echo __('%1% Setting',array('%1%'=> sfConfig::get('app_site_title'))) ?></b></a>
                        <?php 
//                        if($actionName=='list' && $modulName=="device"){
//                          echo link_to(__('Mobile Models'), 'device/index',array('class'=>'subSelect'));
//                        }else{
//                          echo link_to(__('Mobile Models'), 'device/index');
//                        }
                        ?>
                        <?php 
//                        if($actionName=='list' && $modulName=="manufacturer"){
//                          echo link_to(__('Mobile Brands'), 'manufacturer/index',array('class'=>'subSelect'));
//                        }else{
//                          echo link_to(__('Mobile Brands'), 'manufacturer/index');
//                        }
                        ?>
                       
                        <?php 
                        if($actionName=='list' && $modulName=="postal_charges"){
                          echo link_to(__('Postal charges'), 'postal_charges/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Postal charges'), 'postal_charges/index'); 
                        }
                        ?>
                        <a href="javascript:;" class="label"><b><?php echo __('General Setting') ?> </b></a>
                        <?php 
                        if($actionName=='list' && $modulName=="product"){
                          echo link_to(__('Products'), 'product/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Products'), 'product/index'); 
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="enable_country"){
                          echo link_to(__('Country List'), 'enable_country/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Country List'), 'enable_country/index');
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="province"){
                          echo link_to(__('Province List'), 'province/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Province List'), 'province/index');
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="city"){
                          echo link_to(__('Cities'), 'city/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Cities'), 'city/index'); 
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="preferredLanguages"){
                          echo link_to(__('Preferred Languages'), 'preferredLanguages/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Preferred Languages'), 'preferredLanguages/index');
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="simTypes"){
                          echo link_to(__('Sim Types'), 'simTypes/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Sim Types'), 'simTypes/index');
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="handsets"){
                          echo link_to(__('Handsets'), 'handsets/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Handsets'), 'handsets/index');
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="nationality"){
                          echo link_to(__('Nationality'), 'nationality/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Nationality'), 'nationality/index');
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="sms_text"){
                          echo link_to(__('SMS Text'), 'sms_text/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('SMS Text'), 'sms_text/index');
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="usage_alert"){
                          echo link_to(__('Low Credit Alert'), 'usage_alert/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Low Credit Alert'), 'usage_alert/index');
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="usage_alert_sender"){
                          echo link_to(__('Low Credit Alert Sender'), 'usage_alert_sender/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Low Credit Alert Sender'), 'usage_alert_sender/index');
                        }
                        ?>
                        <?php 
                        if($actionName=='list' && $modulName=="telecom_operator"){
                          echo link_to(__('Telecom Operator'), 'telecom_operator/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Telecom Operator'), 'telecom_operator/index');
                        }
                        ?>
                           <?php
                        if($actionName=='deActivateCustomer' && $modulName=="customer"){
                          echo link_to(__('DeActivat eCustomer'), 'customer/deActivateCustomer',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('DeActivat eCustomer'), 'customer/deActivateCustomer');
                        }
                        ?>
                           <?php
                        if($actionName=='index' && $modulName=="transactionDescription"){
                          echo link_to(__('Transaction Description'), 'transactionDescription/index',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Transaction Description'), 'transactionDescription/index');
                        }
                        if($actionName=='indexAll' && $modulName=="company"){
                          echo link_to(__('Edit B2B Credit Limit'), 'company/indexAll',array('class'=>'subSelect'));
                        }else{
                          echo link_to(__('Edit B2B Credit Limit'), 'company/indexAll');
                        }
                        ?>

                </div>
            </li>
   
			<li class="last">
                <?php echo link_to(__('Logout'), 'user/logout'); ?>
            </li>
          	
        </ul>
             </div>
      <?php endif; ?> 
    <br />
         
      <div class="clr"></div>
    <?php echo $sf_content ?>
    </div> <!--  end wrapper -->


    <script type="text/javascript">
  jQuery('#sddm li a').click(function() {
    $('li:last').addClass('current') ;
   });
 
jQuery(function(){

	jQuery('#sf_admin_form').validate({
	});
jQuery('#sf_admin_edit_form').validate({

     rules: {
        "company[name]": "required",
        "company[vat_no]": "required",
        "company[post_code]": "required digits",
        "company[address]": "required",
        "company[contact_name]": "required",
        "company[head_phone_number]": "required",
        "company[email]": "required email",
        "company[invoice_method_id]": "required"
  }
	});
});
</script>

    <script type="text/javascript">
    /* jQuery('#company_post_code').blur(function(){
        var poid=jQuery("#company_post_code").val();
       // poid = poid.replace(/\s+/g, '');
        var poidlenght=poid.length;
        //alert(poidlenght);
       // var poida= poid.charAt(0);
       // var poidb= poid.charAt(1);
       // var poidc= poid.charAt(2);
       // var poidd= poid.charAt(3);
        //var poide= poid.charAt(4);
        if(poidlenght>5){
            jQuery("#companyPost").html('"'+poid+'" is too long 5 characters max.');
           
            jQuery('#error').val("error");
           // var fulvalue=poida+poidb+poidc+" "+poidd+poide;
        }else if(poidlenght<4){
            jQuery("#companyPost").html('"'+poid+'" is too short 4 characters min');//
          jQuery('#error').val("error");
           //var fulvalue=poida+poidb+poidc;
        }else{jQuery("#companyPost").html('');jQuery('#error').val("");}
      // jQuery("#company_post_code").val(fulvalue);
       //  alert(fulvalue);

        });*/



</script>

   <?php if ($sf_user->getCulture() == 'en') {
 ?>
        <?php use_javascript('jquery.validate1.js', '', array('absolute' => true)) ?>
        <?php } else {
 ?>
        <?php use_javascript('jquery.validatede.js', '', array('absolute' => true)) ?>
<?php } ?>
    <script language="javascript" type="text/javascript">

jQuery(function(){

    // add multiple select / deselect functionality
    jQuery("#selectall").click(function () {
          jQuery('.case').attr('checked', this.checked);
    });

    // if all checkbox are selected, check the selectall checkbox
    // and viceversa
    jQuery(".case").click(function(){

        if(jQuery(".case").length == jQuery(".case:checked").length) {
            jQuery("#selectall").attr("checked", "checked");
        } else {
            jQuery("#selectall").removeAttr("checked");
        }

    });
});
jQuery(function(){



	jQuery( "#startdate" ).datepicker({ minDate: '-2m +0w',maxDate: '0m +0w', dateFormat: 'dd-mm-yy' });
	jQuery( "#enddate" ).datepicker({ minDate: '-2m +0w',maxDate: '0m +0w', dateFormat: 'dd-mm-yy'});
        jQuery( "#stdate" ).datepicker({maxDate: '0m +0w', dateFormat: 'dd-mm-yy' });
        jQuery( "#endate" ).datepicker({maxDate: '0m +0w', dateFormat: 'dd-mm-yy'});

    });


	jQuery('#company_vat_no').blur(function(){
		//remove all the class add the messagebox classes and start fading
		jQuery("#msgbox").removeClass().addClass('messagebox').text('<?php echo __('Checking...') ?>').fadeIn("slow");

                 var val=jQuery(this).val();

                if(val==''){
                    jQuery("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
			{
			  //add message and change the class of the box and start fading
			  jQuery(this).html('<?php echo __('Enter Vat Number') ?>').addClass('messageboxerror').fadeTo(900,1);
			});
                        jQuery('#error').val("error");
                }else{
		//check the username exists or not from ajax
		jQuery.post("<?php echo sfConfig::get('app_admin_url');?>company/vat",{ vat_no:val } ,function(data)
        {//alert(data);
		  if(data=='no') //if username not avaiable
		  {
		  	jQuery("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
			{
			  //add message and change the class of the box and start fading
			  jQuery(this).html('<?php echo __('This Vat No Already exists') ?>').addClass('messageboxerror').fadeTo(900,1);
			});jQuery('#error').val("error");
          }
		  else
		  {
		  	jQuery("#msgbox").fadeTo(200,0.1,function()  //start fading the messagebox
			{
			  //add message and change the class of the box and start fading
			  jQuery(this).html('<?php echo __('Vat No is available') ?>').addClass('messageboxok').fadeTo(900,1);
			});jQuery('#error').val("");
		  }

        });
                }
	});

        	jQuery('#employee_mobile_number').blur(function(){
		//remove all the class add the messagebox classes and start fading
		jQuery("#msgbox").removeClass().addClass('messagebox').text('<?php echo __('Checking...') ?>').fadeIn("slow");
		//check the username exists or not from ajax
                var val=jQuery(this).val();

                if(val==''){
                    jQuery("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
			{
			  //add message and change the class of the box and start fading
			  jQuery(this).html('<?php echo __('Enter Mobile Number') ?>').addClass('messageboxerror').fadeTo(900,1);
			});
                        jQuery('#error').val("error");
                }else{
                    if(val.length >7){

                    if(val.substr(0, 1)==0){
                jQuery("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
			{
			  //add message and change the class of the box and start fading
			  jQuery(this).html('<?php echo __('Please enter a valid mobile number not starting with 0') ?>').addClass('messageboxerror').fadeTo(900,1);
			});
                        jQuery('#error').val("error");
                }else{

		jQuery.post("<?php echo sfConfig::get('app_admin_url');?>employee/mobile",{ mobile_no: val} ,function(data)
        {
		  if(data=='no') //if username not avaiable
		  {
		  	jQuery("#msgbox").fadeTo(200,0.1,function() //start fading the messagebox
			{
			  //add message and change the class of the box and start fading
			  jQuery(this).html('<?php echo __('This Mobile No Already exists') ?>').addClass('messageboxerror').fadeTo(900,1);
			});jQuery('#error').val("error");
          }
		  else
		  {
		  	jQuery("#msgbox").fadeTo(200,0.1,function()  //start fading the messagebox
			{
			  //add message and change the class of the box and start fading
			  jQuery(this).html('<?php echo __('Mobile No is available') ?>').addClass('messageboxok').fadeTo(900,1);
			});jQuery('#error').val("");
		  }

        });
                }}}
	});

    jQuery("#sf_admin_form").submit(function() {
      if (jQuery("#error").val() == "error") {

        return false;
      }else{
          return true;
      }


    });
       jQuery("#sf_admin_edit_form").submit(function() {
      if (jQuery("#error").val() == "error") {

        return false;
      }else{
          return true;
      }


    });

    


</script>
<style type="text/css">
.messagebox{
	position:absolute;
	width:100px;
	margin-left:30px;
	border:1px solid #c93;
	background:#ffc;
	padding:3px;
}
.messageboxok{
	position:absolute;
	width:auto;
	margin-left:30px;
	border:1px solid #349534;
	background:#C9FFCA;
	padding:3px;
	font-weight:bold;
	color:#008000;

}
.messageboxerror{
	position:absolute;
	width:auto;
	margin-left:30px;
	border:1px solid #CC0000;
	background:#F7CBCA;
	padding:3px;
	font-weight:bold;
	color:#CC0000;
}

</style>
  </body>
</html>
