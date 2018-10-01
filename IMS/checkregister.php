<?php

session_start();
include_once "db/db.php";
$tbl_name="stock_user";
$myusername=$_POST['myusername']; 
$mypassword=$_POST['mypassword'];
$myConfirmpassword=$_POST['myConfirmpassword'];

$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myConfirmpassword = stripslashes($myConfirmpassword);

$myusername = mysqli_real_escape_string($con,$myusername);
$mypassword = mysqli_real_escape_string($con,$mypassword);
$myConfirmpassword = mysqli_real_escape_string($con,$myConfirmpassword);

if($mypassword == $myConfirmpassword) {
    $user_type = 'admin';
    $sql="INSERT INTO $tbl_name (username, password, user_type) VALUES ('$myusername', '$mypassword', '$user_type')" ;
    $res = $db->query($con,$sql );
    if($res){
        header("location:admin.php");
    }else{
        header("location:register.php");
    }
} else {
    header("location:register.php?msg=Password%20Mismatch");
}
?>