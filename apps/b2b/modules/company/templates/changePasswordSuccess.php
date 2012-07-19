<div id="sf_admin_container"><h1><?php echo __('Change Password') ?></h1></div>
<script>
  jQuery(document).ready(function(){
      jQuery("#frmChangePassword").validate();
 });
</script>
<div class="borderDiv">
    <br/>
    <div>
        <form action="<?php echo url_for(sfConfig::get('app_b2b_url').'company/changePassword') ?>" name="frmChangePassword" method="post" id="frmChangePassword">
            <input type="hidden" value="<?php echo $vatNo;?>" name="vatNum" />
            <table cellspacing="0" cellpadding="2" border="0" class="tblChangePassword" width="75%">
                <tr>
                  <th width="17%"><label class="required">Old Password:</label></th><td width="83%"><input type="text" value="" name="oldPassword" class="required" /></td>
              </tr>
                <tr>
                    <th><label class="required">New Password:</label></th><td><input type="text" value="" name="newPassword" class="required" /></td>
                </tr>
                <tr><td class="btnChange"><input type="submit" name="submit" value="Change" /></td><td></td></tr>
            </table>
        </form>        
        
    </div>

    <div class="clr"></div>
</div>					