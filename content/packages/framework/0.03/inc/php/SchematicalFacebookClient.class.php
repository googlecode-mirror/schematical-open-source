<?php 
abstract class SchematicalFacebookClient{
	const GET = 'GET';
	const POST = 'POST';
	const GRAPH_URL = 'https://graph.facebook.com/';
	
	public static $blnLog = true;
	protected static $strFBAppId = null;
	protected static $strFBAppSecret = null;
	protected static $strAccessToken = null;
	
	
	public static $CURL_OPTS = array(
	    CURLOPT_CONNECTTIMEOUT => 10,
	    CURLOPT_RETURNTRANSFER => true,
	    CURLOPT_TIMEOUT        => 60,
	    CURLOPT_USERAGENT      => 'schematical-php-0.03'//facebook-php-2.0',
    );
    
	public static function Init($strFBAppId = null, $strFBAppSecret = null){
		$this->strFBAppId = $stFBAppId;
		$this->strFBAppSecret = $strFBAppSecret;
		
	}
	public static function Call($strCall, $arrParams = array()){
		if(is_null(self::$strAccessToken)){
			throw new Exception('No Access Token Set');
		}
	    $arrParams['access_token'] = self::$strAccessToken;
	    
	    $strUrl = self::GRAPH_URL . $strCall;
	    self::Log('Call:' . $strUrl);
	    
	   
	    foreach($arrParams as $strKey=>$mixParam){
	    	self::Log('Param: ' . $strKey . '=>' . $mixParam);	
	    }
	  	$strResponse = self::MakeRequest($strUrl, $arrParams);
	    $arrData = json_decode($strResponse);
	    _dp($strResponse);
	
	    // results are returned, errors are thrown
	    if (is_array($arrData) && isset($arrData['error'])) {
	    	self::TriggerError($arrData['error']);
	    }
	    
    	return $arrData;
	}
	
	public static function MakeRequest($strUrl, $arrParams) { 
		  
	    $ch = curl_init();
	    $arrOpts = self::$CURL_OPTS;
	    
	    $arrOpts[CURLOPT_POSTFIELDS] = http_build_query($arrParams, null, '&');
	    
	    $arrOpts[CURLOPT_URL] = $strUrl;
	
	    // disable the 'Expect: 100-continue' behaviour. This causes CURL to wait
	    // for 2 seconds if the server does not support this header.
	    if (isset($arrOpts[CURLOPT_HTTPHEADER])) {
			$existing_headers = $arrOpts[CURLOPT_HTTPHEADER];
			$existing_headers[] = 'Expect:';
			$arrOpts[CURLOPT_HTTPHEADER] = $existing_headers;
	    } else {
			$arrOpts[CURLOPT_HTTPHEADER] = array('Expect:');
	    }
	
	    curl_setopt_array($ch, $arrOpts);
	    $strData = curl_exec($ch);
	
	    if (curl_errno($ch) == 60) { // CURLE_SSL_CACERT	      
			curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/fb_ca_chain_bundle.crt');
			$strData = curl_exec($ch);
	    }
	    if ($strData === false) {
		    self::TriggerError(curl_error($ch));		          
	    }
	    curl_close($ch);
	    return $strData;
	}
	public static function JsonEncode($arrParams){
	  foreach ($arrParams as $strKey => $mixValue) {
	      if (!is_string($mixValue)) {
	        $arrParams[$strKey] = json_encode($mixValue);
	      }
	   }
	   return $arrParams;
	}
	public static function  SetSignedRequest($mixSignedRequest) {
		if(is_string($mixSignedRequest)){
			$arrSignedRequest = self::ParseFBSignedRequest($mixSignedRequest);
		}elseif(is_array($mixSignedRequest)){
			$arrSignedRequest = $mixSignedRequest;
		}elseif(is_null($mixSignedRequest)){
			$arrSignedRequest = self::ParseFBSignedRequest();
		}else{
			throw new Exception('Invalid Parameter Passed in');
		}
	    if (!isset($arrSignedRequest['oauth_token'])) {
	    	return null;
	    }
	    self::$strAccessToken = $arrSignedRequest['oauth_token'];
	}
	public static function SetAccessToken($strAccessToken){
		self::$strAccessToken = $strAccessToken;
	}
	public static function ParseFBSignedRequest($strSignedRequest = null){
		 if(is_null($strSignedRequest)){
		 	$strSignedRequest = $_REQUEST["signed_request"];
		 }	
	     list($encoded_sig, $payload) = explode('.', $strSignedRequest, 2); 
	
	     $arrFBData = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
	    
	     return $arrFBData;
	}
	public function TriggerError($strMessage){
		throw new Exception($strMessage);
	}
	public function Log($strLog){
		error_log($strLog);
	}
	
}
abstract class SFB extends SchematicalFacebookClient{
	
	
}