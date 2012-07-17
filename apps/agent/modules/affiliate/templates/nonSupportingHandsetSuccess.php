<div id="sf_admin_container"><h1><?php echo __('Non Supporting Handset') ?></h1></div>
        
 <div class="borderDiv"> 
     <table width="98%" cellspacing="0" cellpadding="3" align="center">
          <tbody>
            <tr> 
              <th><?php echo __('Brand name')?></th>
              <th><?php echo __('Model')?></th>
              <th><?php echo __('Auto reboot')?></th>
              <th><?php echo __('Dialer Mode')?></th>
              <th><?php echo __('Tested by')?></th>
              <th><?php echo __('Comments')?></th>
            </tr>
    <?php foreach ($handsets as $handset){?>        
            <tr> 
              <td>&nbsp;<?php echo $handset->getBrandName();?></td>
              <td>&nbsp;<?php echo $handset->getModelName();?></td>
              <td>&nbsp;<?php echo $handset->getAutoReboot();?></td>
              <td>&nbsp;<?php echo $handset->getDialerMode();?></td>
              <td>&nbsp;<?php echo $handset->getTestedBy();?></td>
              <td>&nbsp;<?php echo $handset->getComments();?></td>
            </tr>
    <?php } ?>        
           
          </tbody>
        </table>
 </div>    
<script language="javascript" type="text/javascript">
	jq = jQuery.noConflict();
	jq('table tr:not(:first-child):even').css('background-color', '#f2f8f8');
</script>