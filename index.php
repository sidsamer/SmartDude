
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<body>
<?php
//this script is a simple user interface for the system.
include_once 'includes/connection.php';
require_once "includes/Training.php";
?>
<center>
<form action="index.php" method='post'>
<button type="submit" value="Off" name="Off">Off</button>
<button type="submit" value="On" name="On">On</button>
<button type="submit" value="temp" name="Temp">temp</button>
<button type="submit" value="newUser" name="newUser">newUser</button>
<button type="submit" value="login" name="login">login</button>
<button type="submit" value="scheduTurnOn" name="scheduTurnOn">scheduTurnOn</button>
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
}
else if(isset($_POST['On']))
{
    $sql = "INSERT INTO tasks(task) VALUES ('on');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler turn on");
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
         $name="boris";
         $temp=60;
         $pass="112113";
         $phone="0524734844";
         $url="http://smart-dude.herokuapp.com/Android_req.php/?order=newUser&name=$name&temp=$temp&password=$pass&phone=$phone";
         $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
}
else if(isset($_POST['login']))
{
         $name="boris";
         $pass="112113";
         $url="http://smart-dude.herokuapp.com/Android_req.php/?order=login&name=$name&password=$pass";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo "id:".$contents;
    else
        echo "cant make http req";
}
else if(isset($_POST['scheduTurnOn']))
{
         $url="http://smart-dude.herokuapp.com/Android_req.php/?order=newSchdule&userId=1&day=sunday&showerTime=6:30pm&regular=1";
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