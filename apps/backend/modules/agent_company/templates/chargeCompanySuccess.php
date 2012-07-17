<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("#agent_company_charge").validate();
      });
</script>

<div id="sf_admin_container">
    <?php if ($sf_user->hasFlash('message')): ?>
        <div class="alert_bar">
                <?php echo $sf_user->getFlash('message') ?>
        </div>
        <?php endif;?>
</div>    
<div id="sf_admin_container">
   <div class="sf_admin_filters">  
       <fieldset>     
    <h1 style="margin-top: 0;"><?php echo __('Charge Agent Company') ?></h1><br />
    <form method="post" action="chargeAgentCompany" id="agent_company_charge">
        <div class="form-row">
             <label for="agent_commission_agent_company_id"><strong>Agent Company</strong></label>
             <div class="content">
                 <select id="agent_commission_agent_company_id" name="agent_company_id" class="required">
                    <option value="" selected="selected">Select Agent Company</option>
                    <?php    foreach($Lcompanies as $Lcompany){?>
                    <option value="<?php echo $Lcompany->getId();   ?>"><?php echo $Lcompany->getName()."(".$Lcompany->getCVRNumber().")";   ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-row">
             <label for="transaction_description">Transaction Description</label>
             <div class="content">
                 <select id="transaction_description" name="transaction_description" class="required">                    
                    <?php    foreach($transactionDescriptions as $transactionDescription){?>
                    <option value="<?php echo $transactionDescription->getId();   ?>"><?php echo $transactionDescription->getTitle();   ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <label for="agent_commission_agent_company_id"><strong>Charge Amount</strong></label>
            <div class="content">
                <input type="text" name="charge_amount"  class="required number" />  <?php sfConfig::get('app_currency_code');?>
            </div>
        </div>
        <div class="form-row">
            <div class="content">
                <input type="submit" name="Refill Agent Company" value="Chareg Agent Company" class="user_external_link" />
            </div>
        </div>
    </form>
       </fieldset>
   </div>  
</div>
</div>


   