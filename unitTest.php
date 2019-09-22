<?php
// this module is the the unit-test of the system.
// here,we can check all the functionality of the system however we want
// in order to make sure everyting work the way we want it to work and we can manage the system.
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<body>
<?php
include_once 'includes/connection.php';
require_once "includes/Training.php";
?>
<form  action="unitTest.php" method="POST">
<button type="submit" name="exit">Exit</button>
</form>
<center>
<form action="unitTest.php" method='post'>
<button type="submit" value="Off" name="Off">Off</button>
<button type="submit" value="On" name="On">On</button>
<button type="submit" value="Status" name="Status">status</button>
<button type="submit" value="temp" name="Temp">temp</button>
<button type="submit" value="train" name="train">train</button><br><br>
<button type="submit" value="ShowSchedule" name="ShowSchedule">All Schedule</button>
<button type="submit" value="Mesuraments" name="Mesuraments">All Mesuraments</button>
<button type="submit" value="Users" name="Users">All Users</button>
<button type="submit" value="Tasks" name="Tasks">All Tasks</button><br><br>
<button type="submit" value="DelTasks" name="DelTasks">Delete All Tasks</button><br><br>
<input type="text" placeholder="Name" name="Name"><br>
<input type="text" placeholder="Favorite Temp" name="FavTemp"><br>
<input type="text" placeholder="Pass" name="Pass"><br>
<input type="text" placeholder="Phone" name="Phone"><br>
<input type="text" placeholder="UID" name="Uid"><br>
<button type="submit" value="newUser" name="newUser">newUser</button><br><br>
<input type="text" placeholder="Name" name="UserName"><br>
<input type="text" placeholder="Pass" name="UserPass"><br>
<button type="submit" value="login" name="login">login</button><br><br>
<input type="text" placeholder="boiler Temp" name="TempIn"><br>
<input type="text" placeholder="Outside Temp" name="TempOut"><br>
<button type="submit" value="regression" name="regression">regression</button><br><br>
<input type="text" placeholder="User Id" name="userId"><br>
<input type="text" placeholder="Day" name="day"><br>
<input type="text" placeholder="Time" name="time"><br>
<input type="text" placeholder="Regular or not" name="regular"><br>
<button type="submit" value="ScheduleTurnOn" name="ScheduleTurnOn">ScheduleTurnOn</button><br><br>
<input type="text" placeholder="Name" name="RecoverUserName"><br>
<input type="text" placeholder="Phone" name="RecoverUserPhone"><br>
<input type="text" placeholder="UID" name="RecoverUid"><br>
<button type="submit" value="recover" name="recover">Recover pass</button><br><br>
<input type="text" placeholder="ID" name="ID"><br>
<button type="submit" value="delete" name="delete">Delete User</button><br><br>
<input type="text" placeholder="ID" name="Schedule_ID"><br>
<button type="submit" value="deleteSchedule" name="deleteSchedule">Delete Schedule</button><br><br>
<input type="text" placeholder="UID" name="UID"><br>
<button type="submit" value="boiler_data" name="boiler_data">Boiler Data</button><br><br>
<input type="text" placeholder="UID" name="UIDusers"><br>
<button type="submit" value="NumOfUsers" name="NumOfUsers">Number of users</button><br><br>
<input type="text" placeholder="Day" name="Day_sch"><br>
<button type="submit" value="Day_Schedule" name="Day_Schedule">Day Schedule</button><br><br>
<input type="text" placeholder="ID" name="User_sch_id"><br>
<button type="submit" value="User_schedule" name="User_schedule">User Schedule</button><br><br>
</form><br><br>
<a href="SignUp.php" style="color:white;">press to sign up</a><br>

