<?php

$host="localhost";
$dbuser="root";
$pass="";
$dbname="SmartDude";
$conn=mysqli_connect($host,$dbuser,$pass,$dbname);
if(mysqli_connect_errno())
{
	die("Connection Faild!".mysqli_connect_error());
}
?>
