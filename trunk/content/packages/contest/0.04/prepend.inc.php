<?php
define('__CONTEST_PACKATE_VERSION__','0.04');
define('__CONTEST_DIR__', __PACKAGE_DIR__ . '/contest/' . __CONTEST_PACKATE_VERSION__);
require(__CONTEST_DIR__ . '/inc/FBContestApplicationBase.class.php');
require(__CONTEST_DIR__ . '/inc/_enum.inc.php');
SApplication::$arrIncludes['indexBase']  = __CONTEST_DIR__ . '/indexBase.php';
SApplication::$arrIncludes['adminBase']  = __CONTEST_DIR__ . '/adminBase.php';
SApplication::$arrIncludes['exportToCsvBase'] = __CONTEST_DIR__ . '/exportToCsvBase.php';


define('__CONTEST_ASSETS__', CONTENT_URL . '/packages/contest/' . __CONTEST_PACKATE_VERSION__);
define('__CONTEST_TPL_ASSETS__', __CONTEST_DIR__ . '/templates');
define('__CONTEST_PHP_ASSETS__', __CONTEST_DIR__ . '/inc');
define('__CONTEST_JS_ASSETS__', __CONTEST_ASSETS__ . '/js');
define('__CONTEST_CSS_ASSETS__', __CONTEST_ASSETS__ . '/css');

//Data Layer
SApplication::$arrIncludes['ContestEntry'] = __CONTEST_PHP_ASSETS__ . '/data_layer/ContestEntry.class.php';
SApplication::$arrIncludes['ContestFormAnswer'] = __CONTEST_PHP_ASSETS__ . '/data_layer/ContestFormAnswer.class.php';
SApplication::$arrIncludes['ContestFormFieldType'] = __CONTEST_PHP_ASSETS__ . '/data_layer/ContestFormFieldType.class.php';


define('__CONTEST_SQL___', __CONTEST_DIR__ . '/sql');

FBAppForm::AddJSAsset(__CONTEST_JS_ASSETS__ . '/contestApp.js');
FBAppForm::AddCssAsset(__CONTEST_CSS_ASSETS__ . '/contestApp.css');