<?php use_helper('I18N') ?>
<?php use_helper('Number') ?>
<script type="text/javascript">
    jQuery(function() {
        
       jQuery("#validation_result").hide();
        jQuery("#sf_admin_form").submit(function(){
          var valu = jQuery("#charge").val();
          var t =  /^(?:\d+|\d{1,3}(?:,\d{3})+)(?:\.\d+)?$/.test(valu);   
          
         // alert(t);
          if(t==false){  
              jQuery("#validation_result").show();
              jQuery("#validation_result").html('Please enter valid amount');
              
              return false;
          }else if(t==true){
              jQuery("#validation_result").html('');
              jQuery("#validation_result").hide();
              return true;
          } 
      });
    });
</script>
<?php /*if($sf_user->hasFlash('message')){ ?>


<div class="save-ok">
<h2><?PHP echo __($sf_user->getFlash('message'));?> </h2>
</div>
  
<?php    }*/   ?>

<div id="sf_admin_container"><h1><?php echo __('Charge') ?></h1></div>

<form id="sf_admin_form" name="sf_admin_edit_form" method="post" enctype="multipart/form-data" action="Charge">
    <div id="sf_admin_content">
    <table style="padding: 0px;"  id="sf_admin_container" class="tblAlign" cellspacing="0" cellpadding="2" >
    <tr>
        <td style="padding: 5px;"><?php echo __('Company:') ?></td>
        <td style="padding: 5px;">
            <select name="company_id" id="employee_company_id"    class="required"  style="width:190px;">
           
            <?php foreach($companys as $company){  ?>
            <option value="<?php echo $company->getId();   ?>"><?php echo $company->getName()   ?></option>
            <?php   }  ?>
            </select>
        </td>
    </tr>
    <tr>
        <td style="padding: 5px;"><?php echo __('Transaction Desc.:') ?></td>
        <td style="padding: 5px;">
            <select name="descid" id="employee_company_id"    class="required"  style="width:190px;">
           
            <?php foreach($descriptions as $description){  ?>
            <option value="<?php echo $description->getId();   ?>"><?php echo $description->getTitle();   ?></option>
            <?php   }  ?>
            </select>
        </td>
    </tr>
        <tr>
        <td style="padding: 5px;"><?php echo __('Charge:') ?></td>
        <td style="padding: 5px;">
            <input type="text" id="charge" name="charge" class="" style="width:180px;" /> <?php echo sfConfig::get('app_currency_code');?>
            <label id="validation_result"></label>
        </td>
    </tr>
    </table>
        <div id="sf_admin_container">
          <ul class="sf_admin_actions">
           <li><input type="submit" name="save" value="<?php echo __('save') ?>" class="sf_admin_action_save" /></li>
           </ul>
        </div>
    </div>
    </form>
