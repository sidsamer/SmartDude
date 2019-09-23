<?php
/*
this page is the login page,from here you can go to Sign up if you dont have user yet
or insert user and pass and enter into menu of the 'app'. 
*/
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body {
color:white;
background-image: url("page1.png");
background-repeat: no-repeat;
background-size:100%;
}
 button{
 background-color:transparent;
 border-radius:20%;
 color:LightGray;
 font-size:15px;
 padding: 10px 15px;
 }
  input {
  padding: 4px 4px;
  margin: 4px 0;
}
</style>
<body style="background-color:blue;">
<?php
include_once 'includes/connection.php';
session_start();
?>
<?php
if(isset($_COOKIE['Id']))
{
         echo "cokie found<br>"; 
		 $_SESSION['Uid']=$_COOKIE['Uid'];
		 $_SESSION['Id']=$_COOKIE['Id'];
         header('Location: menu.php');
}

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
if(isset($_POST['page'])) //this flow checks if user and pass are valid.
{
        $name=$_POST['User'];
        $pass=$_POST['Password'];
        $myfile = fopen("boilerData.txt", "r") or die("Unable to open boiler data file!");//take Unique id, for future use.
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
         $_SESSION['Id']=$row['id'];
         $_SESSION['Uid']=$uid;
        header('Location: menu.php');
       }
}
?>
</center>
</body>
</html>