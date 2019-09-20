<?php
include_once 'includes/connection.php';
session_start();
?>
<!DOCTYPE html>
<html>
<script src="javascript.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
 button{
 background-color:transparent;
 color:LightGray;
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
<button type="submit" name="Schedule">Schedule</button><br><br>
<button type="submit" name="Settings">Settings</button><br><br>
<button type="submit" name="About">About</button><br><br>
</form>
<?php
if(isset($_POST['Schedule']))
{
  header('Location: UserSchedule.php'); 
}

else if(isset($_POST['Settings']))
{
   header('Location: Settings.php');
}
else if(isset($_POST['About']))
{
	echo "this is the about secttion";
}
else if(isset($_POST['exit'])) 
{
    setcookie('Id',$Id,time()-10);
	header('Location: index.php');
}
?>
</CENTER>
</body>
</html>