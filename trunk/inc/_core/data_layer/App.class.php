<?php
class App extends BaseEntity {
    const TABLE_NAME = 'App';
    const P_KEY = 'idApp';
    public function __construct(){
        $this->table =  self::TABLE_NAME;
		$this->pKey = self::P_KEY;
    }
  
	public static function LoadById($intId, $blnIncludeArchived = false){
		if($blnIncludeArchived === false){
			$strExtra = ' AND delDate IS NULL';
		}elseif($blnIncludeArchived === true){
			$strExtra = ' AND delDate IS NOT NULL';			
		}elseif(is_null($blnIncludeArchived)){
			$strExtra = '';
		}
		$sql = sprintf("SELECT * FROM %s WHERE idApp = %s %s;", self::TABLE_NAME, $intId, $strExtra);
		$result = LoadDriver::query($sql);
		while($data = mysql_fetch_assoc($result)){
			$tObj = new App();
			$tObj->materilize($data);
			return $tObj;
		}
	}
	
	
	public static function LoadByFBUID($intFBUID, $blnIncludeArchived = false){
		if($blnIncludeArchived === false){
			$strExtra = ' WHERE delDate IS NULL';
		}elseif($blnIncludeArchived === true){
			$strExtra = ' WHERE delDate IS NOT NULL';			
		}elseif(is_null($blnIncludeArchived)){
			$strExtra = '';
		}
		$sql = sprintf("SELECT * FROM %s WHERE fbuid = %s %s;",self::TABLE_NAME, $intFBUID, $blnIncludeArchived);
		$result = LoadDriver::query($sql);
		while($data = mysql_fetch_assoc($result)){
			$tObj = new App();
			$tObj->materilize($data);
			return $tObj;
		}
	}
	public static function LoadAll($blnIncludeArchived = false){
		if($blnIncludeArchived === false){
			$strExtra = ' WHERE delDate IS NULL';
		}elseif($blnIncludeArchived === true){
			$strExtra = ' WHERE delDate IS NOT NULL';			
		}elseif(is_null($blnIncludeArchived)){
			$strExtra = '';
		}
		$sql = sprintf("SELECT * FROM %s %s;", self::TABLE_NAME, $strExtra);
		$result = LoadDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$tObj = new App();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		return $coll;
	}
	
	public function GetContestFormAnswerAsIdArray(){
		return ContestFormAnswer::LoadByIdApp($this->idApp)->getCollection();
	}
/*
	public static function LoadByIdUserArr($arrIdUsers, $intMax = null, $intAppTpcd = null){
		if(!is_null($intAppTpcd)){
			$strAppType = sprintf(" AND idAppType = %s", $intAppTpcd);
		}else{
			$strAppType = "";
		} 
		$strIdUsers = implode(',', $arrIdUsers);
		$strLimit = ((!is_null($intMax))?('LIMIT ' . $intMax):'');
		$sql = sprintf("SELECT * FROM %s WHERE (idApp IN (%s) OR idAppReciver IN (%s) OR idAppThirdParty IN (%s)) AND approved = 1  ORDER BY creDate DESC %s;", self::TABLE_NAME, $strIdUsers, $strIdUsers, $strIdUsers, $strLimit, $strAppType);		
		$result = LoadDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$tObj = new App();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		return $coll;
	}*/
	

}
?>