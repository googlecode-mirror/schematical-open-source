<?php
/* 
 * User defined configuration settings will go in here
 */
define('__CONTEST_DEBUG_MODE__', 'true');//Comment this out before going live
//define('__ADMIN_FBUIDS__', '8601266,100001842107744');
//defined('__SHOW_UNLIKED_PAGE__', 'true');
//It is strongly recomended you set the template version 
//define('APP_TEMPLATE_LOC', 'x.x');//only put the core in here
//here so the users can upgrade if they want
SApplication::LoadPackage('framework', '0.02');
SApplication::LoadPackage('contest', '0.02');
?>