<?php
include "db/db.php";
$q = strtolower($_GET["q"]);
if (!$q) return;
$db->query($con,"SELECT * FROM stock_details ");
  while ($line = $db->fetchNextObject()) {
  
  	if (strpos(strtolower($line->stock_name), $q) !== false) {
		echo "$line->stock_name\n";
	
 }
 }

?>