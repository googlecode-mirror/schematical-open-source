<?php
abstract class MFBDateTime{

	public static function Now(){
		return date("Y-m-d H:i:s");
	}
	public static function Q(QDateTime $objQDateTime){	
		if ($objQDateTime->IsTimeNull()){
			//error_log("NotNUll: " . sprintf("'%s'", $objQDateTime->__toString('YYYY-MM-DD')));
			return $objQDateTime->__toString('YYYY-MM-DD');
		}else{
			//error_log("IS NULL: " . sprintf("'%s'", $objQDateTime->__toString(QDateTime::FormatIso)));
			return  $objQDateTime->__toString(QDateTime::FormatIso);
		}
	
	}
}