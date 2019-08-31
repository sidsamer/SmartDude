<?php
//this script automates all the functionity of the system //
include_once 'includes/connection.php';
require_once "includes/Training.php";

$day=strtolower(date("l")); //current day.
$nowTime=date("H:i:s"); //current time.
$nowTime=date('H:i:s',strtotime("".$nowTime." +10800 seconds")); //current time in israel(server +3 hours).
$pastTime=date('H:i:s',strtotime("".$nowTime." -300 seconds")); //minus 5 minutes.
$futureTime=date('H:i:s',strtotime("".$nowTime." +10800 seconds"));//plus 3 hours.

///////////////////////check temps///////////////////////
    $sql = "INSERT INTO tasks(task) VALUES ('temp');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler check temp");
/////////////////////recalculate shower time////////////////////////
         $reg=new LinearRegression(30,50); 
$sql="SELECT * FROM turnon where day='$day' and showerTime>'$nowTime' and showerTime<='$futureTime'";
              echo $sql;
	          $result=mysqli_query($conn,$sql);
	          $resultCheck=mysqli_num_rows($result); 
              if($resultCheck>0) //all the closest showers(three hours).
              {
                while($row=mysqli_fetch_assoc($result)) //for each shower,recalculate duration and turnon time.
               {
                   $id=$row['id'];
                   $user=$row['userId']; //in order to get the user fav water temp.
                   $showerTime=$row['showerTime'];
                   $regular=$row['regular'];
                   $duration=$reg->CalcDuration(); //need to be upgrated.
                   $turnOnTime=date('H:i:s',strtotime("".$showerTime." -$duration seconds")); //turn on time calculation
                   $sql="delete from turnon where id='$id'";
                   $result=mysqli_query($conn,$sql);
                   	if(!$result)
			         die("delete query faild");
                   else
                     echo ("new Schdule was set!");
                   $sql = "INSERT INTO turnon(userId,day,turnOnTime,duration,showerTime,regular) VALUES ('$user','$day','$turnOnTime','$duration','$showerTime','$regular');";
                   $result=mysqli_query($conn,$sql);
		           if(!$result)
			         die("insert query faild");
                   else
                     echo ("new Schdule was set!");
               }
              }

////////////////////Triaining(only once a day)//////////////////////

 if($nowTime >="06:00" and $nowTime <="07:00")
 {
     echo "<br>".$nowTime."<br>";
     $trainer=new LinearRegressionTrainer(30,50);
     $trainer->Test();
 }

?>