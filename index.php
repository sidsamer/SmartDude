
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<body>
<?php
include_once 'includes/connection.php';
session_start();
?>
<?php
if(isset($_COOKIE['Id']))
{
         echo "cokie found<br>";
	     header('Location: unitTest.php'); 
		 $_SESSION['Uid']=$_COOKIE['Uid'];
		 $_SESSION['Id']=$_COOKIE['Id'];
}
else
    echo "cokie was not found<br>";
?>

<h1><CENTER>Smart Dude</CENTER></h1><br><br>
<center>
<form action="index.php" method='post'>
<input type="text" name="User" placeholder="Enter User Name" pattern="[^()/><\][\\\x22,'=;|]+"><br>
<input type="password" name="Password" placeholder="Password"><br>
<button type="submit" value="Login" name="page">Login</button>
</form><br><br>
<a href="SignUp.php">press to sign up</a><br>

<?php
if(isset($_POST['page']))
{
        $name=$_POST['User'];
        $pass=$_POST['Password'];
        $myfile = fopen("boilerData.txt", "r") or die("Unable to open boiler data file!");//check uid valid
        $uid=fgets($myfile);
        fclose($myfile);
        $sql ="select id from users where name='$name' and password='$pass'";
        $result=mysqli_query($conn,$sql);
	    $resultCheck=mysqli_num_rows($result); 
        if($resultCheck>0)
       {
         $row=mysqli_fetch_assoc($result);

         setcookie('Id',$Id,time()+(60*60*24*7));
		 setcookie('Uid',$uid,time()+(60*60*24*7));
         
         if(isset($_COOKIE['Id']))
{
                  echo "cokie was set";
}
         header('Location: unitTest.php');
         $_SESSION['Id']=$row['id'];
         $_SESSION['Uid']=$uid;
       }
}
?>
</center>
</body>
</html>