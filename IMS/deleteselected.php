<?php
session_start(); // Use session variable on this page. This function must put on the top of page.
if(!isset($_SESSION['username']) || $_SESSION['usertype'] !='admin'){ // if session variable "username" does not exist.
header("location:sales_graph.php?msg=Please%20login%20to%20access%20admin%20area%20!"); // Re-direct to sales_graph.php
}
else
{
	include_once "db/db.php";
	error_reporting (E_ALL ^ E_NOTICE);

	$tablename=$_POST['table'];
	$return=$_POST['return'];
	$i=0;
	foreach($_POST['checklist'] as $singleVar)
									{
			
 $SQL = "SELECT * FROM $tablename where id=$singleVar";
$result=mysqli_query($con,$SQL) or die(mysqli_error($con));
  $checkuser=mysqli_num_rows($result);
 		if($checkuser>0) {
								
												if($tablename=="stock_entries")
					{			
					
								$id=$singleVar;
								$difference=$db->queryUniqueValue($con,"SELECT quantity FROM stock_entries WHERE id=$id");
								$name=$db->queryUniqueValue($con,"SELECT stock_name FROM stock_entries WHERE id=$id");
								$result=$db->query($con,"SELECT * FROM stock_entries where id > $id");
								while ($line2 = $db->fetchNextObject($result)) {
								$osd=$line2->opening_stock - $difference;
								$csd=$line2->closing_stock - $difference;
								$cid=$line2->id;
								$db->execute($con,"UPDATE stock_entries SET opening_stock=".$osd.",closing_stock=".$csd." WHERE id=$cid");
									
								}
								$total = $db->queryUniqueValue($con,"SELECT quantity FROM stock_avail WHERE name='$name'");
								$total = $total - $difference;
								$db->execute($con,"UPDATE stock_avail SET quantity=$total WHERE name='$name'");
					}
					if($tablename=="stock_sales")
				
					{			$id=$singleVar;
								$difference=$db->queryUniqueValue($con,"SELECT quantity FROM stock_sales WHERE id=$id");	
								$sid=$db->queryUniqueValue($con,"SELECT transactionid FROM stock_sales WHERE id=$id");
								$id=$db->queryUniqueValue($con,"SELECT id FROM stock_entries WHERE salesid='$sid'");
								$name=$db->queryUniqueValue($con,"SELECT stock_name FROM stock_entries WHERE id=$id");
								$result=$db->query($con,"SELECT * FROM stock_entries where id > $id");
								while ($line2 = $db->fetchNextObject($result)) {
								$osd=$line2->opening_stock + $difference;
								$csd=$line2->closing_stock + $difference;
								$cid=$line2->id;
								$db->execute($con,"UPDATE stock_entries SET opening_stock=".$osd.",closing_stock=".$csd." WHERE id=$cid");
									
								}
								echo "sale $name";
								$total = $db->queryUniqueValue($con,"SELECT quantity FROM stock_avail WHERE name='$name'");
								$total = $total + $difference;
								$db->execute($con,"UPDATE stock_avail SET quantity=$total WHERE name='$name'");
					}
									
									
								mysqli_query($con,"DELETE FROM $tablename WHERE id=$singleVar") or die(mysqli_error($con)); 
							
								$i++;		
					}
						
							}
header("location:$return?cmsg=$i Records Deleted Successfully!");							
							
	}
	
	
							
							?>