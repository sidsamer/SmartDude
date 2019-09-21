<?php
//this script handle the microconroller http requests.
// if the microconroller gets task named "temp", he sends get request with the current boiler temp.
//then this script use openweathermap api to get current outside temp and insert both temp inside db as mesurament.
//this process is critical for the learning phase.
include_once 'includes/connection.php';

	$weater_url='http://api.openweathermap.org/data/2.5/weather?id=294577&appid=4d77a0bba1a709b9b52103f6981d7ac3'; //at the moment hard coded to Karmiel
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
    
    $sql="select * from measurements order by id asc";  //get number of mesuraments.
    $result=mysqli_query($conn,$sql);
	$resultCheck=mysqli_num_rows($result); 
       if($resultCheck>720) //need only last month.
           {
              $sql="select id from measurements order by id asc limit 1"; //get the oldest mesurament id.
              $result=mysqli_query($conn,$sql);
	          $resultCheck=mysqli_num_rows($result); 
               if($resultCheck!=0)
             {
                 $row=mysqli_fetch_assoc($result);
                 $id=$row['id'];
                 $sql="delete from measurements where id='$id'";//delete the oldest mesurament.
                         $result=mysqli_query($conn,$sql);
		     if(!$result)
			    die("query faild");
             else
               echo ("oldest mesurament deleted!");
             }    
           }



?>