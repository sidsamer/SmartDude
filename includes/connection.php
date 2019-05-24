<?php

$host="eu-cdbr-west-02.cleardb.net";
$dbuser="b930876c351ee7";
$pass="0f8d4cc8";
$dbname="heroku_c26d047c909fd55";
$conn=mysqli_connect($host,$dbuser,$pass,$dbname);
if(mysqli_connect_errno())
{
	die("Connection Faild!".mysqli_connect_error());
}
?>
