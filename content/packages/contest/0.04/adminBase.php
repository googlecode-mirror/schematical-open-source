<?php
class adminBase extends FBAppAdminForm{	
	public static $arrEntries = array();
	public static $arrAnswerFields = array();
	public function Form_Create(){
		parent::Form_Create();		
		$this->AddAction('.MFBDisplayEntries', 'MFBDisplayEntries_click');
		$this->AddAction('.MFBDeleteBtn', 'MFBDeleteBtn_click');		
		
		$this->ListenForTplChange('displayEntries', 'displayEntries_change');
	}
	public function MFBDeleteBtn_click($mixActionParameter){
		$objContestEntry = ContestEntry::Load($mixActionParameter);
		FBContestApplication::DeleteContestEntry($objContestEntry);
		$this->displayEntries_change();		
	}

	public function displayEntries_change(){
		admin::$arrEntries = FBContestApplication::GetContestEntries();
		$arrAnswerFieldsObjects = FBContestApplication::GetContestFields(admin::$arrEntries[0]);
	
		self::$arrAnswerFields = array(); 
		foreach ($arrAnswerFieldsObjects as $intIndex=>$objAnswerField){ 
			self::$arrAnswerFields[$intIndex] = $objAnswerField->Name;
		} 
		$this->strTemplate = $this->GetTemplateLoc('displayEntries');
	}
	public function RenderEntries(){
		foreach(admin::$arrEntries as $intIndex=>$objEntry){
			FBContestApplication::SetCurrentEntry($objEntry);
			$strTemplate = $this->GetTemplateLoc('_displaySingleEntry');
			require($strTemplate);
		}
	}
	
}