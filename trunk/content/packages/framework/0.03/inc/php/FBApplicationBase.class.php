<?php
abstract class FBApplicationBase{
	const RENDER_MODE_NORMAL = 1;
	const RENDER_MODE_AJAX = 2;
	public static $strAccessTokenOverride = null;
	public static $strRenderMode = null;
	public static $arrClassFiles = array();
	public static $objFacebook = null;
	public static $objForm = null;
	public static $strSignedRequest = null;
	public static $arrFBData = null;
	public static $strJSMainObject = null;	
	public static $arrExtraSettings = array();
	
	
	protected static function Init(){
		if(array_key_exists('signed_request', $_REQUEST)){
			self::$strSignedRequest = $_REQUEST['signed_request'];
			self::$arrFBData = MLCFBDriver::ParseFBSignedRequest();
		}else{
			throw new Exception('No valid Signed Request passed in');
		}
	}
	
	public static function Run($strFormName){
		if(!array_key_exists('ajax_call', $_POST)){
			self::$strRenderMode = self::RENDER_MODE_NORMAL;
		}else{
			self::$strRenderMode = self::RENDER_MODE_AJAX;
		}
		self::$objForm = new $strFormName();
		self::$objForm->Init();
		self::$objForm->Form_Create();
		self::$objForm->ExicuteEvents();
		
		if(self::$strRenderMode == self::RENDER_MODE_NORMAL){
			self::$objForm->Render();
		}elseif(self::$strRenderMode == self::RENDER_MODE_AJAX){
			self::$objForm->RenderAsAjax();
		}		
	}
	
	public static function RenderJSInit($objExtraSettings = null){
		if(self::$strRenderMode == self::RENDER_MODE_NORMAL){
			$objSettings = array();
			$arrEvents = self::$objForm->GetEvents();
			$arrNewEvents = array();
			foreach($arrEvents as $strEventFull=>$strFunction){
				$arrParts = explode('_', $strEventFull);
				$strElement = $arrParts[0];
				$strEvent = $arrParts[1];
				$arrNewEvents[] = array(
					'element'=>$strElement,
					'event'=>$strEvent
				);
			}
			$objSettings['Events'] = $arrNewEvents;
			$objSettings['strUrl'] = $_SERVER['REQUEST_URI'];
			$objSettings['signed_request'] = self::$strSignedRequest;
			$objSettings['MFBIdApp'] = SApplication::AppName();
			$objSettings['FB_APP_ID'] = __FB_APP_ID__;
			$objSettings['page'] = self::$arrFBData['page'];
			//$objSettings['page_url'] = self::ParsePageUrl();
			//Inportant URLs
			
			$objSettings['UploadInputIFrameUrl'] =  SApplication::GetPhpAssetUrl('framework', __MFB_FRMWK_VERSION__, '/upload/upload.iframe.php');
			if(!is_null($objExtraSettings)){
				foreach($objExtraSettings as $strName => $mixValue){
					$objSettings[$strName] = $mixValue;
				}
			}
			
			foreach(self::$arrExtraSettings as $strName => $mixValue){
				$objSettings[$strName] = $mixValue;
			}
			$strSettings = json_encode($objSettings);
			echo sprintf('%s.%s(%s);', self::$strJSMainObject, 'Init', $strSettings);
		}
		
	}
	
