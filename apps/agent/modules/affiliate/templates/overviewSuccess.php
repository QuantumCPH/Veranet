<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<style type="text/css">
    table {
        margin-bottom: 10px;
    }

    table.summary td {
        font-size: 1.2em;
        font-weight: normal;
    }
</style>
<link href="<?php echo sfConfig::get('app_web_url'); ?>css/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo sfConfig::get('app_web_url'); ?>js/jquery.min.js"></script>
<script src="<?php echo sfConfig::get('app_web_url'); ?>js/jquery-ui.min.js"></script>
<script>
    jQuery(function() {
      
        jQuery( "#startdate" ).datepicker({ dateFormat: 'yy-mm-dd' });
        jQuery( "#enddate" ).datepicker({ dateFormat: 'yy-mm-dd'});
	
		
    });
</script>
<div class="report_container">

    <table cellpadding="0" cellspacing="0" class="tbldatefilter" align="center">
        <tr><td><h1><?php echo __('Date Filter') ?></h1></td></tr>
        <tr>
            <td>
                <form action="" id="searchform" method="POST" name="searchform">
                    <div class="dateBox-pt">
                        <div class="formRow-pt" style="float:left;">
                            <label class="datelable"style="text-align:left">From:</label>
                            <input type="text"   name="startdate" autocomplete="off" id="startdate" style="width: 110px;" value="<?php echo date('Y-m-d', strtotime(@$startdate)); ?>" />
                        </div>
                        <div class="formRow-pt" style="float:left;">
                            <label class="datelable"style="text-align:left">To:</label>
                            <input type="text"   name="enddate" autocomplete="off" id="enddate" style="width: 110px;" value="<?php echo @$enddate ? $enddate : date('Y-m-d'); ?>" />
                        </div>
                        <span><input type="submit" name="søg" value="Search" class="datefilterBtn" /></span>
                    </div>
                </form>
            </td>
        </tr>
    </table>
</div>
<div class="report_container">
    <div id="sf_admin_container"><h1><?php echo __('Earning Summary') ?><div style="float:right;clear:both;"><a href="<?php echo sfConfig::get('app_agent_url'); ?>affiliate/printOverview?startdate=<?php echo @$startdate ? $startdate : date('Y-m-d', strtotime('-15 days')); ?>&enddate=<?php echo @$enddate ? $enddate : date('Y-m-d'); ?>" target="_blank" style="color:white;">Print</a></div></h1></div>

    <div class="borderDiv">
        <table cellspacing="0" width="60%" class="summary">
            <?php
            if ($agent->getIsPrepaid()) {
            ?>
                <tr>
                    <td><strong><?php echo __('Your Balance is:') ?></strong></td>
                    <td align="right"><?php echo BaseUtil::format_number($agent->getBalance()); ?><?php echo sfConfig::get('app_currency_code');?></td>
                </tr>
            <?php } ?>
            <tr>
                <td><b><?php echo __('Customers') ?></b> <?php echo __('registered with you:') ?></td>
                <td align="right"><?php echo count($registrations) ?></td>
          </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td><?php echo __('Total') ?> <strong><?php echo __('revenue on registration') ?></strong></td>
                <td align="right">
                    <?php echo number_format($registration_revenue,2)?><?php echo sfConfig::get('app_currency_code');?>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Total commission earned on registration:') ?></td>
                <td align="right">
<?php echo number_format($registration_commission,2);?><?php echo sfConfig::get('app_currency_code');?>
                </td>
            </tr>

            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td><?php echo __('Total') ?> <strong><?php echo __('revenue on refill') ?></strong></td>
                <td align="right">
<?php echo number_format($refill_revenue,2)?><?php echo sfConfig::get('app_currency_code');?>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Total commission earned on refill:') ?></td>
                <td align="right">
<?php echo number_format($refill_com,2) ?><?php echo sfConfig::get('app_currency_code');?>
                </td>
            </tr>

            <tr>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td><?php echo __('Total') ?> <strong><?php echo __('revenue earned') ?>  </strong><?php echo __('on refill from shop:') ?></td>
                <td align="right">
<?php echo number_format($ef_sum,2); ?><?php echo sfConfig::get('app_currency_code');?>
                </td>
            </tr>
            <tr>
                <td><?php echo __('Total') ?> <strong><?php echo __('commission earned') ?> </strong><?php echo __('on refill from shop:') ?></td>
                <td align="right">
                    <?php echo number_format($ef_com,2); ?><?php echo sfConfig::get('app_currency_code');?>
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
            </tr>
       <!--       <tr>
                <td><?php echo __('Total') ?> <strong>revenue </strong><?php echo __('on SMS Registeration:') ?></td>
                    <td align="right">
<?php echo number_format($sms_registration_earnings,2); ?>
		</td>
	</tr>
          <tr>
                    <td><?php echo __('Total') ?> <strong> <?php echo __('Commission earned') ?> </strong><?php echo __('on SMS Registeration:') ?></td>
                    <td align="right">
<?php echo number_format($sms_commission_earnings,2); ?>
                    </td>
            </tr>
            -->


        </table>
    </div>
</div>
    <p>
    </p>
    <div id="sf_admin_container"><h1><?php echo __('News Box') ?></h1></div>

            <div class="borderDiv">

                <br/>
                <p>
<?php
                    $currentDate = date('Y-m-d');
?>
<?php
                    foreach ($updateNews as $updateNew) {
                        $sDate = $updateNew->getStartingDate();
                        $eDate = $updateNew->getExpireDate();

                        if ($currentDate >= $sDate) {$sDate1 = $updateNew->getStartingDate('d-m-Y');
?>


                            <b><?php echo $sDate1 ?></b><br/>
            <?php echo $updateNew->getHeading(); ?> :
            <?php
                            if (strlen($updateNew->getMessage()) > 100) {
                                echo substr($updateNew->getMessage(), 0, 100);
                                echo link_to('....read more', 'affiliate/newsListing');
                            } else {
                                echo $updateNew->getMessage();
                            }
            ?>
                            <br/><br/>

            <?php
                        }
                    } ?>
                    <b><?php echo link_to(__('View All News & Updates'), 'affiliate/newsListing'); ?> </b>
        </p>





    </div>



