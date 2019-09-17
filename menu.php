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


</style>
<body style="background-color:powderblue;">
<form  action="menu.php" method="POST">
<button type="submit" name="exit">Exit</button>
<button type="submit" name="deleteUser">DeleteUser</button>
</form>
<CENTER>
<table>
<td style='padding:30px;'><button class="NewButton" onclick="NoteBody('ScheduleForm');">Create</button></td>
<td style='padding:30px;'><button class="RemoveButton" onclick="NoteBody('RemoveForm');">Remove</button></td>
</table>
<br>
<div class="ScheduleForm"; id="ScheduleForm"; style="display:none;">
<form action='menu.php' method='post'>
<select name="regular"><br>
'<option value='0'>Not Regular</option>
'<option value='1'>Regular</option>
<input type="datetime-local" name="Deadline" required><br>
<button type="submit" value="SignUp" name="submit">Create/Update</button>
</form>
</div>

<div class="RemoveForm"; id="RemoveForm"; style="display:none;">
<form action="menu.php" method='post'>
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
	    header('Location: menu.php'); 

}

else if(isset($_POST['RemoveSubmit']))
{
	$val=$_POST['RemoveTaskList'];
	$sql="DELETE FROM turnon where id=$val";
	$res=mysqli_query($conn,$sql);
			if(!$res)
				echo("query faild".mysqli_connect_error());
            header('Location: menu.php');
}
else if(isset($_POST['exit'])) 
{
    setcookie('Id',$Id,time()-10);
	header('Location: index.php');
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
<form action="menu.php"  method='post' >
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
      echo $day;
      $sql="select userId,showerTime,day,regular from turnon where day ='".$day."';";
 $result=mysqli_query($conn,$sql);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck>0)
       {
           echo "<table>";
	     while($row=mysqli_fetch_assoc($result))
	   {
		   $day =$row['dayButton'];
           $showerTime=$row['showerTime'];
           if((int)$row['regular']==1)
               $regular="regular";
           else
               $regular="";
		   echo "<tr>";
		    echo "<td style='color:Chartreuse;'>".$row['userId']."</td><td style='color:Chartreuse;'>".$showerTime.
            "</td><td style='color:Chartreuse;'>".$day."</td>"."</td><td style='color:Chartreuse;'>".$regular."</td>";
		   echo "</tr>";
	   }
       echo "/<table>";
	   }
}
?>


</CENTER>
</body>
</html>