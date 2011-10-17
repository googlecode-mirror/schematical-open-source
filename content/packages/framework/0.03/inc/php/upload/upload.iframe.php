<?php


if(!array_key_exists('MFBFile', $_FILES)){
	
	if(
		(array_key_exists('fieldName', $_POST)) &&
		(array_key_exists('signed_request', $_POST))
		
	){
		$strFieldName = $_POST['fieldName'];
		$strSignedRequest = $_POST['signed_request'];
	}
	require(__FRMWK_CORE_PHP_ASSETS__ . '/upload/tpl/uploadForm.tpl.php');
}else{
	
	$arrJSON = array();
	$arrJSON['success'] = 'false'; 
    $objAttachment = FBApplicationBase::UploadFile($_FILES['MFBFile']);
    if(!is_null($objAttachment)){
    	
		$arrJSON['success'] = 'true';
		$arrJSON['idAttachment'] = $objAttachment->idAttachment;
		$arrJSON['value'] = $objAttachment->fileLoc;
		$arrJSON['fieldName'] = $_POST['fieldName'];
		
	}
	echo json_encode($arrJSON);
}
?>
