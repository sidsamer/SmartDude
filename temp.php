<?php
//this script handle the microconroller http requests.
include_once 'includes/connection.php';

	$weater_url='http://api.openweathermap.org/data/2.5/weather?id=294577&appid=4d77a0bba1a709b9b52103f6981d7ac3'; //need to change to be by city(id)
	$weaterinfoJson = file_get_contents($weater_url);
    $tempviaday_array = json_decode($weaterinfoJson,true);
		
			$tempOut=$tempviaday_array['main']['temp'];
            $tempOut-=273.15;        
            $BoilerTemp=htmlspecialchars($_GET["temp"]);
            $hour=date('Y-m-d H:i:s');

               $hour = date("Y-m-d H:i:s",strtotime("+3 hours",strtotime($hour)));

               
    $sql = "INSERT INTO measurements(hour,boilerTemp,outsideTemp)
    VALUES ('$hour','$BoilerTemp','$tempOut');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo("out:".$tempOut." boiler:".$BoilerTemp." inserted");




?>