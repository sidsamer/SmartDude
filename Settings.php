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
 border-radius:50%;
 color:LightGray;
 font-size:15px;
 padding: 10px 15px;
 }
</style>
<body style="background-color:blue;">
<form  action="Settings.php" method="POST">
<button type="submit" name="back">Back</button>
</form>
<CENTER>
<br><br><br>
<form  action="menu.php" method="POST">
<input type="text" name="Volume" placeholder="Volume"><br>
<input type="email" name="Email" placeholder="Email"><br>
<button type="submit" name="define">Define System</button><br><br>
<button type="submit" name="deleteUser">Delete User</button><br><br>
</form>
<?php
if(isset($_POST['define']))
{
  	$vol=$_POST['Volume'];
    $email=$_POST['Email'];
    $uid=$_SESSION['Uid'];
        $url="http://smart-dude.herokuapp.com/Android_req.php/?order=boiler_data&uid=$uid&volume=$vol&mail=$email";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
        header('Location: Settings.php'); 
}

else if(isset($_POST['deleteUser'])) 
{
    $userid=$_SESSION['Id'];
         setcookie('Id',$Id,time()-10);  
		 $sql = "delete from users where id='$userid';";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
	header('Location: index.php');
}
else if(isset($_POST['back'])) 
{
	header('Location: menu.php');
}
?>
</CENTER>
</body>
</html>