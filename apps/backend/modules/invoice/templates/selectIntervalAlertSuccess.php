<div id="sf_admin_container"><br>
<?php if ($sf_user->hasFlash('notice')): ?>
	<div class='notice'>
	  <?php echo $sf_user->getFlash('notice') ?>
	</div>
<?php endif; ?>
</div>    
<div id="sf_admin_container">
   <div class="sf_admin_filters">  
       <fieldset> <h1 style="margin-top: 0;"><?php echo __('Low Credit Alert Report') ?></h1>
<?php echo form_tag('invoice/usageAlertReport') ?>
     <div class="form-row">
         <label> Start date/time</label> 
         <div class="content">
           <input type="text"   name="startdate" autocomplete="off" id="startdate" style="width: 90px;"  />
         </div>
     </div>    
     <div class="form-row">
       <label>  End date/time</label> 
       <div class="content">
           <input type="text"   name="enddate" autocomplete="off" id="enddate" style="width: 90px;"  />
       </div>    
     </div>   
     <div class="form-row">
            <div class="content"> 
              <input type="submit" value="Generate Report" class="user_external_link" />  
            </div>
     </div>   
       </form>
</fieldset>  
    </div>
</div>    



