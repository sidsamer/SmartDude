<?php
//אם הייתה הדלקה נוספת בין הזמן הדלקה לזמן כיבוית הזמן כיבוי יהיה הרחוק יותר.
//אם ישנו כיבוי עתידי שהדלקתו כבר עבר לא מכבים לפני.
include_once 'includes/connection.php';
include_once 'boilerStatus.txt';

//get boiler status from file
$myfile = fopen("boilerStatus.txt", "r") or die("Unable to open status file!");
$status=fgets($myfile);
fclose($myfile);
        
$day=strtolower(date("l"));
$nowTime=date("h:i:s");
$nowTime=date('H:i:s',strtotime("".$nowTime." +10800 seconds"));
$pastTime=date('H:i:s',strtotime("".$nowTime." -300 seconds"));
$futureTime=date('H:i:s',strtotime("".$nowTime." +300 seconds"));


$sql="SELECT * FROM turnon where day='$day' and turnOnTime>='$pastTime' and turnOnTime<='$futureTime'";
	     $result=mysqli_query($conn,$sql);
	     $resultCheck=mysqli_num_rows($result); 
         if($resultCheck>0)
       {
           if( $status=="off" )
               {
               echo "user:".$row['userId']." TurnOnTime:".$row['turnOnTime']." Showertime:".$row['showerTime']." regular:".$row['regular']."<br>"; //delete
               $temp=shell_exec('wget http://smart-dude.herokuapp.com/Android_req.php/?order=on');
               echo $temp."<br>"; //delete
               $status="on";
               }
          // else
               // {
                    // while($row=mysqli_fetch_assoc($result))
                     // {
               // echo "user:".$row['userId']." TurnOnTime:".$row['turnOnTime']." Showertime:".$row['showerTime']." regular:".$row['regular']."<br>";
               // $temp=shell_exec('wget http://smart-dude.herokuapp.com/Android_req.php/?order=on');
               // echo $temp;
               // $status="off";
               // }

       }
echo "boiler status: ".$status."<br>";
///save boiler status to file
        $myfile = fopen("boilerStatus.txt", "w") or die("Unable to open status file!");
        fwrite($myfile,$status);
        fclose($myfile);
        
        
echo "day:".$day."<br>";
echo "time now:".$nowTime."<br>";
echo "time -5 minutes: ".$pastTime."<br>";
echo "time +5 minutes: ".$futureTime."<br>";

?>