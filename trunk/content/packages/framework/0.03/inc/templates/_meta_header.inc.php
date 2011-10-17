<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<script language='javascript' src='<?= __FRMWK_CORE_PUB_JS_ASSETS__; ?>/jquery-1.6.3.js'></script>
<script language='javascript' src='<?= __FRMWK_CORE_PUB_JS_ASSETS__; ?>/app.js'></script>
<script src="http://connect.facebook.net/en_US/all.js"></script>
<?php $this->RenderHeaderAssets(); ?>
<?php require(__APP_CORE_TPL_ASSETS__. '/_meta_header.inc.php'); ?>
<?php if(file_exists(__APP_TPL_ASSETS__ . '/_custom_meta.tpl.php')){ 
	require(__APP_TPL_ASSETS__ . '/_custom_meta.tpl.php');
}?>
<script language='javascript'>
	$(function(){		 
		 <?php FBApplicationBase::RenderJSInit(); ?> 
	});
</script>
