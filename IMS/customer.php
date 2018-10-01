<?php
include "db/db.php";
$q = strtolower($_GET["term"]);
$return_arr = array();
if (!$q) return;
$db->query($con,"SELECT * FROM customer_details");
  while ($line = $db->fetchNextObject()) {
  
  	if (strpos(strtolower($line->customer_name), $q) !== false) {
		//echo "$line->customer_name\n";
		$return_arr[] =  $line->customer_name;
	
 }
 }
 echo json_encode($return_arr);
?>