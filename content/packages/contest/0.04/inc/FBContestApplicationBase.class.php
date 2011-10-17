<?php
abstract class FBContestApplicationBase extends FBApplicationBase{
	protected static $objEntry = null;
	protected static $arrAnswerFields = null;
	public static function Init(){
		parent::Init();
		FBContestApplication::$strJSMainObject = 'MFBContestApp';

	}
	public static function GetContestEntries(){
		$arrEntries = ContestEntry::LoadAll()->getCollection();
		return $arrEntries;
	}
	
	public static function RenderJSSettings(){
		$objSettings = parent::RenderJSSettings();
		
		return $objSettings;
	}
	public static function SetCurrentEntry($objEntry){
		FBContestApplication::$objEntry = $objEntry;
		FBContestApplication::$arrAnswerFields = FBContestApplication::$objEntry->GetContestFormAnswerAsIdArray();
	}
	public static function GetCurrentEntry(){
		return FBContestApplication::$objEntry;
	}
	public static function CanSubmit(){
		if(defined('__CONTEST_DEBUG_MODE__')){
			return true;
		}
		
		$arrEntries = ContestEntry::LoadByFBUID($intFBUID);
		
		
		if(count($arrEntries) > 0){
			return false;
		}else{
			return true;
		}
	}
	public static function AddEntry($arrEntryData){
		
		if(!FBContestApplication::CanSubmit()){
			return false;
		}
		
		
		
		$objEntry = new ContestEntry();
		
		$objEntry->fbuid = FBContestApplication::FBUid();
		$objEntry->creDate = MFBDateTime::Now();
		$objEntry->Save();
		foreach($arrEntryData as $strName => $arrData){
			$objEntryAnswer = new ContestFormAnswer();
			$objEntryAnswer->idContestEntry = $objEntry->idContestEntry;
			$objEntryAnswer->name = $strName;
			$objEntryAnswer->idContestFormFieldType = $arrData['type'];
			$objEntryAnswer->value = $arrData['value'];
			$objEntryAnswer->save();
		}
		return true;
	}
	public static function GetContestFields($objEntry = null){		
			if(is_null($objEntry)){
				if(is_null(FBContestApplication::$objEntry)){
					throw new Exception('You must pass in a second parameter of type "ContestEntry" or call "SetCurrentEntry" before calling this method');
				}
				$objEntry = self::$objEntry;
			}
			if(((!is_null(self::$objEntry)) && ($objEntry->idContestEntry != self::$objEntry->idContestEntry)) ||  (is_null(self::$arrAnswerFields))){
				$arrAnswerFields = $objEntry->GetContestFormAnswerAsIdArray();
			}else{
				$arrAnswerFields = self::$arrAnswerFields;
			}
			return $arrAnswerFields;	
			
	}
	public static function RenderContestField($strFieldName, $mixOptions = null,$objEntry = null){
		
		if(is_null($objEntry)){
			if(is_null(FBContestApplication::$objEntry)){
				throw new Exception('You must pass in a second parameter of type "ContestEntry" or call "SetCurrentEntry" before calling this method');
			}
			$objEntry = self::$objEntry;
		}
		$strFieldName = strtolower($strFieldName);
		//Search for reserved words	
			
		switch($strFieldName){
			case('credate'):					
				if(is_null($mixOptions)){			
					return _p($objEntry->creDate);
				}elseif(is_string($mixOptions)){	
					$objDateTime = new DateTime($objEntry->creDate);	
					return _p($objDateTime->format($mixOptions));
				}elseif(is_array($mixOptions)){
					if(array_key_exists('format', $mixOptions)){
						$objDateTime = new DateTime($objEntry->creDate);
						return _p($objDateTime->format($mixOptions['format']));
					}else{
						return _p($objEntry->creDate);					
					}
				}
			default:
				$arrAnswerFields = FBContestApplication::GetContestFields($objEntry);
			
				$strTplLoc = null;
				foreach($arrAnswerFields as $intIndex=>$objAnswerField){
					if(strtolower($objAnswerField->name) == strtolower($strFieldName)){
						switch($objAnswerField->idContestFormFieldType){
							case(MFBContestFormFieldType::Text):
							case(MFBContestFormFieldType::LongText):
							case(MFBContestFormFieldType::Select):					
								
								_p($objAnswerField->value);
							break;
							case(MFBContestFormFieldType::Upload):
								//$objAttachment = Attachment::Load($objAnswerField->Value);
								//if(!is_null($objAttachment)){
								
										$_HREF = __UPLOADS_URL__ . DS . $objAnswerField->value;
									$strTplLoc = FBContestApplication::CheckForDefaultTemplate('_uploadEntry');
								//}else{
									//$_HREF = null;
								//}
								
							break;
							case(MFBContestFormFieldType::Youtube):
								$strTplLoc = FBContestApplication::CheckForDefaultTemplate('_youtubeEntry');
							break;
							default:
								throw new Exception("No render code for type '" . $objAnswerField->idContestFormFieldType . "'");
						}
						$_VALUE = $objAnswerField->value;
						if(!is_null($strTplLoc)){
							require($strTplLoc);
						}
						return;
					}
					
				}
			break;
		}
	}
	public static function DeleteContestEntry($objContestEntry){
		if(!is_null($objContestEntry)){
			$objContestEntry->delDate = MFBDateTime::Now();
			$objContestEntry->save();
			return true;
		}
		return false;
	}
	public static function ExportToCSV($arrHiddenFields = array()){
		$strReturn = '';
		$arrEntries = FBContestApplication::GetContestEntries();
		$objEntry = $arrEntries[0];
		$arrFields = $objEntry->GetContestFormAnswerAsIdArray();
		foreach($arrFields as $intFieldIndex=>$objField){
			if(!array_search($objField->name, $arrHiddenFields)){
				$strReturn .= $objField->name . ',';
			}
		}
		$strReturn = substr($strReturn, 0, (strlen($strReturn) - 1));
		
		$strReturn .= "\n";
		foreach($arrEntries as $intIndex=>$objEntry){
			if($intIndex != 0){
				$arrFields = $objEntry->GetContestFormAnswerAsIdArray();
			}		
			foreach($arrFields as $intFieldIndex=>$objField){
				if(!array_search($objField->name, $arrHiddenFields)){
					switch($objField->idContestFormFieldType){
						case(MFBContestFormFieldType::Select):
						case(MFBContestFormFieldType::Text):
						case(MFBContestFormFieldType::LongText):
							$strReturn .=  $objField->value . ',';
						break;
						case(MFBContestFormFieldType::Upload):
							$objAttachment = Attachment::Load($objField->value);
							if(!is_null($objAttachment)){	
	 							$strReturn .= AWS_BUCKET_URL . DS . AWS_BUCKET_NAME . DS . $objAttachment->value . ',';
							}
	 					break;	
					}					
				}				
			}
			$strReturn = substr($strReturn, 0, (strlen($strReturn) - 1));
			$strReturn .= "\n";
		}
		return $strReturn;
	}
	
	
}