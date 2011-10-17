<?php
/**
 * This file bassically boots up the application
 */

require_once(dirname(__FILE__) . '/config.global.php');

define('DS', DIRECTORY_SEPARATOR);
//SApplication::$arrIncludes[''] = '';

define('__INC_DIR__', __DOCROOT__ . '/inc');
define('APP_DIR', __DOCROOT__ . '/apps');
define('CONTENT_DIR', __DOCROOT__ . '/content');
define('CONTENT_URL', __BASE_URL__ . '/content');
define('__PACKAGE_DIR__', CONTENT_DIR . '/packages');
define('__APP_TPL_DIR__', CONTENT_DIR . '/app_templates');
define('__UPLOADS_DIR__', CONTENT_DIR . '/uploads');
define('__UPLOADS_URL__', CONTENT_URL . '/uploads');
define('__PHP_ASSET_URL_BASE__', __BASE_URL__ . '/php_asset.php');
function __autoload($strClassName){
	if(!array_key_exists($strClassName, SApplication::$arrIncludes)){
		throw new Exception('No class named "' . $strClassName . '" exists'); 
	}
	require_once(SApplication::$arrIncludes[$strClassName]);
}
require_once(__INC_DIR__ . '/_enum.inc.php');
require_once(__INC_DIR__ . '/_core/SApplication.class.php');


SApplication::LoadPackage('sdatalayer', '0.01');
//Data layer
SApplication::$arrIncludes['App'] = __INC_DIR__ . '/_core/data_layer/App.class.php';


SApplication::InitDB(DATABASE_1);
