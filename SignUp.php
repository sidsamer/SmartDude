<!DOCTYPE html>
<?php
/*
this page is the signup page, the user fills the right fields and if everyting wents well
he will have a new user to login with into the system.
*/
?>
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
 color:white;
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
?>
<h1><CENTER>Sign-Up</CENTER></h1><br><br>
<center><form action='SignUp.php' method='post' enctype="multipart/form-data">
<input type="text" placeholder="User Name" name="User" required pattern="[^()/><\][\\\x22,'=;|]+"><br>
<input type="text" placeholder="password" name="Password" required><br>
<input type="text" placeholder="password Check" name="Repassword" required><br>
<input type="text" placeholder="Favorite Temp" name="Temp" required><br>
<input type="text" placeholder="Phone" name="Phone" required><br>
<input type="text" placeholder="System Id" name="uid" required><br>
<input type="email" placeholder="E-mail" name="Email" required><br><br>
<button type="submit" value="submit" name="page">Submit</button><br><br>
</form>
<?php
if(isset($_POST['page']))
{
	if($_POST['Password']==$_POST['Repassword'])//checks if match
	{
         $name=$_POST['User'];
         $temp=$_POST['Temp'];
         $pass=$_POST['Password'];
         $phone=$_POST['Phone'];
         $uid=$_POST['uid'];
		$User=$_POST['User'];
         $myfile = fopen("boilerData.txt", "r") or die("Unable to open boiler data file!");//check uid valid
        $uid2=fgets($myfile);
        fclose($myfile);
        if((int)$uid == (int)$uid2) //check if user inserted valid Unique id of a real system.
        { 
         $sql = "select * from users where name='$name';";
	     $result=mysqli_query($conn,$sql);
	     $resultCheck=mysqli_num_rows($result); 
         if($resultCheck==0) //checks if user name is not used yet
       {
           $sql = "INSERT INTO users(name,temp,password,phone) 
           VALUES ('$name','$temp','$pass','$phone');";
        $result=mysqli_query($conn,$sql);
		if(!$result)
			die("query faild");
        else
        {
        $myfile = fopen("numOfUsers.txt", "r") or die("Unable to open boiler data file!"); //adding one to the user counter
        $num=fgets($myfile);
        fclose($myfile);
        $num=(int)$num+1;
        $myfile = fopen("numOfUsers.txt", "w") or die("Unable to open boiler data file!");
        fwrite($myfile,$num.PHP_EOL);
        fclose($myfile);
        echo "welcome new member <br>";
		header('Location: index.php'); 
        }
       }
       else
           echo ("user allready exist"); // if name is not available
        }
        else
            echo ("cant find uid, cant create a new user!"); //unique id dont mach the one the system use.
    }
else
  echo "Passwords didnt match!";  //passowrds should mach

}
?>
</center>
</body>
</html>