<?php
//this script handle the microconroller http requests.
include_once 'includes/connection.php';

$str=htmlspecialchars($_GET["temp"]);
         echo($str."was sent!");
?>