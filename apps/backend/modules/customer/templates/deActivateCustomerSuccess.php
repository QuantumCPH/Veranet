<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

?><div id="sf_admin_container">
<div class="sf_admin_filters">
    <fieldset>  
    <h1 style="margin-top: 0;">De-Activate Customer</h1><br />
<form action="" method="get">
    <div class="form-row">
             <label for=""><strong>Customer Id:</strong></label>
    <div class="content">
             <input type="text" name="customer_id" />
    </div>
    </div>
    <div class="form-row">
            <div class="content">
              <input type="submit" value="De-Activate" class="user_external_link" />
            </div>
    </div>
</form>

<br/>
<?php echo $response_text ?>
<br />
</div>