<?php $this->RenderAdminBar(); ?>
<div class="header">
   <img src="<?= __APP_IMAGE_ASSETS__; ?>/sweepstakes-header.jpg"> 
</div>
<div class="nav clearfix">
   <ul id="button">
	<li><MFBLink tpl='index' href='#' <?php if ($this->TplMatch('index')) { echo 'class="active"'; } ?>>Contest Details</MFBLink></li>
	<li><MFBLink tpl='entryForm' href='#' <?php if ($this->TplMatch('entryForm')) { echo 'class="active"'; } ?>>Enter Sweepstakes</MFBLink></li>
	<li><MFBLink tpl='displayEntries' href='#' <?php if ($this->TplMatch('displayEntries')) { echo 'class="active"'; } ?>>View Entries</MFBLink></li>
	<li><MFBLink tpl='privacyPolicy' href='#' <?php if ($this->TplMatch('privacyPolicy')) { echo 'class="active"'; } ?>>Official Rules</MFBLink></li>
    
</ul>
<!-- <?php echo $this->TplName();  ?> -->
</div>