<?php
//turn off, using the connection module
if(isset($_POST['Off']))
{
    $sql = "INSERT INTO tasks(task) VALUES ('off');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler turn off");
        $status="off";
        $myfile = fopen("boilerStatus.txt", "w") or die("Unable to open status file!");
        fwrite($myfile,$status);
        fclose($myfile);
}
//turn on, using the connection module
else if(isset($_POST['On']))
{
    $sql = "INSERT INTO tasks(task) VALUES ('on');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler turn on");
        $status="on";
        $myfile = fopen("boilerStatus.txt", "w") or die("Unable to open status file!");
        fwrite($myfile,$status);
        fclose($myfile);
}
//check temp, using the connection module
else if(isset($_POST['Temp']))
{
    $sql = "INSERT INTO tasks(task) VALUES ('temp');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler check temp");
}
//inserting new user, using Android_req module
else if(isset($_POST['newUser']))
{
         $name=$_POST['Name'];
         $temp=$_POST['FavTemp'];
         $pass=$_POST['Pass'];
         $phone=$_POST['Phone'];
         $uid=$_POST['Uid'];
         $url="http://smart-dude.herokuapp.com/Android_req.php/?order=newUser&name=$name&temp=$temp&password=$pass&phone=$phone&uid=$uid";
         $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
//login,checks login order inside Android_req module
else if(isset($_POST['login']))
{
         $name=$_POST['UserName'];
         $pass=$_POST['UserPass'];
         $url="http://smart-dude.herokuapp.com/Android_req.php/?order=login&name=$name&password=$pass";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo "id:".$contents;
    else
        echo "cant make http req";
}
//check our prediction abilities
else if(isset($_POST['regression']))
{
    $reg=new LinearRegression(intval($_POST['TempIn']),intval($_POST['TempOut']));
    echo "out:".$reg->getOut().", boiler:".$reg->getBoiler().", text:".$reg->PredictTemp();
}
//check the train function to see if it works
else if(isset($_POST['train']))
{
    $trainer=new LinearRegressionTrainer(30,50);
    echo "weight before training:".$trainer->getW1();
    $trainer->Test();
    echo "weight after training:".$trainer->getW1();
}
//insert a new Schedule turn on the way the users do in the app,checks newSchdule order inside Android_req module
else if(isset($_POST['ScheduleTurnOn'])) 
{
    $userid=$_POST['userId'];
    $day=$_POST['day'];
    $time=$_POST['time'];
    $regular=$_POST['regular'];
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=newSchdule&userId=$userid&day=$day&showerTime=$time&regular=$regular";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
//checks getlAllSchdules order inside Android_req module 
else if(isset($_POST['ShowSchedule'])) 
{
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=getAllSchdules";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
//check if the status of the boiler is right
else if(isset($_POST['Status'])) 
{
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=status";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
//checks recover order inside Android_req module 
else if(isset($_POST['recover'])) 
{
         $name=$_POST['RecoverUserName'];
         $phone=$_POST['RecoverUserPhone'];
         $uid=$_POST['RecoverUid'];
         $url="http://smart-dude.herokuapp.com/Android_req.php/?order=recover&name=$name&phone=$phone&uid=$uid";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo "pass:".$contents;
    else
        echo "cant make http req";
}
//checks deleteUser order inside Android_req module 
else if(isset($_POST['delete'])) 
{
    $id=$_POST['ID'];
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=delete&id=$id";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
//checks get_boiler order inside Android_req module 
else if(isset($_POST['boiler_data'])) 
{
    $uid=$_POST['UID'];
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=get_boiler_data&uid=$uid";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
    
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=num_users&id=$uid";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo "users:".$contents;
    else
        echo "cant make http req, cant get number of users";
}
//checks delete_schdule order inside Android_req module 
else if(isset($_POST['deleteSchedule'])) 
{
    $id=$_POST['Schedule_ID'];
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=delete_schdule&id=$id";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
//checks num_users order inside Android_req module 
else if(isset($_POST['NumOfUsers'])) 
{
    $id=$_POST['UIDusers'];
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=num_users&id=$id";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
//checks day_schdule order inside Android_req module 
else if(isset($_POST['Day_Schedule'])) 
{
    $day=$_POST['Day_sch'];
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=day_Schedule&day=$day";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
//gets all the mesuraments inside the Database
else if(isset($_POST['Mesuraments'])) 
{
    $sql='SELECT * FROM measurements;';
    $result=mysqli_query($conn,$sql);
    $resultCheck=mysqli_num_rows($result);
    if($resultCheck>0)
     {
         $i=0;
         while($i<$resultCheck)
         {
             	    $row=mysqli_fetch_assoc($result);
             echo "<br> boiler:".$row['boilerTemp']." outside:".$row['outsideTemp'];
             $i++;
         }
	 }
     else
     {
         echo("resultCheck:".$resultCheck);
     }
}
//gets all the tasks inside the Database
else if(isset($_POST['Tasks'])) 
{
    $sql='SELECT * FROM tasks;';
    $result=mysqli_query($conn,$sql);
    $resultCheck=mysqli_num_rows($result);
    if($resultCheck>0)
     {
         $i=0;
         while($i<$resultCheck)
         {
             	    $row=mysqli_fetch_assoc($result);
             echo "<br> id:".$row['id']." task:".$row['task'];
             $i++;
         }
	 }
     else
     {
         echo("resultCheck:".$resultCheck);
     }
}
//gets all the users inside the Database
else if(isset($_POST['Users'])) 
{
    $sql='SELECT * FROM users;';
    $result=mysqli_query($conn,$sql);
    $resultCheck=mysqli_num_rows($result);
    if($resultCheck>0)
     {
         $i=0;
         while($i<$resultCheck)
         {
             	    $row=mysqli_fetch_assoc($result);
             echo "<br> id:".$row['id']." name:".$row['name']." temp:".$row['temp']." phone:".$row['phone'];
             $i++;
         }
	 }
     else
     {
         echo("resultCheck:".$resultCheck);
     }
}
//delete all tasks from the Database
else if(isset($_POST['DelTasks'])) 
{
        $sql="delete from tasks where id>0";
        $result=mysqli_query($conn,$sql);
                   	if(!$result)
			         die("delete query faild");
                   else
                     echo ("all tasks are deleted!");  
}
//get all Schedule of a spesific user
else if(isset($_POST['User_schedule'])) 
{
        $id=$_POST['User_sch_id'];
        $sql="select userId,id,showerTime,day from turnon where userId='$id';";
        $result=mysqli_query($conn,$sql);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck>0)
       {
	     while($row=mysqli_fetch_assoc($result))
	   {
		  echo "id:"$row["id"].' day:'.$row["day"].' turn on time:'.$row["turnOnTime"].' shower time:'.$row["showerTime"]."<br>";
	   }
	   }
       else
           echo "this user dont have turn on's at the moment!"
}
//exit unit-test into the app login page
else if(isset($_POST['exit'])) 
{
    setcookie('Id',$Id,time()-10);
	header('Location: index.php');
}
?>
</center>
</body>
</html>