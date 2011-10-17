<?php
abstract class FBAppForm{
	const callBack = 'callBack';
	const callBackData = 'callBackData';
	const html = 'html';
	const signed_request = 'signed_request';
	const location = 'location';
	const window_location = 'window_location';
	const form_vars = 'form_vars';
	protected $strAdminBarTpl = null;
	protected $arrEvents = array();
	protected $arrPageChangeListeners = array();
	protected $arrResponse = array();
	protected $strTemplate = null;
	protected $strCurrentTemplateName = null;
	protected $arrFormVars = array();
	protected static $arrGlobalTemplateData = array();
	protected static $arrHeaderAssets = array();
	
	/**
	 * Starts the core framework 
	 */
	public function Init(){
		$strPageName = null;
		if(array_key_exists('tpl', $_GET)){
			$strPageName = $_GET['tpl'];
		}
		$this->ChangeTpl($strPageName);
				
		$this->AddAction('.MFBLink', 'MFBLink_click');
		if(
			(FBApplicationBase::$strRenderMode == FBApplicationBase::RENDER_MODE_AJAX) &&
			(array_key_exists(self::form_vars, $_POST))
		){
				$this->arrFormVars = unserialize($_POST[self::form_vars]);
		}else{
			$this->arrFormVars = array();
		}
		
		if((defined('__SHOW_UNLIKED_PAGE__')) && (FBContestApplication::$arrFBData['page']['liked'] != 1)){
			$this->strTemplate = $this->GetTemplateLoc('unLiked');
		}
	}
	/*******************************************************
	 * 
	 *           Form Functions
	 * 
	 *******************************************************/
	
	/**
	 * This function allows the end user to overwrite to add a function 
	 * That listens to a template change in the form
	 * @param String $strTemplateName
	 */
	public function Form_Change_TPL($strTemplateName){ }
	/**
	 * This function allows the end user to overwrite to add a function
	 * that gets called when the form is called
	 */
	public function Form_Create(){ }
	
	public function MFBLink_click($mixActionParameter){		
		if(array_key_exists('ctl_file', $mixActionParameter)){
			$arrQS = array();
			if(array_key_exists('tpl', $mixActionParameter)){
				$arrQS['tpl'] = $mixActionParameter['tpl'];
			}
			$this->Redirect($mixActionParameter['ctl_file'], $arrQS);
		}
		if(array_key_exists('tpl', $mixActionParameter)){
			$this->ChangeTpl($mixActionParameter['tpl'], $mixActionParameter);
		}		
	}
	public function ChangeTpl($strTemplateName, $mixActionParameter = null){
		if(is_null($strTemplateName)){
			$strTemplateName = get_class($this);
		}
		$strTemplateLoc = $this->GetTemplateLoc($strTemplateName);
		
		$this->strCurrentTemplateName = $strTemplateName;
		$this->strTemplate = $strTemplateLoc;
		$this->Form_Change_TPL($strTemplateName);
		if(array_key_exists($strTemplateName, $this->arrPageChangeListeners)){
			$strFunctionName = $this->arrPageChangeListeners[$mixActionParameter['tpl']];
			$this->{$strFunctionName}($mixActionParameter);
		}
		
	}
	public function TplName(){
		return $this->strCurrentTemplateName;
	}
	public function TplMatch($strMatch){
		return ($this->TplName() == $strMatch);		
	}
	
	public function AddAction($strEvent, $strFunctionName, $strEventType = 'click'){
		$strEventFull = sprintf('%s_%s', $strEvent, $strEventType);
		$this->arrEvents[$strEventFull] = $strFunctionName;
	}
	public function ListenForTplChange($strTpl, $strFunction){
		$this->arrPageChangeListeners[$strTpl] = $strFunction;
	}
	public function ExicuteEvents(){
		if(array_key_exists('event', $_POST)){
			$this->TriggerEvent($_POST['event']);
		}		
	}
	public function TriggerEvent($strEvent){
		$strActionParameter = null;
		if(array_key_exists('action_parameter', $_POST)){
			$strActionParameter = $_POST['action_parameter'];
		}
		$strFunctionName = $this->arrEvents[$strEvent];
		$this->$strFunctionName($strActionParameter);
	}
	public function SetCallBack($strFunctionName){ 
		$this->arrResponse['callBack'] = $strFunctionName;
	}
	public function SetCallBackData($mixData){
		if(!array_key_exists(self::callBackData, $this->arrResponse)){
			$this->arrResponse[self::callBackData] = array();
		}
		$this->arrResponse[self::callBackData] = $mixData;
	}
	public function GetEvents(){
		return $this->arrEvents; 
	}
	
