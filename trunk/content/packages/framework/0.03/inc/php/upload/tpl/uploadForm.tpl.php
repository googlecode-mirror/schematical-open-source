<html>
	<head>
		<script src='<?php _p(__FRMWK_CORE_PUB_JS_ASSETS__); ?>/jquery-1.6.3.js'></script>
		<script src='<?php _p(__FRMWK_CORE_PUB_JS_ASSETS__); ?>/upload/upload.iframe.js'></script>
	</head>
	<body>
		<form id='frmUplaod' action='' method='post' enctype="multipart/form-data" >
			<input type='hidden' name='fieldName' value='<?php _p($strFieldName); ?>' />
			<input type='hidden' name='signed_request' value='<?php _p($strSignedRequest); ?>' />
			<input id='uplFile' type='file' name='MFBFile' />
		</form>
		<div id='divMessage'></div>
	</body>
</html>