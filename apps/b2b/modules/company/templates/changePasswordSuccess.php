<script>
  jQuery(document).ready(function(){
      jQuery("#frmChangePassword").validate();
 });
</script>
<div id="sf_admin_container">
    <h1><?php echo __('Change Password') ?></h1>
    <div class="sf_admin_filters">
        <form action="<?php echo url_for(sfConfig::get('app_b2b_url').'company/changePassword') ?>" name="frmChangePassword" method="post" id="frmChangePassword">
            <input type="hidden" value="<?php echo $vatNo;?>" name="vatNum" />
            <fieldset>
                <div class="form-row">
                    <label class="required">Old Password:</label>
                    <div class="content">
                        <input type="text" value="" name="oldPassword" class="required" />
                    </div>
                </div>
                <div class="form-row">
                    <label class="required">New Password:</label>
                    <div class="content">
                        <input type="text" value="" name="newPassword" class="required" />
                    </div>
                </div>
            </fieldset>
            <ul class="sf_admin_actions">
               <li><input type="submit" name="submit" value="Change" /></li>
            </ul>
        </form>
    </div>
</div>
					