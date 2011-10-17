<?php
class FBAppAdminForm extends FBAppForm{
	
	public function Init(){
		parent::Init();
		if(FBApplicationBase::IsAdminUser()){
			//were good
		}else{
			$this->Redirect('/index.php');
		}
	}
	public function GetTemplateLoc($strPageName = null){
		if(is_null($strPageName)){
			$strPageName = get_class($this);
		}
		return parent::GetTemplateLoc('admin/' . $strPageName);
	}
}