	/*
	public static function ParseFBSignedRequest(){
		if(array_key_exists('signed_request', $_REQUEST)){
			 self::$strSignedRequest = $_REQUEST['signed_request'];
		
		     list($encoded_sig, $payload) = explode('.', self::$strSignedRequest, 2); 
		
		     $arrFBData = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);
		    
		     return $arrFBData;
		}else{
			return null;
		}
	}*/
	public static function FBPostToFeed($intUserId, $arrParams, $strTag = null){
		
		$objFeedPost = new FeedPost();
		$objFeedPost->IdApp = SApplication::AppName();
		$objFeedPost->SenderFbuid = self::FBUid();
		$objFeedPost->ReciverFbuid = $intUserId;
		$objFeedPost->Data = json_encode($arrParams);
		$objFeedPost->CreDate = QDateTime::Now();
		$objFeedPost->Tag = $strTag;
		$objFeedPost->Save();
		
		
		$strCall = sprintf('/%s/feed', $intUserId);
		return self::FB($strCall, $arrParams);
	}
	public static function FBUid(){
		//Facebook original post
		//if(self::$strRenderMode == self::RENDER_MODE_NORMAL){
			//die(print_r(self::$arrFBData));
			if(array_key_exists('user_id', self::$arrFBData)){
				return self::$arrFBData['user_id'];
			}else{
				return null;
			}
		//}else{
			//$_GET['MFBFbuid'];
		//}
		
		
		//Prompt
		
	}
	public static function FBUserName(){
		$intFbuid = self::FBUid();
		$arrData = self::FB('/' . $intFbuid);
		if(array_key_exists('name', $arrData)){  
  	 		return $arrData['name'];  
		}else{
			return null;
		}
	}
	public static function FB($strCall, $arrParams = null){
		
		MLCFBDriver::Init(__FB_APP_ID__, __FB_APP_SECRET__);		
		if(!is_null(self::$strAccessTokenOverride)){
			if(is_null($arrParams)){
				$strMethod = MLCFBDriver::GET;
			}else{
				$strMethod = MLCFBDriver::POST;
			}
			$arrParams = array();
			$arrParams['access_token'] = self::$strAccessTokenOverride;
			
			return MLCFBDriver::FB($strCall, $arrParams, $strMethod);
		}else{
			return MLCFBDriver::FB($strCall, $arrParams);
		}
		/*	
		if(!is_null(self::$strAccessTokenOverride)){
			SFB::SetAccessToken(self::$strAccessTokenOverride);		
		}else{
			SFB::SetSignedRequest(self::$arrFBData);
		}
		return SFB::Call($strCall, $arrParams);*/
	}
	public static function CheckForDefaultTemplate($strTemplateName){
		$strFileName =  $strTemplateName . '.tpl.php';
		$strUserTplLoc = __APP_TPL_ASSETS__ . '/' . $strFileName;
		if(file_exists($strUserTplLoc)){
			return $strUserTplLoc;
		}
		$strDefaltTplLoc = __APP_CORE_TPL_ASSETS__ . '/' . $strFileName;
		if(file_exists($strDefaltTplLoc)){
			return $strDefaltTplLoc;
		}
		throw new Exception("Not template '" . $strTemplateName . "' exists");
	}
	public static function IsPageAdmin(){
		if(
			(array_key_exists('page', FBApplicationBase::$arrFBData)) &&
			(array_key_exists('admin', FBApplicationBase::$arrFBData['page'])) &&
			(FBApplicationBase::$arrFBData['page']['admin'] == 1)
		){
			return true;
		}else{
			return false;
		}
	}
	public static function IsAdminUser(){
		if(defined('__ADMIN_FBUIDS__')){				
			$arrParts = explode(',', __ADMIN_FBUIDS__);
			if(array_search(FBApplicationBase::FBUid(), $arrParts)){
				return true;
			}
		}
		return false;
	}
	public static function EncodeFBSignedRequest($arrFBData, $strNewSig, $strOriginalRequest = 'x.x'){
		 $strJSON = json_encode($arrFBData);
		 $strEncoded = base64_encode($strJSON);
		 $strTranslated = strtr($strEncoded, '+/', '-_');
		 
		 //list($encoded_sig, $payload) = explode('.', $strOriginalRequest, 2); 
		 $strSignedRequest = base64_encode($strNewSig) . '.' . $strTranslated;
		 return $strSignedRequest;
		 		
	}
	public static function GetFriendsData($intFBUID = null){
		if(is_null($intFBUID)){
			$intFBUID = self::FBUid();
		}		
		$strCall = sprintf('/%s/friends', $intFBUID);
		$arrFriends = self::FB($strCall);
		return $arrFriends['data'];		
	}
	public static function SetAccessTokenOverride($strAccessTokenOverride){
		self::$strAccessTokenOverride = $strAccessTokenOverride;
	}
	public static function ParsePageUrl($intIdPage = null, $intIdApp = null){
		if(is_null($intIdPage)){			
			$intIdPage = self::$arrFBData['page']['id'];
		}
		if(is_null($intIdApp)){
			$intIdApp = __FB_APP_ID__;
		}	
		$arrData = self::FB('/' . $intIdPage);
		$strUrl = $arrData['link'];
		return $strUrl . '?sk=app_' . $intIdApp;
	}
	public static function UploadFile($objFile){
		$objAttachment = new Attachment();
		$objAttachment->creDate = MFBDateTime::Now();
		$objAttachment->save();
		
	    $arrFileData = pathinfo($objFile['name']);
	    //die(print_r($arrFileData));
		$strExtension = $arrFileData['extension'];  
		$strFileName =  $objAttachment->idAttachment . '.' . $strExtension;
		$strNewPath = __UPLOADS_DIR__ . '/' . $strFileName;
		move_uploaded_file($objFile["tmp_name"], $strNewPath);
      	$objAttachment->fileLoc = $strFileName;
      	$objAttachment->save();
      	return $objAttachment;
      	

    }
}