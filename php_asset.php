<?php
require(dirname(__FILE__) . '/inc/prepend.inc.php');
SApplication::BootPhpAsset($_GET[MFBQS::PACKAGE], $_GET[MFBQS::VERSION], $_GET[MFBQS::CTL_FILE]);