<?php
//this script automates all the functionity of the system //
include_once 'includes/connection.php';
require_once "includes/Training.php";

$day=strtolower(date("l")); //current day.
$nowTime=date("H:i:s"); //current time.
$nowTime=date('H:i:s',strtotime("".$nowTime." +10800 seconds")); //current time in israel(server +3 hours).
$nearFutureTime=date('H:i:s',strtotime("".$nowTime." +600 seconds")); //plus 10 minutes.
$futureTime=date('H:i:s',strtotime("".$nowTime." +10800 seconds"));//plus 3 hours.

///////////////////////check temps///////////////////////
    $temp=rand(20,40); //represent the temp inside the boiler.
    $res=shell_exec("wget http://smart-dude.herokuapp.com/temp.php/?temp=$temp");
    echo $res."<br>"; //delete
/////////////////////recalculate shower time////////////////////////
         $reg=new LinearRegression(30,50); 
        $currTemp=rand(20,30); //need to insert real temps;
        $myfile = fopen("boilerData.txt", "r") or die("Unable to open boiler data file!");
        $uid2=fgets($myfile); //dont need.
        $mail=fgets($myfile); //dont need.
        $volume=fgets($myfile); //size of boiler.
        fclose($myfile);
$sql="SELECT * FROM turnon where day='$day' and showerTime<='$futureTime' and turnOnTime>'$nearFutureTime'";
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
                   $sql="select * from users where id='$user'"; //to check user fav temps.
	               $result=mysqli_query($conn,$sql);
                   $resultCheck=mysqli_num_rows($result);
                   if($resultCheck>0)
              {
                  $row=mysqli_fetch_assoc($result);
                  $favTemp=$row['temp'];
              }
              else
              {
                   $favTemp=40; //avg temp humans like to take a shower. 
              }
                   $duration=$reg->CalcDuration($currTemp,$favTemp,$volume); //need to be upgrated.
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