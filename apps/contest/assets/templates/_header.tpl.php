<?php $this->RenderAdminBar(); ?>
<div class="header">
   <img src="<?= __APP_IMAGE_ASSETS__; ?>/sweepstakes-header.jpg"> 
</div>
<div class="nav clearfix">
   <ul id="button">
	<li><a tpl='index' href='#' class='MFBLink <?php if ($this->TplMatch('index')) { echo 'active'; } ?>'>Contest Details</a></li>
	<li><a tpl='entryForm' href='#' class='MFBLink <?php if ($this->TplMatch('entryForm')) { echo 'active'; } ?>'>Enter Sweepstakes</a></li>
	<li><a tpl='displayEntries' href='#' class='MFBLink <?php if ($this->TplMatch('displayEntries')) { echo 'active'; } ?>'>View Entries</a></li>
	<li><a tpl='privacyPolicy' href='#' class='MFBLink <?php if ($this->TplMatch('privacyPolicy')) { echo 'active'; } ?>'>Official Rules</a></li>
    
</ul>
<!-- <?php echo $this->TplName();  ?> -->
</div>