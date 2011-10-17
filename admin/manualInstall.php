<?php 
require_once dirname(__FILE__) . '/../inc/prepend.inc.php';
//Require Config

$strAppName = $_GET['app'];

//Require the install
SApplication::InstallApp($strAppName);

header('location: ' . __BASE_URL__ . '/gettingStarted.php?success=1');
?>