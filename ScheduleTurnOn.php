<?php

include_once 'includes/connection.php';

$day=strtolower(date("l"));
$nowTime=date("h:i:s");
$pastTime=date('H:i:s',strtotime("".$nowTime." -300 seconds"));
$futureTime=date('H:i:s',strtotime("".$nowTime." +300 seconds"));

$sql="SELECT * FROM turnon where day='$day'";
	     $result=mysqli_query($conn,$sql);
	     $resultCheck=mysqli_num_rows($result); 
         if($resultCheck>0)
       {
           while($row=mysqli_fetch_assoc($result))
           {
               echo "user:"$row['userId']." time:".$row['showerTime']." regular:".$row['regular']."<br>";
           }
       }
       echo "day:".$day."<br>";
echo "time now:".$nowTime."<br>";
echo "time -5 minutes: ".$pastTime."<br>";
echo "time +5 minutes: ".$futureTime."<br>";

 $temp=shell_exec('wget http://smart-dude.herokuapp.com/Android_req.php/?order=on');
 echo $temp;
?>