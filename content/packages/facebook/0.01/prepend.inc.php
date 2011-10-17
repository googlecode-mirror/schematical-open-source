<?php
define('__MFB_FACEBOOK_VERSION__', '0.01');
define('__FACEBOOK_DIR__', __PACKAGE_DIR__ . '/facebook/' . __MFB_FACEBOOK_VERSION__);

//require_once( __FACEBOOK_DIR__ . '/base_facebook.class.php');
require_once( __FACEBOOK_DIR__ . '/facebook.class.php');
SApplication::$arrIncludes['MLCFBDriver'] = __FACEBOOK_DIR__ . '/MLCFBDriver.class.php';
