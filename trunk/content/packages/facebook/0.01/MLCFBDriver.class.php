<?php
abstract class MLCFBDriver{
	const POST = 'POST';
	const GET = 'GET';
	
	protected static $objFacebook = null;
	public static function Init($strFBAppId = null, $strFBAppSecret = null){
		if(is_null($strFBAppId)){
			$strFBAppId = FB_APP_ID;
		}
		if(is_null($strFBAppSecret)){
			$strFBAppSecret = FB_APP_SECRET;
		}
		if(is_null(self::$objFacebook) || (self::$objFacebook->getAppId() != $strFBAppId))
		self::$objFacebook = new Facebook(array(
		  'appId'  => $strFBAppId,
		  'secret' => $strFBAppSecret,
		  'cookie' => true//,
		  //'fileUpload'=>false
		));
	}
	public static function ParseFBSignedRequest(){
		 $signed_request = $_REQUEST["signed_request"];
	
	     list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
	
	     $arrFBData = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
	    
	     return $arrFBData;
	}
	/**
	 * Will be used for the non facebook app portion of the website to authenticate users
	 */
	public static function GetFacebookCookie() {
		  $arrArgs = array();
		  $strCookieName = 'fbs_' . FB_APP_ID;
		  if(!array_key_exists($strCookieName, $_COOKIE)){
		  	return null;
		  }
		  parse_str(trim($_COOKIE[$strCookieName], '\\"'), $arrArgs);
		  ksort($arrArgs);
		  $strPayload = '';
		  foreach ($arrArgs as $key => $value) {
		    if ($key != 'sig') {
		      $strPayload .= $key . '=' . $value;
		    }
		  }
		  if (md5($payload . FB_APP_SECRET) != $arrArgs['sig']) {
		    return null;
		  }
		  return $arrArgs;
	}
	
	
	public static function FB($strCall, $arrParams = null, $strMethod = 'POST'){
		$arrResults = null;
		if(is_null(self::$objFacebook)){
			self::Init();
		}
		
		//$objSession = self::$objFacebook->getSession();
		// Session based API call.
		//if ($objSession) {
		error_log('FB: ' . $strCall . '-' .$strMethod);
		foreach($arrParams as $strKey=>$strValue){
			error_log('Param: ' . $strKey . '=>' . $strMethod);
		}
			  try {
			  	if(is_null($arrParams)){
			   		$arrResults = self::$objFacebook->api($strCall);
			  	}else{
			  		$arrResults = self::$objFacebook->api($strCall, $strMethod, $arrParams);
			  	}
			  } catch (FacebookApiException $e) {		  		
			    	self::HandelException($e);			    	
			    	//return $arrResults;
			  }
		/*}else{
			throw new Exception("No facebook session available");
		}*/		
		return $arrResults;
	}
	public static function GetFBSession(){
		if(is_null(self::$objFacebook)){
			self::Init();
		}
		return self::$objFacebook->getSession();
	}
	public static function HandelException(FacebookApiException $objFBException){
		switch($objFBException->getMessage()){
			case('Error validating access token: The session has been invalidated because the user has changed the password.'):
				throw new MFBFBPermissionsException();
			break;
			case('Error validating access token: Session does not match current stored session. This may be because the user changed the password since the time the session was created or Facebook has changed the session for security reasons.'):
				throw new MFBFBPermissionsException();
			break;
			default:
				throw $objFBException;
			break;
		}
	}	
}
class MFBFBPermissionsException extends Exception{
	
}