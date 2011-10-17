<?php
	
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"my-data.csv\"");
$strReturn = FBContestApplication::ExportToCSV();
die($strReturn);