<div id="sf_admin_container">
   <div class="sf_admin_filters">  
       <fieldset>     
    <h1 style="margin-top: 0;"><?php echo __('Select Company') ?></h1><br />
<form method="post" action="agentProduct">
<div class="form-row">
            <label for="agent_commission_agent_company_id"><strong> <?php echo $form['agent_company_id']->renderLabel() ?></strong></label>
            <div class="content">
             <?php echo $form['agent_company_id'] ?></div>
</div>
        <div class="form-row">
            <div class="content">
                <input type="submit" name="Assign Product" value="Assign Product" class="user_external_link" />
            </div>
        </div>
</form> <br />
    <br />
       </fieldset>
  </div>
   
</div>    