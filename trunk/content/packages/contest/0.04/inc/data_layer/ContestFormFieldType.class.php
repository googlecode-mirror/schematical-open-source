<?php
class ContestFormFieldType extends BaseEntity {
    const TABLE_NAME = 'ContestFormFieldType';
    const P_KEY = 'idContestFormFieldType';
    public function __construct(){
        $this->table =  self::TABLE_NAME;
		$this->pKey = self::P_KEY;
    }
  
	public static function LoadById($intId){
		$sql = sprintf("SELECT * FROM %s WHERE idContestFormFieldType = %s;", self::TABLE_NAME, $intId);
		$result = LoadDriver::query($sql);
		while($data = mysql_fetch_assoc($result)){
			$tObj = new 		ContestFormFieldType();
			$tObj->materilize($data);
			return $tObj;
		}
	}
	
	
	public static function LoadByFBUID($intFBUID){
		$sql = sprintf("SELECT * FROM %s WHERE fbuid = %s AND delDate = null;",self::TABLE_NAME, $intFBUID);
		$result = LoadDriver::query($sql);
		while($data = mysql_fetch_assoc($result)){
			$tObj = new 		ContestFormFieldType();
			$tObj->materilize($data);
			return $tObj;
		}
	}
	public static function LoadAll(){
		$sql = sprintf("SELECT * FROM %s;", self::TABLE_NAME);
		$result = LoadDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$tObj = new 		ContestFormFieldType();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		return $coll;
	}
	public static function LoadByFbuid($strFbuid){
		
		$sql = sprintf("SELECT * FROM %s WHERE fbuid = %s %s;", self::TABLE_NAME, $strFbuid, $str);
		$result = LoadDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$tObj = new 		ContestFormFieldType();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		return $coll;

	}
/*
	public static function LoadByIdUserArr($arrIdUsers, $intMax = null, $int		ContestFormFieldTypeTpcd = null){
		if(!is_null($int		ContestFormFieldTypeTpcd)){
			$str		ContestFormFieldTypeType = sprintf(" AND id		ContestFormFieldTypeType = %s", $int		ContestFormFieldTypeTpcd);
		}else{
			$str		ContestFormFieldTypeType = "";
		} 
		$strIdUsers = implode(',', $arrIdUsers);
		$strLimit = ((!is_null($intMax))?('LIMIT ' . $intMax):'');
		$sql = sprintf("SELECT * FROM %s WHERE (id		ContestFormFieldType IN (%s) OR id		ContestFormFieldTypeReciver IN (%s) OR id		ContestFormFieldTypeThirdParty IN (%s)) AND approved = 1  ORDER BY creDate DESC %s;", self::TABLE_NAME, $strIdUsers, $strIdUsers, $strIdUsers, $strLimit, $str		ContestFormFieldTypeType);		
		$result = LoadDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$tObj = new 		ContestFormFieldType();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		return $coll;
	}*/
	

}
?>