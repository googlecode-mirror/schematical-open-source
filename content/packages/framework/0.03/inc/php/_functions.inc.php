<?php 

function _p($strString, $blnHtmlEntities = true) {
	// Standard Print
	if ($blnHtmlEntities && (gettype($strString) != 'object'))	
		print(htmlentities($strString, ENT_COMPAT, 'UTF-8'));
	else
		print($strString);
}
?>