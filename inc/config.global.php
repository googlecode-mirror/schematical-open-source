<?php
/**
 * This config file will hold global configurations such as the __DOCROOT__ and DB credentials
 */
define('__DOCROOT__', '/var/www/html/');

define('__BASE_URL__', (array_key_exists('HTTPS', $_SERVER)?'https':'http') . '://lab.mattleaconsulting.com/schematical');
//define('__DOCROOT__', 'xyz');
define('DATABASE_1', serialize(array(
	'host'=>'your_db_host',
	'db_name'=>'your_db_name',
	'user'=>'your_db_username',
	'password'=>'your_db_password'
)));
//Facebook app ID for global use(Optional if you defined it in /apps/{your_app_name}/_config.inc.php)
define('__GLOBAL_FB_APP_ID__','your_fb_app_id');
//Facebook app secret key for global use(Optional if you defined it in /apps/{your_app_name}/_config.inc.php)
define('__GLOBAL_FB_APP_SECRET__','your_fb_app_secure_key');