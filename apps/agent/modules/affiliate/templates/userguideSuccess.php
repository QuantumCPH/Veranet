<style>
    #basic p {
    border-bottom: 0px solid #fff;
    }
</style>

<div id="sf_admin_container"><h1><?php echo __('User Guide') ?></h1></div>
        
 <div class="borderDiv"> 
    <?php //echo __('User Guide') ?>

     <?php foreach ($Userguide as $userguide): ?>
  <?php echo '<h1> '.__($userguide->getTitle()).'</h1>';
  if($userguide->getImage()!=''){?>
  <img src="<?php echo image_path('../uploads/userguide/'.$userguide->getImage()) ?>" alt="" width="160" /><br /><br />


  <?php }
  echo '<p style=nowrap><b><font size=2></font></b>&nbsp;'.__($userguide->getDescription());
  echo '</p>';?>
<?php endforeach; ?>
     <div class="clr"></div>
 </div>