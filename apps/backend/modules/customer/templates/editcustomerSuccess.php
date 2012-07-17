<div id="sf_admin_container"><form method="post" action="" name="edit" enctype="multipart/form-data" >
    <input type="hidden" name="customerID" value="<?php echo $editCust->getId();?>" />
<p><?php echo @$message;?></p>
<div id="sf_admin_content">
                <ul class="customerMenu" style="margin:10px 0;">
                    <li><a class="external_link"  href="../../allRegisteredCustomer">View All Customer</a></li>
                    <li><a class="external_link"  href="../../paymenthistory?id=<?php echo $editCust->getId();  ?>">Payment History</a></li>
                    <li><a class="external_link"  href="../../callhistory?id=<?php echo $editCust->getId();  ?>">Call History</a></li>
                    <li><a class="external_link"  href="../../customerDetail?id=<?php echo $editCust->getId();  ?>">Customer Detail</a></li>
                </ul></div>
   <h1>Edit Customer</h1>
<table width="100%" cellspacing="0" cellpadding="2" class="tblAlign" border='0'>

        
        <tr>
            <td style="padding: 5px;">First Name</td>
            <td style="padding: 5px;"><input type="text" name="firstName" value="<?php echo $editCust->getFirstName();?>" />
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Last Name</td>
            <td style="padding: 5px;"><input type="text" name="lastName" value="<?php echo $editCust->getLastName();?>" />
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Second Family Name</td>
            <td style="padding: 5px;"><input type="text" name="secondlastName" value="<?php echo $editCust->getSecondLastName();?>" />
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Address</td>
            <td style="padding: 5px;"><input type="text" name="address" value="<?php echo $editCust->getAddress();?>" />
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">City</td>
            <td style="padding: 5px;"><input type="text" name="city" value="<?php echo $editCust->getCity();?>" />
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Province</td>
            <td style="padding: 5px;">
                <select name="provinceid">
                    <option value="">--Select--</option>
                <?php
                  foreach($province_list as $province){
               ?>
                    <option value="<?php echo $province->getId();?>" <?php echo ($editCust->getProvinceId()==$province->getId())?'selected="selected"':'';?>><?php echo $province->getProvince();?></option>
               <?php       
                  }
                ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">PO-BOX Number</td>
            <td style="padding: 5px;"><input type="text" name="pob" value="<?php echo $editCust->getPoBoxNumber();?>" />
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Email</td>
            <td style="padding: 5px;"><input type="text" name="email" value="<?php echo $editCust->getEmail();?>" />
            </td>
        </tr>
         <tr>
            <td style="padding: 5px;">Nationality</td>
            <td style="padding: 5px;">
                <select name="nationalityid">
                    <option value="">--Select--</option>
                <?php
                  foreach($nationality_list as $nationality){
               ?>
                    <option value="<?php echo $nationality->getId();?>" <?php echo ($nationality->getId()==$editCust->getNationalityId())?'selected="selected"':'';?>><?php echo $nationality->getTitle();?></option>
               <?php       
                  }
                ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Preferred Language</td>
            <td style="padding: 5px;">
                <select name="pLanguageId">
                    <option value="">--Select--</option>
                <?php
                  foreach($planguages as $language){
               ?>
                    <option value="<?php echo $language->getId();?>" <?php echo ($language->getId()==$editCust->getPreferredLanguageId())?'selected="selected"':'';?>><?php echo $language->getLanguage();?></option>
               <?php       
                  }
                ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Date Of Birth</td>
            <td style="padding: 5px;">
                <?php
                $dt = "";
                $dd = "";
                $dm = "";
                $dy ="";
                $dt = $editCust->getDateOfBirth();
                if($dt){
                          $dd = date('d',strtotime($dt));
                          $dm = date('m',strtotime($dt));
                          $dy = date('Y',strtotime($dt));
                         } 
                ?>
                <select name="dd">
                    <option value="">Day</option>
                    <?php
                    for($d = 1;$d<=31; $d++){
                    ?>
                    <option value="<?php echo $d;?>"<?php echo (@$dd!=$d)?'':' selected="selected" ' ?> ><?php echo $d;?></option>
                    <?php    
                    }
                    ?>
                </select>&nbsp;
                <select name="dm">
                    <option value="">Month</option>
                    <?php
                    for($m = 1;$m<=12; $m++){
                    ?>
                    <option value="<?php echo $m;?>"<?php echo (@$dm!=$m)?'':' selected="selected" ' ?> ><?php echo $m;?></option>
                    <?php    
                    }
                    ?>
                </select>&nbsp;
                <select name="dy">
                    <option value="">Year</option>
                    <?php
                    for($y =1901;$y<=1998; $y++){
                    ?>
                    <option value="<?php echo $y;?>"<?php echo (@$dy!=$y)?'':' selected="selected" ' ?> ><?php echo $y;?></option>
                    <?php    
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Usage Email Alerts</td>
            <td style="padding: 5px;">
                <input type="checkbox" name="usage_email" <?php if($editCust->getUsageAlertEmail()) echo" checked=checked"?> />&nbsp;
                
            </td>
        </tr>
        <tr>
            <td style="padding: 5px;">Usage SMS Alerts</td>
            <td style="padding: 5px;">
                <input type="checkbox" name="usage_sms" <?php if($editCust->getUsageAlertSMS()) echo" checked=checked"?> />&nbsp;
                
            </td>
        </tr>
            <tr>
            <td>Comments:</td>
            <td><textarea name="comments"  id="customer_comments"><?php echo $editCust->getComments(); ?></textarea>
                </td>
        </tr>
        </table>
        <ul class="sf_admin_actions"><li><input class="sf_admin_action_create" type="submit" name="submit"  value="update" /></li></ul>
        




    





</form></div>