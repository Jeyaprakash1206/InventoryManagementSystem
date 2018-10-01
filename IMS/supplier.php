<?php
include "db/db.php";
$q = strtolower($_GET["term"]);
$return_arr = array();
if (!$q) return;
$db->query($con,"SELECT * FROM supplier_details");
  while ($line = $db->fetchNextObject()) {
  
  	if (strpos(strtolower($line->supplier_name), $q) !== false) {
		$return_arr[] =  $line->supplier_name\;
		//echo "$line->supplier_name\n";
	
 }
 }
 echo json_encode($return_arr);
?>