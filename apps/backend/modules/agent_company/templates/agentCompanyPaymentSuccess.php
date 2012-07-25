<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<style>
    .odd{background-color: #EEEEFF}
    .even{background-color: #FFFFFF}
    .headings{color: #000000;}
</style>

<div id="sf_admin_container"><h1><?php echo __('Agent Company Refill History') ?>  (<?php if($agentidd>0){ echo  $agent_company->getName(); }else{ echo __("All Agent Companies"); }   ?>) </h1></div><br/>
<div id="sf_admin_container">

    <form method="get" action="agentCompanyPayment">
        <fieldset>
            <div class="form-row">
                <label for="agent_commission_agent_company_id"><?php echo __('Agent Company');?></label>

                <div class="content">
                    <select id="agent_commission_agent_company_id" name="agent_company_id">
                        <option value="0" selected="selected">All Agent Companies</option>
                        <?php foreach($Lcompanies as $Lcompany){?>

                        <option value="<?php echo $Lcompany->getId();     ?>" <?php if($Lcompany->getId()==$agentidd){ ?> selected="selected" <?php } ?> ><?php echo $Lcompany->getName()."(".$Lcompany->getCVRNumber().")";   ?></option>

                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <label for="agent_commission_agent_company_id"><?php echo __('Refill By');?>:</label>
                <div class="content">
                    <select name="refilltype">
                        <option value="0"<?php if($refilltype=='0'){ ?> selected="selected" <?php } ?>>All</option>
                        <?php foreach($transactionDesc as $desc){?>

                        <option value="<?php echo $desc->getId();?>" <?php if($desc->getId()==$refilltype){ ?> selected="selected" <?php } ?> ><?php echo $desc->getTitle();   ?></option>

                        <?php } ?>
                    </select>
                </div>
            </div>
            <ul class="sf_admin_actions">
                <li>
                    <input type="submit" name="Agent Company" class="sf_admin_action_filter" value="filter" />
                </li>
            </ul>
      </fieldset>

    </form>
    <br/>
</div>
<div class="borderDiv">     

    <table cellspacing="0" width="75%" class="tblAlign">

        <tr class="headings">
            <th><?php echo __('Sr #') ?></th>
            <th><?php echo __('Date') ?> </th>
            <?php if($agentidd==0){?>
            <th><?php echo __('Agent Company') ?></th>
            <th><?php echo __('Agent Number') ?></th>
            <?}?>
            <th><?php echo __('Description') ?> </th>
            <th align="right" style="text-align: right;padding-right: 15px;"><?php echo __('Amount') ?></th>
            <th><?php echo __('Show Receipt') ?></th>
        </tr>
        <?php
        $i = 0;

        foreach($agents as $agent) {

        $i++;
        ?>
        <tr <?php echo 'class="'.($i%2 == 0?'odd':'even').'"' ?>>
            <td><?PHP echo $i;?></td>
            <td><?php  echo $agent->getCreatedAt('d-m-Y');  ?></td>
             <?php if($agentidd==0){?>
            <td>
                 <?php
                    $agent_company = AgentCompanyPeer::retrieveByPK($agent->getAgentCompanyId());
                    echo $agent_company->getName(); ?>
            </td>
            <td><?php  echo $agent_company->getCvrNumber();   ?></td>
            <?}?>
            <td><?php if($agent->getOrderDescription()){
                $c = new Criteria();
                $c->add(TransactionDescriptionPeer::ID,$agent->getOrderDescription());
                $transaction_desc = TransactionDescriptionPeer::doSelectOne($c);
                echo $transaction_desc->getTitle();
            }?></td>
            <td align="right" style="padding-right: 15px;"><?php  echo number_format($agent->getAmount(),2);?><?php echo sfConfig::get('app_currency_code');?></td>
            <td><a href="<?php echo url_for(sfConfig::get('app_admin_url').'agent_company/printAgentReceipt?aoid='.$agent->getId(), true) ?>"><img alt="view Detail" title="view Detail" src="<?php echo sfConfig::get('app_web_url');?>sf/sf_admin/images/default_icon.png" /></a>
         </tr>
    <?php  } ?>
    </table>
    <br />
</div>
 