	public function Render(){		
		echo $this->EvaluateTemplate($this->strTemplate);		
	}
	public function RenderAsAjax(){		
		
		$this->arrResponse[self::html] = $this->EvaluateTemplate($this->strTemplate);
		$this->arrResponse[self::signed_request] = FBApplicationBase::$strSignedRequest;	
		$this->arrResponse[self::form_vars] = serialize($this->arrFormVars);
		echo json_encode($this->arrResponse);
	}
	/**
	 * Rendres the header assets
	 */
	public function RenderHeaderAssets(){
		$strHeaderAssets = '';	
		foreach(self::$arrHeaderAssets as $intIndex=>$objHeader){
			$strHeaderAssets .= $objHeader->__toString();
		}
		echo $strHeaderAssets;
	}
	public function Redirect($strLocation, $arrQSData = array()){

		$strQS = '?';
		foreach($arrQSData as $strKey=>$mixValue){
			$strQS .= $strKey . '=' . $mixValue . "&";
		}
		$strQS = substr($strQS, 0, strlen($strQS) -1);
		$strLocation .= $strQS;
		if(FBApplicationBase::$strRenderMode == FBApplicationBase::RENDER_MODE_NORMAL){
			header('location:' . $strLocation);
			die('redirecting');
		}else{
			$this->arrResponse[self::location] = $strLocation;
			die(json_encode($this->arrResponse));
		}
	}
	public function RenderAdminForm($blnForceRender = false){
		if(FBApplicationBase::IsAdmin() || $blnForceRender){
			$strTplLoc = self::GetTemplateLoc('adminBar');
			require($strTplLoc);
		}
	}
	public function RenderHeader($strHeaderName = '_header'){
		if(FBApplicationBase::$strRenderMode == FBApplicationBase::RENDER_MODE_NORMAL){
			require(__FRMWK_CORE_TPL_ASSETS__ . '/_header.tpl.php');
		}
		$strHeaderLoc = $this->GetTemplateLoc($strHeaderName);
		if(file_exists($strHeaderLoc)){
			require($strHeaderLoc);
		}
	}
	public function RenderFooter($strFooterName = '_footer'){
		$strFooterLoc = $this->GetTemplateLoc($strFooterName);		
		
		if(file_exists($strFooterLoc)){
			require($strFooterLoc);
		}
		
		if(FBApplicationBase::$strRenderMode == FBApplicationBase::RENDER_MODE_NORMAL){
			require(__FRMWK_CORE_TPL_ASSETS__ . '/_footer.tpl.php');
		}		
	}
	public function RenderAdminBar(){
		if(!is_null($this->strAdminBarTpl)){
			if(FBApplicationBase::IsAdminUser()){
											
				require($this->strAdminBarTpl);				
			}
		}
	}
	public function SetAdminBarTpl($strAdminBarTpl){
		$this->strAdminBarTpl = $strAdminBarTpl;
	}
	public function GetTemplateLoc($strPageName = null){
		return self::LocateTemplate($strPageName);
	}
	public static function LocateTemplate($strPageName){
		if(is_null($strPageName)){
			$strPageName = get_class($this);
		}
		$strCustomTpl = __APP_TPL_ASSETS__ . '/' . $strPageName . '.tpl.php';
		if(file_exists($strCustomTpl)){
			return $strCustomTpl;
		}else{
			$strCoreTpl = __APP_CORE_TPL_ASSETS__ . '/' . $strPageName . '.tpl.php';						
			if(file_exists($strCoreTpl)){
				return $strCoreTpl;
			}else{
				$strFrameworkTpl = __FRMWK_CORE_TPL_ASSETS__ . '/' . $strPageName . '.tpl.php';
				if(file_exists($strFrameworkTpl)){
					return $strFrameworkTpl;
				}else{
					throw new Exception("No template file for '" . $strPageName . "' exists");
				}
			} 
		}
	}
	public static function TplData($strKey, $mixValue){
		self::$arrGlobalTemplateData[$strKey] = $mixValue;
	}
	public function EvaluateTemplate($strTemplate, $arrTplData = array()){		

        if ($strTemplate) {      			
        	foreach(self::$arrGlobalTemplateData as $strKey=>$mixValue){
        		$$strKey = $mixValue;
        	}
        	
        	foreach($arrTplData as $strKey=>$mixValue){
        		$$strKey = $mixValue;
        	}
            // Store the Output Buffer locally
            $strAlreadyRendered = ob_get_contents();
            ob_clean();
			
            // Evaluate the new template
            $blnStart = ob_start('__ObHandler');
                require($strTemplate);
                $strTemplateEvaluated = ob_get_contents();
            
            ob_end_clean();            

            // Restore the output buffer and return evaluated template
            print($strAlreadyRendered);
                        

            return $strTemplateEvaluated;
        } else
            return null;
	}
	
	public function FormVar($strKey, $mixValue = null){
		if(is_null($mixValue)){
			if(array_key_exists($strKey, $this->arrFormVars)){
				return $this->arrFormVars[$strKey];
			}else{
				return null;
			}
		}else{
			return $this->arrFormVars[$strKey] = $mixValue;
		}
	}
	public static function AddJSAsset($strSrc){
		self::$arrHeaderAssets[] = new FBAppJSHeaderAsset($strSrc);
	}
	public static function AddCssAsset($strSrc){
		self::$arrHeaderAssets[] = new FBAppCssHeaderAsset($strSrc);
	}
	public static function AddMetaAsset($strName, $strContent){
		self::$arrHeaderAssets[] = new FBAppMetaHeaderAsset($strName, $strContent);
	}
	
	
}

