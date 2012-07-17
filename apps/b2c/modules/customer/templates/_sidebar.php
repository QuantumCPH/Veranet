<?php
/*
  <div class="right-col">
    <div class="box box-b">
      <h3>Zapna Call<span>All</span></h3>
      <small>Ring billigt over hele verden</small>
      <p>Luda esse singularis simmilas
        vector ludo prosint nunc pro
        exreas...</p>
      <a href="#" class="more"><span>L?s og bestil &raquo;</span></a> </div>

  </div>
*/?>
  
<!-- new sidebar -->


<div id="sidebar" role="complementary">

	<div class="right-col">
		<div class="box box-a">
                    <h4 class="web_sms"><?php echo __('Web SMS') ?></h4><p>
<?php echo __('Send SMS worldwide at affordable prices.') ?></p>
                    <a title="<?php echo __('Web SMS') ?>" class="sidebar_button" href="<?php echo url_for('customer/websms', true) ?>"><?php echo __('Send SMS') ?></a>
		</div>

	</div>

	<div class="right-col">
		<div class="box box-b">
                    <h4 class="tellAfrnd"><?php echo __('Invite a friend') ?></h4><p>
<?php echo __('Save up to 80 % on international calls from your Spanish mobile telephone.'); ?></p>
                    <a title="<?php echo __('Tell a friend') ?>" class="sidebar_button" rel="bookmark" href="<?php echo url_for('customer/tellAFriend', true) ?>"><?php echo __('Send Invitation') ?></a>
		</div>
	<br />
	</div>
  <br />  
</div>