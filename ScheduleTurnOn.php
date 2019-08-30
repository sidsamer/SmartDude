<?php

//אם ישנו כיבוי עתידי שהדלקתו כבר עבר לא מכבים לפני.
//זמן מקלחת שכבר עבר ולא רגולר.
include_once 'includes/connection.php';
include_once 'boilerStatus.txt';

//get boiler status from file
$myfile = fopen("boilerStatus.txt", "r") or die("Unable to open status file!");
$status=fgets($myfile);
fclose($myfile);
  
//since heroku scheduler limit you to run the sceduler only for 10 minutes margins, we will check 5 minutes before and after to give us 5 minutes error top!  
$day=strtolower(date("l"));
$nowTime=date("H:i:s");
$nowTime=date('H:i:s',strtotime("".$nowTime." +10800 seconds")); //current time in israel(server +3 hours).
$pastTime=date('H:i:s',strtotime("".$nowTime." -300 seconds")); //minus 5 minutes.
$futureTime=date('H:i:s',strtotime("".$nowTime." +300 seconds"));//plus 5 minutes.



           if( $status=="off" ) //if boiler is off, were looking for a turn on in the (5 minutes radious(+-)).
               {
              $sql="SELECT * FROM turnon where day='$day' and turnOnTime>='$pastTime' and turnOnTime<='$futureTime'";
              echo $sql;
	          $result=mysqli_query($conn,$sql);
	          $resultCheck=mysqli_num_rows($result); 
              if($resultCheck>0)
              {
               echo "user:".$row['userId']." TurnOnTime:".$row['turnOnTime']." Showertime:".$row['showerTime']." regular:".$row['regular']."<br>"; //delete
               $temp=shell_exec('wget http://smart-dude.herokuapp.com/Android_req.php/?order=on');
               echo $temp."<br>"; //delete
              }
               }
          else   //status on.
               {
              $sql="SELECT * FROM turnon where day='$day' and showerTime>='$pastTime' and showerTime<='$futureTime' order by showerTime asc limit 1"; //check what is the closest turn off.
	          $result=mysqli_query($conn,$sql);
	          $resultCheck=mysqli_num_rows($result); 
              if($resultCheck>0) //if there is a turn off,we need to check if there is another turn on, if not, we can turn off the boiler.
              {
                  $row=mysqli_fetch_assoc($result);
              $showerTime=$row['showerTime']; //from this shower time
              $sql="SELECT * FROM turnon where day='$day' and showerTime>'$showerTime' and turnOnTime<='$showerTime'";
	          $result=mysqli_query($conn,$sql);
	          $resultCheck=mysqli_num_rows($result);           
              if($resultCheck==0) //if we find nothing, we can turn it off.
              {          
                $temp=shell_exec('wget http://smart-dude.herokuapp.com/Android_req.php/?order=off');
                echo $temp;             
              }
              }

       }
       
       
              $sql="SELECT * FROM turnon where day='$day' and showerTime<'$nowTime'"; //find all the old schedules, the ones whose not regular will be deleted.
	          $result=mysqli_query($conn,$sql);
	          $resultCheck=mysqli_num_rows($result); 
                if($resultCheck>0)
              {
              while($row=mysqli_fetch_assoc($result))
               {
                   if($row['regular']==0)
                   {
                       $id=$row['id'];
                      $sql="delete from turnon where id=$id";
                      $result=mysqli_query($conn,$sql);
		              if(!$result)
			             die("query faild");
                      else
                         echo ("old Schedule deleted!<br>");
                   }
               }
              }
               
                       
echo "boiler status: ".$status."<br>";

?>