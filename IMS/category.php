<?php
include "db/db.php";
$q = strtolower($_GET["q"]);
if (!$q) return;
$db->query($con,"SELECT * FROM category_details");
  while ($line = $db->fetchNextObject()) {
  
  	if (strpos(strtolower($line->category_name), $q) !== false) {
		echo "$line->category_name\n";
	
 }
 }

?>