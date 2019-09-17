<?php
include_once 'includes/connection.php';
session_start();
?>
<!DOCTYPE html>
<html>
<script src="javascript.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
.NewButton{
	background-color:#0080ff;
	position: absolute; 
}
.RemoveButton{
	background-color:#0080ff;
	position: absolute; 
}
.container {
 height:100%;
 width:100%;
 max-width: 500px;
 }

table{
background-color:black;
}
td{
    border-bottom: 2px solid white;
	padding-up: 5px;
	padding-right: 10px;
}

</style>
<body>
<form  action="menu.php" method="POST">
<button type="submit" name="exit">Exit</button>
</form>
<CENTER>
<table>
<td style='padding:15px;'><button class="NewButton" onclick="NoteBody('ScheduleForm');">Create</button></td>
<td style='padding:15px;'><button class="RemoveButton" onclick="NoteBody('RemoveForm');">Remove</button></td>
</table>
<br><br><br>
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

if(isset($_POST['RemoveSubmit']))
{
	$val=$_POST['RemoveTaskList'];
	$sql="DELETE FROM turnon where id=$val";
	$res=mysqli_query($conn,$sql);
			if(!$res)
				echo("query faild".mysqli_connect_error());
            header('Location: menu.php');
}
?>
<div class="board"; id="board";">
<form action="Board.php"  method='post' target="myFrame2">
<?php
for($i=0;$i<7;$i++)
{
	?>
<button type="submit" value= "<?php echo date("l", strtotime("+$i days")); ?>" name="dayButton"><?php echo date("l", strtotime("+$i days")); ?></button>
<?php
}
?>
</form>
<div class="container">
<iframe id="myFrame2" src="Board.php" name="myFrame2" height="600px" width="100%" style="border:none;"></iframe>
</div>


</CENTER>
</body>
</html>