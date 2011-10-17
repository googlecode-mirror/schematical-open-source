<?php
class exportToCsvBase extends FBAppForm{	

	public function Init(){
		$strCsv = FBContestApplication::ExportToCSV();
		
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"my-data.csv\"");
		
		die($strCsv);
		
		
	}
}