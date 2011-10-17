<?php 
define('__SDATALAYER_PACKAGE_VERSION__','0.01');
define('__SDATALAYER_DIR__', __PACKAGE_DIR__ . '/sdatalayer/' . __SDATALAYER_PACKAGE_VERSION__);
//require(__SDATALAYER_DIR__ . '/inc/FBPollApplicationBase.class.php');


define('__SDATALAYER_PHP_ASSETS__', __SDATALAYER_DIR__ . '/inc/php');

SApplication::AddClass('BaseEntity', __SDATALAYER_PHP_ASSETS__ . '/BaseEntity.class.php');
SApplication::AddClass('BaseEntityCollection', __SDATALAYER_PHP_ASSETS__ . '/BaseEntityCollection.class.php');
SApplication::AddClass('DataConnectionBase', __SDATALAYER_PHP_ASSETS__ . '/DataConnectionBase.class.php');
SApplication::AddClass('MFBDateTime', __SDATALAYER_PHP_ASSETS__ . '/MFBDateTime.class.php');
SApplication::AddClass('LoadDriver', __SDATALAYER_PHP_ASSETS__ . '/LoadDriver.class.php');
SApplication::AddClass('MySqlDataConnection', __SDATALAYER_PHP_ASSETS__ . '/MySqlDataConnection.class.php');
?>