<?php
class indexBase extends FBAppForm{	
	protected static $strWallComment = '';
	protected static $strWallSubComment = '';
	
	protected static $arrEntries = array();
	protected static $objCurrWallComment = null;
	protected static $intIdWallCommentDispayReplyForm = null;
	public function Form_Create(){
		$this->AddAction('.MFBSubmitButton', 'MFBSubmitButton_submit', 'submit');
		$this->AddAction('.FBProxyControl', 'FBProxyControl_permissions', 'permissions');
		$this->AddAction('.MFBDisplayEntries', 'MFBDisplayEntries_click');
		$this->AddAction('.MFBDisplayContestForm', 'MFBDisplayContestForm_click');
		
		$this->SetAdminBarTpl($this->GetTemplateLoc('_adminBar'));
	}
	public function MFBSubmitButton_submit($mixActionParameter){
		$arrContestData = $_POST['MFBContestData'];
		
		$blnSuccess = FBContestApplication::AddEntry($arrContestData);
		if($blnSuccess){
			$this->strTemplate = $this->GetTemplateLoc('thankYou');
		}else{
			$this->strTemplate = $this->GetTemplateLoc('contestError');
		}
		return $blnSuccess;
	}
	public function MFBDisplayContestForm_click(){
		$this->SetCallBack('RenderMFBInputs');
		$this->strTemplate = $this->GetTemplateLoc('index');
	}
	
	public static function RenderEntries(){
		self::$arrEntries = FBContestApplication::GetContestEntries();
		foreach(index::$arrEntries as $intIndex=>$objEntry){
			FBContestApplication::SetCurrentEntry($objEntry);
			require(__APP_TPL_ASSETS__ . '/displaySingleEntry.tpl.php');
		}
	}
}