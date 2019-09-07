
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<body>
<?php
//this script is a simple user interface for the system.
include_once 'includes/connection.php';
require_once "includes/Training.php";
//include_once 'boilerStatus.txt';
?>
<center>
<form action="index.php" method='post'>
<button type="submit" value="Off" name="Off">Off</button>
<button type="submit" value="On" name="On">On</button>
<button type="submit" value="Status" name="Status">status</button>
<button type="submit" value="temp" name="Temp">temp</button>
<button type="submit" value="ShowSchedule" name="ShowSchedule">Show Schedule</button>
<button type="submit" value="train" name="train">train</button><br><br>
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
</form><br><br>
<a href="SignUp.php" style="color:white;">press to sign up</a><br>

<?php
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
else if(isset($_POST['Temp']))
{
    $sql = "INSERT INTO tasks(task) VALUES ('temp');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler check temp");
}
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
else if(isset($_POST['regression']))
{
    $reg=new LinearRegression(intval($_POST['TempIn']),intval($_POST['TempOut']));
    echo "out:".$reg->getOut().", boiler:".$reg->getBoiler().", text:".$reg->PredictTemp();
}
else if(isset($_POST['train']))
{
    $trainer=new LinearRegressionTrainer(30,50);
    echo "weight before training:".$trainer->getW1();
    $trainer->Test();
    echo "weight after training:".$trainer->getW1();
}
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
else if(isset($_POST['ShowSchedule'])) 
{
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=getAllSchdules";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
else if(isset($_POST['Status'])) 
{
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=status";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
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
else if(isset($_POST['boiler_data'])) 
{
    $uid=$_POST['UID'];
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=get_boiler_data&uid=$uid";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
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
?>
</center>
</body>
</html>