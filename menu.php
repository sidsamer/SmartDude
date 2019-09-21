<?php
//this is the menu page,from here you turn on/off system go to settings,about,Schedule
include_once 'includes/connection.php';
session_start();
?>
<!DOCTYPE html>
<html>
<script src="javascript.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
color:white;
}
 button{
 background-color:transparent;
  border-radius:50%;
 color:white;
 font-size:15px;
 padding: 10px 15px;
 }
</style>
<body style="background-color:blue;">
<form  action="menu.php" method="POST">
<button type="submit" name="exit">Exit</button>
</form>
<CENTER>
<br><br><br><br>
<form  action="menu.php" method="POST">
<?php
    $myfile = fopen("boilerStatus.txt", "r") or die("Unable to open status file!");
    $status=fgets($myfile);
    fclose($myfile);
        if($status=="off")
           echo '<button style="background-color:red;" type="submit" value="on" name="Status">off</button><br><br>'; //to turn on
       else
           echo '<button style="background-color:green;" type="submit" value="off" name="Status">on</button><br><br>'; //to turn off
     
?>
<button type="submit" name="Schedule">Schedule</button><br><br>
<button type="submit" name="Settings">Settings</button><br><br>
<button type="submit" name="About">About</button><br><br>
</form>
<?php
//go to Schedule
if(isset($_POST['Schedule']))
{
  header('Location: UserSchedule.php'); 
}
//go to settings
else if(isset($_POST['Settings']))
{
   header('Location: Settings.php');
}
//show about
else if(isset($_POST['About']))
{
	echo "this is the about secttion";
}
//exit the app back to login
else if(isset($_POST['exit'])) 
{
    setcookie('Id',$Id,time()-10);
	header('Location: index.php');
}
//show system status
else if(isset($_POST['Status'])) 
{
    echo "status:".$_POST['Status']."<br>";
   if($_POST['Status']=="off")
         $sql = "INSERT INTO tasks(task) VALUES ('off');";
     else
         $sql = "INSERT INTO tasks(task) VALUES ('on');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
        {
        $myfile = fopen("boilerStatus.txt", "w") or die("Unable to open status file!");
        fwrite($myfile,$_POST['Status']);
        fclose($myfile);
        }
   header('Location: menu.php');
}
?>
</CENTER>
</body>
</html>