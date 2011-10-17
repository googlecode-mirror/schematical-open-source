<?php
define('__MFB_FRMWK_VERSION__', '0.03');
define('__FRMWK_DIR__', __PACKAGE_DIR__ . '/framework/' . __MFB_FRMWK_VERSION__);
require(__FRMWK_DIR__ . '/inc/php/FBApplicationBase.class.php');
require(__FRMWK_DIR__ . '/inc/php/_enum.inc.php');
require(__FRMWK_DIR__ . '/inc/php/_functions.inc.php');
SApplication::$arrIncludes['FBAppForm']  = __FRMWK_DIR__ . '/inc/php/FBAppForm.class.php';
SApplication::$arrIncludes['FBAppAdminForm']  = __FRMWK_DIR__ . '/inc/php/FBAppAdminForm.class.php';

SApplication::$arrIncludes['FBAppHeaderAsset']  = __FRMWK_DIR__ . '/inc/php/header_assets/FBAppHeaderAsset.class.php';
SApplication::$arrIncludes['FBAppMetaHeaderAsset']  = __FRMWK_DIR__ . '/inc/php/header_assets/FBAppMetaHeaderAsset.class.php';
SApplication::$arrIncludes['FBAppJSHeaderAsset']  = __FRMWK_DIR__ . '/inc/php/header_assets/FBAppJSHeaderAsset.class.php';
SApplication::$arrIncludes['FBAppCssHeaderAsset']  = __FRMWK_DIR__ . '/inc/php/header_assets/FBAppCssHeaderAsset.class.php';

//Data Layer
SApplication::$arrIncludes['Attachment']  = __FRMWK_DIR__ . '/inc/php/data_layer/Attachment.class.php';

function __ObHandler($strRendered){
	return $strRendered;
}

//die($strControlFilePath);

//check to see if the control file exists on the front end
define('__FRMWK_CORE_ASSETS__', __PACKAGE_DIR__ . '/framework/' . __MFB_FRMWK_VERSION__);
define('__FRMWK_CORE_PUB_ASSETS__', CONTENT_URL . '/packages/framework/' . __MFB_FRMWK_VERSION__);

//Core
define('__FRMWK_CORE_CSS_ASSETS__', __FRMWK_CORE_ASSETS__ . '/css');
define('__FRMWK_CORE_PHP_ASSETS__', __FRMWK_CORE_ASSETS__ . '/inc/php');
define('__FRMWK_CORE_TPL_ASSETS__', __FRMWK_CORE_ASSETS__ . '/inc/templates');
define('__FRMWK_CORE_PUB_PHP_ASSETS__', __FRMWK_CORE_PUB_ASSETS__ . '/inc/php');
define('__FRMWK_CORE_PUB_JS_ASSETS__', __FRMWK_CORE_PUB_ASSETS__ . '/js');
define('__FRMWK_CORE_PUB_CSS_ASSETS__', __FRMWK_CORE_PUB_ASSETS__ . '/css');
define('__FRMWK_CORE_PUB_IMAGE_ASSETS__', __FRMWK_CORE_PUB_ASSETS__ . '/images');

define('__FRMWORK_SQL___', __FRMWK_DIR__. '/sql');

SApplication::AddClass('SFB', __FRMWK_CORE_PHP_ASSETS__ . '/SchematicalFacebookClient.class.php');

function FRAMEWK_RUN(){
	define('__APP_ROOT__', APP_FRONTEND_DIR);
	
	define('__APP_TEMPLATE_ASSETS__', sprintf('%s/app_templates/%s/_core/assets', CONTENT_URL, APP_TEMPLATE_LOC));
	
	define('__APP_CORE_ASSETS__', APP_BACKEND_CORE_DIR . '/inc');
	define('__APP_CORE_PUB_ASSETS__', __APP_URL__ . '/assets');//TODO: Fix this to point at the right spot
	define('__APP_CORE_PHP_ASSETS__', __APP_CORE_ASSETS__ . '/php');
	define('__APP_CORE_TPL_ASSETS__', __APP_CORE_ASSETS__ . '/templates');
	define('__APP_CORE_PUB_CSS_ASSETS__', __APP_CORE_PUB_ASSETS__ . '/css');
	define('__APP_CORE_PUB_JS_ASSETS__', __APP_CORE_PUB_ASSETS__ . '/js');
	define('__APP_IMAGE_PUB_ASSETS__', __APP_CORE_PUB_ASSETS__ . '/images');
	//Normal
	
	define('__APP_TEMPLATE_INC__', APP_BACKEND_DIR . '/_core/inc');	
	define('__APP_TEMPLATE_PHP_ASSETS__', __APP_TEMPLATE_INC__ . '/php');
	define('__APP_TEMPLATE_TPL_ASSETS__', __APP_TEMPLATE_INC__ . '/templates');
	define('__APP_TEMPLATE_CSS_ASSETS__', __APP_TEMPLATE_ASSETS__ . '/css');
	define('__APP_TEMPLATE_JS_ASSETS__', __APP_TEMPLATE_ASSETS__ . '/js');
	define('__APP_TEMPLATE_IMAGE_ASSETS__', __APP_TEMPLATE_ASSETS__ . '/images');
	
	//Normal
	define('__APP_ASSETS__', APP_FRONTEND_DIR . '/assets');
	define('__APP_PUB_ASSETS__', __APP_URL__ . '/assets');
	define('__APP_PHP_ASSETS__', __APP_ASSETS__ . '/php');
	define('__APP_TPL_ASSETS__', __APP_ASSETS__ . '/templates');
	define('__APP_CSS_ASSETS__', __APP_PUB_ASSETS__ . '/css');
	define('__APP_JS_ASSETS__', __APP_PUB_ASSETS__ . '/js');
	define('__APP_IMAGE_ASSETS__', __APP_PUB_ASSETS__ . '/images');

	if(!defined('FORM_VAR_ENC_KEY')){
		define('FORM_VAR_ENC_KEY', md5('aeckiIE99381923'));
	}
	
	
	if(defined('APP_CONTROL_LOC')){
		if(file_exists(APP_CONTROL_LOC)){
			$strOldPrependLoc = sprintf('%s/_core/inc/php/prepend.inc.php', APP_BACKEND_DIR);
			if(file_exists($strOldPrependLoc)){
				require_once($strOldPrependLoc);
			}else{
				require_once(sprintf('%s/prepend.inc.php', APP_BACKEND_DIR));
			}	
			require_once(APP_CONTROL_LOC);
			
		}else{
			throw new Exception("Control file not found in Account App FTP Dir(" . APP_CONTROL_LOC . ")");
		}
	}
}
SApplication::AddRunFunction('FRAMEWK_RUN');
SApplication::LoadPackage('facebook', '0.01');



