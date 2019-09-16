
<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<body>
<?php
include_once 'includes/connection.php';
?>
<center>
<form action="index.php" method='post'>
<button type="submit" value="Off" name="Off">Off</button>
<button type="submit" value="On" name="On">On</button>
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
            	header('Location: http.php');
}
if(isset($_POST['On']))
{
    $sql = "INSERT INTO tasks(task) VALUES ('on');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
            echo ("boiler turn on");
        header('Location: http.php');
}

?>
</center>
</body>
</html>