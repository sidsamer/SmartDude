<?php
//this script handle the user/android http requests.
//include_once 'includes/connection.php';

$str=htmlspecialchars($_GET["order"]);
//turn on boiler
     if($str == "on" /*and boiler off*/){ 
		 $sql = "INSERT INTO tasks(task) VALUES ('on');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler turn on");
	 }
//turn off boiler	 
	 else if($str == "off" /*and boiler on*/){
		  $sql = "INSERT INTO tasks(task) VALUES ('off');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler turn off");
	 }
 //create new user    
     else if($str == "newUser"){
         $name=htmlspecialchars($_GET["name"]);
         $temp=htmlspecialchars($_GET["temp"]);
         $pass=htmlspecialchars($_GET["password"]);
         $phone=htmlspecialchars($_GET["phone"]);
         $sql = "select * from users where name='idan';";
	     $result=mysqli_query($conn,$sql);
	     $resultCheck=mysqli_num_rows($result); 
         if($resultCheck==0)
       {
           $sql = "INSERT INTO users(name,temp,password,phone) 
           VALUES ('$name','$temp','$pass','$phone');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("user create sucssesfuly");
       }
       else
           echo ("user allready exist");
     }
 //enter schduled turn on
     else if($str == "newSchdule"){
         $userId=htmlspecialchars($_GET["userId"]);
         $day=htmlspecialchars($_GET["day"]); //day in string exe: 'sunday'
         $showerTime=htmlspecialchars($_GET["showerTime"]);//time in string exe: '6:30 pm'
         //duration will be taken from script,will be in seconds
         $duration=htmlspecialchars($_GET["duration"]); //temp time
         $showerTime= date('H:i:s', strtotime($showerTime));
         $turnOnTime=htmlspecialchars($_GET["turnOnTime"]);
         //$turnOnTime=date('H:i:s',strtotime("-$duration seconds",$showerTime)); //turn on time calculation
         $turnOnTime= date('H:i:s', strtotime($turnOnTime)); //temp time
         $regular=htmlspecialchars($_GET["regular"]); //1 or 0
         
         $sql = "INSERT INTO users(userId,day,turnOnTime,duration,showrTime,regular) 
         VALUES ('$userId','$day','$turnOnTime','$duration','$showrTime','$regular');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("new Schdule was set!");
     }
// get all the schdule 
else if($str == "getAllSchdules"){
         $sql = "select * from turnon;";
	     $result=mysqli_query($conn,$sql);
	     $resultCheck=mysqli_num_rows($result); 
         if($resultCheck>0)
       {
           while($row=mysqli_fetch_assoc($result))
           {
               echo $row['id']; //yet to be done,need to send asociative array.
           }
       }
}
// login into user
else if($str == "login"){
        $name=htmlspecialchars($_GET["name"]);
        $pass=htmlspecialchars($_GET["password"]);
        $sql ="select id from users where name='$name' and password='$pass'";
         $result=mysqli_query($conn,$sql);
	     $resultCheck=mysqli_num_rows($result); 
          if($resultCheck>0)
       {
           $row=mysqli_fetch_assoc($result);
           echo $row['id']; //yet to be tested,returns id.
       }
}
?>