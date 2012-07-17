<div id="sf_admin_container"><h1><?php echo sfConfig::get('app_site_title');echo __(' &#8211; FAQ') ?></h1></div>
        
 <div class="borderDiv">


<?php foreach ($Faqs as $faqs): ?>
  <b><font size=2><?php echo __('Question: ')?></font></b><?php echo __($faqs->getQuestion());?><br /><br />
  <p style=nowrap><b><font size=2><?php echo __('Answer:')?></font></b>&nbsp;<?php echo __($faqs->getAnswer());?></p><br />
<?php endforeach; ?>
 </div>