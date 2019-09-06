<?php
//this script handle the user/android http requests.
include_once 'includes/connection.php';
require_once "includes/Training.php";
include_once 'boilerStatus.txt';

$str=htmlspecialchars($_GET["order"]);
//turn on boiler
     if($str == "on" /*and boiler off*/){ 
		 $sql = "INSERT INTO tasks(task) VALUES ('on');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler turn on");
        $status="on";
        $myfile = fopen("boilerStatus.txt", "w") or die("Unable to open status file!"); //saving boiler status
        fwrite($myfile,$status);
        fclose($myfile);
	 }
//turn off boiler	 
	 else if($str == "off" /*and boiler on*/){
		  $sql = "INSERT INTO tasks(task) VALUES ('off');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler turn off");
        $status="off";
        $myfile = fopen("boilerStatus.txt", "w") or die("Unable to open status file!"); //saving boiler status
        fwrite($myfile,$status);
        fclose($myfile);
	 }
 //create new user    
     else if($str == "newUser"){
         $name=htmlspecialchars($_GET["name"]);
         $temp=htmlspecialchars($_GET["temp"]);
         $pass=htmlspecialchars($_GET["password"]);
         $phone=htmlspecialchars($_GET["phone"]);
         $sql = "select * from users where name='$name';";
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
         //$reg=new LinearRegression(30,50);
         $userId=htmlspecialchars($_GET["userId"]);
         $day=htmlspecialchars($_GET["day"]); //day in string exe: 'sunday'
         $showerTime=htmlspecialchars($_GET["showerTime"]);//time in string exe: '6:30 pm'
         $duration=7200; // two hours max.
         $showerTime=date('H:i:s', strtotime($showerTime));
         $turnOnTime=date('H:i:s',strtotime("".$showerTime." -$duration seconds")); //turn on time calculation
         $regular=htmlspecialchars($_GET["regular"]); //1 or 0
         $sql = "INSERT INTO turnon(userId,day,turnOnTime,duration,showerTime,regular) VALUES ($userId,'$day','$turnOnTime',$duration,'$showerTime',$regular);";
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
               echo $row['userId']." ".$row['showerTime']." "; //yet to be done,need to send asociative array.
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
else if($str == "status"){
$myfile = fopen("boilerStatus.txt", "r") or die("Unable to open status file!");
$status=fgets($myfile);
fclose($myfile);
echo "<br> boiler status: ".$status."<br>";
        
}
else if($str == "recover"){
        $name=htmlspecialchars($_GET["name"]);
        $phone=htmlspecialchars($_GET["phone"]);
        $sql ="select password from users where name='$name' and phone='$phone'";
         $result=mysqli_query($conn,$sql);
	     $resultCheck=mysqli_num_rows($result); 
          if($resultCheck>0)
       {
           $row=mysqli_fetch_assoc($result);
           echo $row['password'];
       }
       else
           echo "cant find user or phone";
}
else if($str == "delete"){
        $id=htmlspecialchars($_GET["id"]);
        $sql="delete from users where id=$id";
        $result=mysqli_query($conn,$sql);
                   	if(!$result)
			         die("delete query faild");
                   else
                     echo ("user is deleted!");
}
?>