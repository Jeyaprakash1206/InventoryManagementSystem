<?php

session_start();
include_once "db/db.php";
$tbl_name="stock_user";
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword'];
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = mysqli_real_escape_string($con,$myusername);
$mypassword = mysqli_real_escape_string($con,$mypassword);
$sql="SELECT * FROM $tbl_name WHERE username='$myusername' and password='$mypassword'" ;
$result=mysqli_query($con,$sql);
$count=mysqli_num_rows($result);
if($count==1){
$row = mysqli_fetch_row($result);
$_SESSION['id']=$row[0];
$_SESSION['username']=$row[1];
$_SESSION['usertype']=$row[3];
if($row[3]=="admin")
header("location:admin.php?");
else if($row[3]=="user")
header("location:user.php");
else
echo "error in validate user";
}
else {
header("location:indexx.php?msg=Wrong%20Username%20or%20Password");
}
?>