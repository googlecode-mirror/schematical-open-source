<?php
class Attachment extends BaseEntity {
    const TABLE_NAME = 'Attachment';
    const P_KEY = 'idAttachment';
    public function __construct(){
        $this->table =  self::TABLE_NAME;
		$this->pKey = self::P_KEY;
    }
  
	public static function LoadById($intId){
		$sql = sprintf("SELECT * FROM %s WHERE idAttachment = %s;", self::TABLE_NAME, $intId);
		$result = LoadDriver::query($sql);
		while($data = mysql_fetch_assoc($result)){
			$tObj = new Attachment();
			$tObj->materilize($data);
			return $tObj;
		}
	}
	
	
	public static function LoadByFBUID($intIdApp, $intFBUID){
		$sql = sprintf("SELECT * FROM %s WHERE fbuid = %s AND delDate = null;",self::TABLE_NAME, $intFBUID);
		$result = LoadDriver::query($sql);
		while($data = mysql_fetch_assoc($result)){
			$tObj = new Attachment();
			$tObj->materilize($data);
			return $tObj;
		}
	}
	public static function LoadAll(){
		$sql = sprintf("SELECT * FROM %s;", self::TABLE_NAME);
		$result = LoadDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$tObj = new Attachment();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		return $coll;
	}
	
	
/*
	public static function LoadByIdUserArr($arrIdUsers, $intMax = null, $intAttachmentTpcd = null){
		if(!is_null($intAttachmentTpcd)){
			$strAttachmentType = sprintf(" AND idAttachmentType = %s", $intAttachmentTpcd);
		}else{
			$strAttachmentType = "";
		} 
		$strIdUsers = implode(',', $arrIdUsers);
		$strLimit = ((!is_null($intMax))?('LIMIT ' . $intMax):'');
		$sql = sprintf("SELECT * FROM %s WHERE (idAttachment IN (%s) OR idAttachmentReciver IN (%s) OR idAttachmentThirdParty IN (%s)) AND approved = 1  ORDER BY creDate DESC %s;", self::TABLE_NAME, $strIdUsers, $strIdUsers, $strIdUsers, $strLimit, $strAttachmentType);		
		$result = LoadDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$tObj = new Attachment();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		return $coll;
	}*/
	

}
?>