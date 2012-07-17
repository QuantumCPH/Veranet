<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<a href="#" onclick="showCompany()" title="company info" class="external_link"><?php echo __('Agent/Company info') ?></a>

<?php if($sf_user->isAuthenticated()): ?>
     <?php if($agent_company->getIsPrepaid()): ?>

        <?php echo link_to(__('Account Recharge'), 'affiliate/accountRefill',array('class'=>'external_link')) ?>

    

        <?php echo link_to(__('Recharge Receipts'), 'affiliate/agentOrder',array('class'=>'external_link')) ?>

    
    
    <?php endif; ?>
<?php endif; ?>
<!--<a href="#" onclick="showBank()" title="bank info"><?php echo __('Bank info') ?></a>
&nbsp; | &nbsp;
<a href="#" onclick="showCommission()" title="commission info"><?php echo __('Commission info') ?></a>
&nbsp; | &nbsp;
-->
 <?php if($agent_company->getIsPrepaid()): ?>
 <?php echo link_to(__('Payment History'), 'affiliate/paymentHistory',array('class'=>'external_link')) ?>
<?php endif; ?>
<br/>
<div id="company-info" style="display:block">
<div id="sf_admin_container"><h1><?php echo __('Agent/Company info') ?></h1></div>
        
  <div class="borderDiv"> 

      <label class="grid_2 required"><?php echo __('Company Name:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getName() ?>
      </div>

      <label class="grid_2 required"><?php echo __('NIE/NIF/Vat Number:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getCVRNumber() ?>
      </div>

      <div class="clear"></div>
      
      <label class="grid_2 required"><?php echo __('Kimarin Agent ID:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getKimarinAgentId(); ?>
      </div>
      
      <label class="grid_2 required"><?php echo __('Address:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getAddress() ?>
      </div>
      
      <div class="clear"></div>
      <label class="grid_2 required"><?php echo __('Post Code:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getPostCode() ?>
      </div>

      

      <label class="grid_2 required"><?php echo __('Province:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getProvinceName();?>
      </div>
      <div class="clear"></div>
      
      <label class="grid_2 required"><?php echo __('City:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getCity()?$agent_company->getCity()->getName():'N/A' ?>
      </div>

      
      <label class="grid_2 required"><?php echo __('Nationality:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getNationalityTitle();?>
      </div>
      
      <div class="clear"></div>
      
      <label class="grid_2 required"><?php echo __('Contact name:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getFirstName()." ".$agent_company->getMiddleName()." ".$agent_company->getLastName(); ?>
      </div>
      
      <label class="grid_2 required"><?php echo __('Contact email:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getEmail() ?>
      </div>

      <div class="clear"></div>

      <label class="grid_2 required"><?php echo __('Head Phone No:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getHeadPhoneNumber() ?>
      </div>
      
      <label class="grid_2 required"><?php echo __('Fax Number:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getFaxNumber() ?>
      </div>      
      
      <div class="clear"></div>
      
      <label class="grid_2 required"><?php echo __('Registered at:') ?></label>
      <div class="grid_2 content">
            <?php echo $agent_company->getCreatedAt('d-m-Y') ?>
      </div>
      <div class="clear"></div>

<!--      <label class="grid_2 required"><?php echo __('Company form:') ?></label>
      <div class="grid_2 content">
            <?php echo ''.$agent_company->getCompanyType() ?>
      </div>


      <label class="grid_2 required"><?php echo __('Account Manager:') ?></label>
      <div class="grid_2 content">
            <?php echo ''.$agent_company->getAccountManager() ?>
      </div>

      <div class="clear"></div>-->



      <div class="clear"></div>

      <div class="clr"></div>
</div>

</div>
<div id="bank-info" style="display:none">
    <h1>Bank info</h1>

    <?php foreach($agent_company->getAgentBanks() as $bank): ?>
        <label class="grid_2 required">Reg. Nr.</label>
        <div class="grid_2 content"><?php echo $bank->getRegNr(); ?></div>
        <label class="grid_2 column_head"><?php echo __('Account Number') ?></label>
        <div class="grid_2 content"><?php echo $bank->getAccountNumber(); ?></div>
        <div class="clear"></div>
    <?php endforeach; ?>
</div>


<div id="commission-info" style="display:none">
    <h1>Commission info</h1>
    <table class="list-view">
        <thead>
            <th><?php echo __('Revenue interval'); ?></th>
            <th><?php echo __('Commission rate %'); ?></th>
            <th><?php echo __('Created at'); ?></th>
        </thead>
        <tbody>
        <?php foreach($agent_company->getAgentCommissions() as $commission): ?>
            <tr>
                <td><?php echo $commission->getRevenueInterval() ?></td>
                <td><?php echo $commission->getCommissionRate() ?></td>
                <td><?php echo $commission->getCreatedAt() ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script type="text/javascript">
		function showCompany() {
		  var companyShow = document.getElementById("company-info");
		  var commissionInfo = document.getElementById("commission-info");
                  var bankInfo = document.getElementById("bank-info");

		  companyShow.style.display = "block";
		  commissionInfo.style.display = "none";
                  bankInfo.style.display = "none";

		};

		function showCommission() {
		  var companyShow = document.getElementById("company-info");
		  var commissionInfo = document.getElementById("commission-info");
                  var bankInfo = document.getElementById("bank-info");

		  companyShow.style.display = "none";
		  commissionInfo.style.display = "block";
                  bankInfo.style.display = "none";
		};

                function showBank() {
		  var companyShow = document.getElementById("company-info");
		  var commissionInfo = document.getElementById("commission-info");
                  var bankInfo = document.getElementById("bank-info");

		  companyShow.style.display = "none";
		  commissionInfo.style.display = "none";
                  bankInfo.style.display = "block";
		};
</script>