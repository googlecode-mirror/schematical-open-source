<?php
/* 
 * User defined configuration settings will go in here
 */
//The Facebook App Id for this specific app
define('__CUST_FB_APP_ID__', 'your_facebook_app_id');
//The Facebook App Secret Key for this specific app
define('__CUST_FB_APP_SECRET__', 'your_facebook_app_secret');


define('__CONTEST_DEBUG_MODE__', 'true');//Comment this out before going live
//A comma delimited list of admin facebook user ids
define('__ADMIN_FBUIDS__', '8601266,100001842107744');
//DO NOT CHANGE THIS
define('APP_TEMPLATE_LOC', '29/15');
//A list of packages that are required to run this app
SApplication::LoadPackage('framework', '0.03');
SApplication::LoadPackage('contest', '0.04');
//The data base this app needs to run
SApplication::InitDB(DATABASE_1);
?>