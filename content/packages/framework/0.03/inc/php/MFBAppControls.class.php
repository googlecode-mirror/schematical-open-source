<?php
abstract class MFBAppControls{
	public static function RenderUploadInput(){
		$strHtml = '<form class="MFBUploadInput_Form" method="post" action="' . ['UploadInputIFrameUrl'],'" target="ifmUpload_', strName, '">'
		'<input type="hidden" name="signed_request" value="', MFBAppPublic.objSettings['signed_request'], '" />', 
		'<input type="hidden" name="MFBIdApp" value="', MFBAppPublic.objSettings['MFBIdApp'], '" />', 
		'<input type="hidden" name="fieldName" value="', strName, '" />',							
		'</form>',
		'<iframe class="MFBUploadInput_IFrame" name="ifmUpload_', strName, '" src="" width="350" height="50"  frameborder="0" />'
	}
}