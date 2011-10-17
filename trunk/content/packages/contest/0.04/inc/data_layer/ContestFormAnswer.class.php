<?php
class ContestFormAnswer extends BaseEntity {
    const TABLE_NAME = 'ContestFormAnswer';
    const P_KEY = 'idFormAnswer';
    public function __construct(){
        $this->table =  self::TABLE_NAME;
		$this->pKey = self::P_KEY;
    }
  
	public static function LoadById($intId){
		$sql = sprintf("SELECT * FROM %s WHERE idFormAnswer = %s;", self::TABLE_NAME, $intId);
		$result = LoadDriver::query($sql);
		while($data = mysql_fetch_assoc($result)){
			$tObj = new ContestFormAnswer();
			$tObj->materilize($data);
			return $tObj;
		}
	}
	
	
	public static function LoadByFBUID($intIdApp, $intFBUID){
		$sql = sprintf("SELECT * FROM %s WHERE fbuid = %s AND delDate = null;",self::TABLE_NAME, $intFBUID);
		$result = LoadDriver::query($sql);
		while($data = mysql_fetch_assoc($result)){
			$tObj = new ContestFormAnswer();
			$tObj->materilize($data);
			return $tObj;
		}
	}
	public static function LoadAll(){
		$sql = sprintf("SELECT * FROM %s;", self::TABLE_NAME);
		$result = LoadDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$tObj = new ContestFormAnswer();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		return $coll;
	}
	public static function LoadByIdContestEntry($intidFormAnswer){
		$sql = sprintf("SELECT * FROM %s WHERE idContestEntry = %s;", self::TABLE_NAME, $intidFormAnswer);
		$result = LoadDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$tObj = new ContestFormAnswer();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		return $coll;
	}
	
/*
	public static function LoadByIdUserArr($arrIdUsers, $intMax = null, $intContestFormAnswerTpcd = null){
		if(!is_null($intContestFormAnswerTpcd)){
			$strContestFormAnswerType = sprintf(" AND idFormAnswerType = %s", $intContestFormAnswerTpcd);
		}else{
			$strContestFormAnswerType = "";
		} 
		$strIdUsers = implode(',', $arrIdUsers);
		$strLimit = ((!is_null($intMax))?('LIMIT ' . $intMax):'');
		$sql = sprintf("SELECT * FROM %s WHERE (idFormAnswer IN (%s) OR idFormAnswerReciver IN (%s) OR idFormAnswerThirdParty IN (%s)) AND approved = 1  ORDER BY creDate DESC %s;", self::TABLE_NAME, $strIdUsers, $strIdUsers, $strIdUsers, $strLimit, $strContestFormAnswerType);		
		$result = LoadDriver::query($sql);
		$coll = new BaseEntityCollection();
		while($data = mysql_fetch_assoc($result)){
			$tObj = new ContestFormAnswer();
			$tObj->materilize($data);
			$coll->addItem($tObj);
		}
		return $coll;
	}*/
	

}
?>