<?php
include "db/db.php";
$q = strtolower($_GET["term"]);
$return_arr = array();
if (!$q) return;
$db->query($con,"SELECT * FROM stock_details ");
  while ($line = $db->fetchNextObject()) {
  
  	if (strpos(strtolower($line->stock_name), $q) !== false) {
		$return_arr[] =  $line->stock_name;
		//echo "$line->stock_name\n";
	
 }
 }
 echo json_encode($return_arr);
?>