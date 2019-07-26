<?php
//this script handle the microconroller http requests.
include_once 'includes/connection.php';

$str=htmlspecialchars($_GET["name"]);
         echo($str."was sent!");
?>