<?php use_helper('I18N') ?>

<div id="sf_admin_container">

 <?PHP
    $str=strlen($company->getId());
    $str1=strlen(sfConfig::get("app_telinta_emp"));
    $substr=$str+$str1;
?>

<!--<a href=?iaccount=<?php //echo $account->getIAccount()."&iaccountTitle=".$account->getAccountTitle(); ?>>-->
<div id="sf_admin_content">

        <h1><?php echo __('Call History'); if(isset($iAccountTitle)&&$iAccountTitle!=''){echo "($iAccountTitle)"; }?></h1>
        <div class="sf_admin_filters">
            <form action="" id="searchform" method="POST" name="searchform">
                <fieldset>
                    <div class="form-row">
                        <label><?php echo __('Select Employee to Filter');?>:</label>
                        <div class="content">
                            <select name="iaccount" id="account">
                                <option value =''></option>
                             <?php 
                             if($telintaAccountObj){
                             foreach($telintaAccountObj as $account){
                                $companyid= $account->getParentId();
                                $cn = new Criteria();
                                $cn->add(EmployeePeer::ID, $companyid);
                                $employees = EmployeePeer::doSelectOne($cn);
                             ?>
                                <option value="<?PHP  echo $account->getId();?>" <?PHP echo ($account->getId()==$iaccount)?'selected="selected"':''?>><?php echo $employees->getFirstName()." -- ". $account->getAccountTitle();?></option>
                            <?php }
                             }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <label><?php echo __('From');?>:</label>
                        <div class="content">
                           
                            <?php echo input_date_tag('startdate', $fromdate, 'rich=true') ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <label><?php echo __('To');?>:</label>
                        <div class="content">
                          
                            <?php echo input_date_tag('enddate', $todate, 'rich=true') ?>
                        </div>
                    </div>

                </fieldset>

                <ul class="sf_admin_actions">
                    <li><input type="submit" class="sf_admin_action_filter" value="filter" name="filter"></li>
                    <li><input type="button" class="sf_admin_action_filter" value="reset" name="reset" onclick="document.location.href='<?PHP echo sfConfig::get('app_b2b_url')."company/callHisotry";?>'"></li>
                   
                </ul>
            </form>
        </div>
    </div>


    <table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>


        <tr class="headings">
            <th width="10%"   align="left"><?php echo __('Date & Time') ?></th>

            <th  width="10%"  align="left"><?php echo __('Phone Number') ?></th>
            <th width="10%"   align="left"><?php echo __('Duration') ?></th>
            <th  width="25%"  align="left"><?php echo __('Country') ?></th>
            <th  width="10%"  align="left"><?php echo __('Description') ?></th>
            <th width="10%"   align="left"><?php echo __('Cost') ?></th>
            <th  width="10%"   align="left"><?php echo __('Account ID') ?></th>
      </tr>
        <?php
        $callRecords = 0;

        $amount_total = 0;

       foreach ($callHistory->xdr_list as $xdr) {
        ?>


            <tr>
                <td><?php echo $xdr->connect_time; ?></td>
                <td><?php echo $xdr->CLD; ?></td>
                <td><?php   
                 $callval=$xdr->charged_quantity;
if($callval>3600){

 $hval=number_format($callval/3600);

  $rval=$callval%3600;

$minute=date('i',$rval);
  $second=date('s',$rval);

  $minute=$minute+$hval*60;

  echo $minute.":".$second;
}else{


echo  date('i:s',$callval);

}
                ?></td>
                <td><?php echo $xdr->country; ?></td>
                   <td><?php echo $xdr->description;  ?></td>
                <td><?php echo number_format($xdr->charged_amount, 2);
            $amount_total+= number_format($xdr->charged_amount, 2); ?><?php echo sfConfig::get('app_currency_code');?></td>
            
            <td><?php echo $xdr->account_id; ?></td>
        </tr>

        <?php
                $callRecords = 1;
            }
        ?>        <?php if ($callRecords == 0) {
 ?>
                <tr>
                    <td colspan="7"><p><?php echo __('There are currently no call records to show.') ?></p></td>
                </tr>
<?php } else { ?>
                <tr>
                    <td colspan="5" align="right"><strong><?php echo __('Subtotal') ?></strong></td>

                    <td><?php echo number_format($amount_total, 2, ',', '') ?><?php echo sfConfig::get('app_currency_code');?></td>
                    <td>&nbsp;</td>
                </tr>
<?php } ?>

<!--            <tr><td colspan="6" align="left"><?php echo __('Call type detail') ?> <br/> <?php echo __('Int. = International calls') ?><br/>
                <?php //echo __('Cb M = Callback mottaga')  ?><br />
                <?php //echo __('Cb S = Callback samtal')  ?><br />
<?php //echo __('R = resenummer samtal')    ?><br/>
            </td></tr>-->
    </table>
</div>