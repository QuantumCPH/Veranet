<div id="sf_admin_container">
	<div id="sf_admin_content">
	<!-- employee/list?filters[company_id]=1 -->
	<a href="<?php echo url_for('employee/usage').'?employee_id='.$employee->getId(); ?>" class="external_link" target="_self">Usage</a>
      
        </div></div>
<div id="sf_admin_container">
	<div id="sf_admin_content">
		<div id="company-info">
		    <h1>Employee Details</h1>
			<fieldset>
				<div class="form-row">
				  <label class="required">Employee Name:</label>
				  <div class="content">
				  	<?php echo $employee->getFirstName()." ".$employee->getLastName(); ?> &nbsp; <?php echo link_to('edit info', 'employee/edit?id='.$employee->getId()) ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Company:</label>
				  <div class="content">
				  	<?php echo ($employee->getCompany()?$employee->getCompany():'N/A') ?>
				  </div>
				</div>
                            <div class="form-row">
				  <label class="required">Employee Balance:</label>
				  <div class="content">
				  	<?php  echo number_format($balance,2);
                        echo sfConfig::get('app_currency_code'); /*$mobileID= $employee->getCountryMobileNumber();
        $telintaGetBalance=0;
        $telintaGetBalance = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name=a'.$mobileID.'&type=account');
        $telintaGetBalance = str_replace('success=OK&Balance=', '', $telintaGetBalance);
        $telintaGetBalance = str_replace('-', '', $telintaGetBalance);
        //$telintaGetBalance;
        $telintaGetBalance1=0;
        $telintaGetBalance1 = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name=cb'.$mobileID.'&type=account');
        $telintaGetBalance1 = str_replace('success=OK&Balance=', '', $telintaGetBalance1);
        $telintaGetBalance1 = str_replace('-', '', $telintaGetBalance1);
         //$telintaGetBalance;
        $telintaGetBalancerese=0;
        $regtype=$employee->getRegistrationType();
        if(isset($regtype) && $regtype==1){
        $voip = new Criteria();

        $voip->add(SeVoipNumberPeer::CUSTOMER_ID, $employee->getCountryMobileNumber());
        $voip->addAnd(SeVoipNumberPeer::IS_ASSIGNED, 1);
        $voipv = SeVoipNumberPeer::doSelectOne($voip);

        if(isset ($voipv)){

       $resenummer=$voipv->getNumber();
       $resenummer = substr($resenummer, 2);
       $telintaGetBalancerese = file_get_contents('https://mybilling.telinta.com/htdocs/zapna/zapna.pl?action=getbalance&name='.$resenummer.'&type=account');
       $telintaGetBalancerese = str_replace('success=OK&Balance=', '', $telintaGetBalancerese);
       $telintaGetBalancerese = str_replace('-', '', $telintaGetBalancerese);

        }
        }

      echo  $balnc=(float)$telintaGetBalance+(float)$telintaGetBalance1+($telintaGetBalancerese>0)?(float)$telintaGetBalancerese:0;
          echo sfConfig::get('app_currency_code');
                                */                ?>
				  </div>
				</div>
				<div class="form-row">
				  <label class="required">Email:</label>
				  <div class="content">
				  	<?php echo ($employee->getEmail()?$employee->getEmail():'N/A') ?>
				  </div>
				</div>

				<div class="form-row">
				  <label class="required">Mobile Number</label>
				  <div class="content">
				  	<?php echo $employee->getMobileNumber() ?>
				  </div>
				</div>
                                <div class="form-row">
				  <label class="required">SIM Type</label>
				  <div class="content">
				  	<?php echo $employee->getSimType(); ?>
				  </div>
				</div>
				<div class="form-row">
				  <label class="required">Product:</label>
				  <div class="content">
				  	<?php $pidd=$employee->getProductId();

                                         $pid = new Criteria();
        $pid->add(ProductPeer::ID, $pidd);
        $product = ProductPeer::doSelectOne($pid);

                         echo $product->getName();
                                        ?>
				  </div>
				</div>



                            <?php


                           $empid=$employee->getRegistrationType();
                          if(isset($empid) && $empid==1){ ?>

<!--                            <div class="form-row">
				  <label class="required">Resenumber:</label>
				  <div class="content">
				  	<?php    $voip = new Criteria();
        $voip->addAnd(SeVoipNumberPeer::CUSTOMER_ID, $employee->getCountryMobileNumber());
        $voipv = SeVoipNumberPeer::doSelectOne($voip);

                        // echo $voipv->getNumber(); ?>
				  </div>
				</div>-->


                            <?php  } ?>




<!--                            <div class="form-row">
				  <label class="required">Product Price:</label>
				  <div class="content">
				  	<?php //echo $employee->getProductPrice(); ?>
				  </div>
				</div>-->


                            <div class="form-row">
				  <label class="required">Created at:</label>
				  <div class="content">
				  	<?php echo $employee->getCreatedAt('d-m-Y') ?>
				  </div>
				</div>
<div class="form-row">
				  <label class="required">Comments:</label>
				  <div class="content">
				  	<?php echo $employee->getComments(); ?>
				  </div>
				</div>
                	<!--<div class="form-row">
				  <label class="required">App Code:</label>
				  <div class="content">
				  	<?php //echo $employee->getAppCode() ?>
				  </div>
				</div>-->
			</fieldset>
		</div>
	</div>
</div>