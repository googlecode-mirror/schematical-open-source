<?php
/* 
 * User defined configuration settings will go in here
 */
define('__CONTEST_DEBUG_MODE__', 'true');//Comment this out before going live
define('__ADMIN_FBUIDS__', '8601266,100001842107744');
//defined('__SHOW_UNLIKED_PAGE__', 'true');
//It is strongly recomended you set the template version 
//here so the users can upgrade if they want
MFBPackageDriver::LoadPackage('framework', '0.02');
MFBPackageDriver::LoadPackage('contest', '0.02');
?>