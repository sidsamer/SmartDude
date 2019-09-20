<?php
include_once 'includes/connection.php';
session_start();
?>
<!DOCTYPE html>
<html>
<script src="javascript.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
.container {
 height:100%;
 width:100%;
 max-width: 500px;
 }
 button{
 background-color:transparent;
  border-radius:50%;
 color:LightGray;
 font-size:15px;
 padding: 10px 15px;
 }
</style>
<body style="background-color:blue;">
<form  action="UserSchedule.php" method="POST">
<button type="submit" name="back">Back</button>
<button type="submit" name="deleteUser">DeleteUser</button>
</form>
<CENTER>
<table>
<td style='padding:30px;'><button class="NewButton" onclick="NoteBody('ScheduleForm');">Create</button></td>
<td style='padding:30px;'><button class="RemoveButton" onclick="NoteBody('RemoveForm');">Remove</button></td>
<td style='padding:30px;'><button class="UpdateButton" onclick="NoteBody('UpdateForm');">Update</button></td>
</table>
<br>
<div class="ScheduleForm"; id="ScheduleForm"; style="display:none;">
<form action='UserSchedule' method='post'>
<input type="datetime-local" name="Deadline" required><br>
<select name="regular">
<option value='0'>Not Regular</option>
<option value='1'>Regular</option>
</select><br>
<button type="submit" value="SignUp" name="submit">Create/Update</button>
</form>
</div>

<div class="RemoveForm"; id="RemoveForm"; style="display:none;">
<form action="UserSchedule.php" method='post'>
  <select name="RemoveTaskList">
  <?php
  
        $sql="select userId,id,showerTime,day from turnon where userId=".$_SESSION['Id'].";";
        $result=mysqli_query($conn,$sql);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck>0)
       {
	     while($row=mysqli_fetch_assoc($result))
	   {
		   $deadlineDate=new DateTime($row['deadline']);
		  echo '<option value='.$row["id"].'>'.$row["day"].' , '.$row["showerTime"].'</option>';
	   }
	   }
     
  ?>

  </select>
  <br><br>
<button type="submit" value="Submit" name="RemoveSubmit">Remove</button>
</form>
</div>
<div class="UpdateForm"; id="UpdateForm"; style="display:none;">
<form action="UserSchedule.php" method='post'>
  <input type="text" name="Volume" placeholder="Volume"><br>
  <input type="email" name="Email" placeholder="Email"><br>
<button type="submit" value="Submit" name="UpdateSubmit">Update Boiler</button>
</form>
</div>
<br><br>
<?php
if(isset($_POST['submit']))
{
	$userid=$_SESSION['Id'];
    $day=date("l", strtotime($_POST['Deadline']));
    $time=date('H:i:s',strtotime($_POST['Deadline']));
    $regular=$_POST['regular'];
    $url="http://smart-dude.herokuapp.com/Android_req.php/?order=newSchdule&userId=$userid&day=$day&showerTime=$time&regular=$regular";
          $contents = file_get_contents($url);
         if($contents !== false)
        echo $contents;
    else
        echo "cant make http req";
       header('Location: UserSchedule.php'); 

}

else if(isset($_POST['RemoveSubmit']))
{
	$val=$_POST['RemoveTaskList'];
	$sql="DELETE FROM turnon where id=$val";
	$res=mysqli_query($conn,$sql);
			if(!$res)
				echo("query faild".mysqli_connect_error());
        header('Location: UserSchedule.php'); 
}
else if(isset($_POST['UpdateSubmit']))
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
        header('Location: UserSchedule.php'); 
}
else if(isset($_POST['back'])) 
{
	header('Location: menu.php');
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
?>
<form action="UserSchedule.php"  method='post' >
<select type="submit" name="dayButton">
<?php
for($i=0;$i<7;$i++)
{
    echo "<option value=".date("l", strtotime("+$i days")).">".date("l", strtotime("+$i days"))."</option>";
}
?>
  </select>
  <br><br>
  <button type="submit" value="Submit" name="Day">Day</button>
</form>
<?php
if(isset($_POST['Day']) || isset($_POST['dayButton']))
{
      $day=$_POST['dayButton'];
      $day=strtolower($day); //change day to lower case.
      $sql="select userId,showerTime,day,regular from turnon where day ='".$day."';";
 $result=mysqli_query($conn,$sql);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck>0)
       {
           echo "<table bgcolor='blue'>";
           echo "<tr><th>User</th><th>time</th><th>regular</th></tr>";
	     while($row=mysqli_fetch_assoc($result))
	   {
          $name="unknown";
          $user=$row['userId'];
          $sql2="select name from users where id=$user";
	      $result2=mysqli_query($conn,$sql2);
	      $resultCheck2=mysqli_num_rows($result2); 
          if($resultCheck2>0)
        {
           $row2=mysqli_fetch_assoc($result2);
           $name=$row2['name'];
	    }
		   $day=$row['dayButton'];
           $showerTime=$row['showerTime'];
           if((int)$row['regular']==1)
               $regular="regular";
           else
               $regular="";
		   echo "<tr>";
		    echo "<td style='color:Chartreuse;'>".$name."</td><td style='color:Chartreuse;'>".$showerTime.
            "</td>"."</td><td style='color:Chartreuse;'>".$regular."</td>";
		   echo "</tr>";
	   }
       echo "</table>";
	   }
}
?>


</CENTER>
</body>
</html>