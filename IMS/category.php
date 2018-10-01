<?php
include "db/db.php";
$q = strtolower($_GET["term"]);
$return_arr = array();
if (!$q) return;
$db->query($con,"SELECT * FROM category_details");
  while ($line = $db->fetchNextObject()) {
  	if (strpos(strtolower($line->category_name), $q) !== false) {
		//echo "$line->category_name\n";
		$return_arr[] =  $line->category_name;
	
 }
 }
 echo json_encode($return_arr);
?>