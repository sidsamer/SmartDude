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
right:0;
}
.RemoveButton{
	background-color:#0080ff;
	position: absolute; 
left:0;
}
.container {
 height:100%;
 width:100%;
 max-width: 500px;
 }

.board button{
	background-color:#262626  	;
	 border-radius: 0px;
 border: 1px solid DarkSlateGray;
 padding: 10px 1px;
 font-size:11px;
float: left;
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
<CENTER>
<button class="NewButton" onclick="NoteBody('ScheduleForm');">+</button>
<button class="RemoveButton" onclick="NoteBody('RemoveForm');">X</button>
<br><br><br>
<div class="ScheduleForm"; id="ScheduleForm"; style="display:none;">
<form action='menu.php' method='post' >
<input type="text" placeholder="Regular or not" name="regular"required pattern="[^()/><\][\\\x22,'=;|]+"><br>
<input type="datetime-local" name="Deadline" required><br>
<button type="submit" value="SignUp" name="submit">Create/Update</button>
</form>
</div>
<?php
if(isset($_POST['RemoveSubmit']))
{
	$val=$_POST['RemoveTaskList'];
	$sql="DELETE FROM turnon where id=$val";
	$res=mysqli_query($conn,$sql);
			if(!$res)
				echo("query faild".mysqli_connect_error());
}
?>
<div class="RemoveForm"; id="RemoveForm"; style="display:none;">
<form action="menu.php" method='post'>
  <select name="RemoveTaskList">
  <?php
  
        $sql="select userId,id,showerTime,day from turnon where id=".$_SESSION['Id'].";";
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
    // $url="http://smart-dude.herokuapp.com/Android_req.php/?order=newSchdule&userId=$userid&day=$day&showerTime=$time&regular=$regular";
          // $contents = file_get_contents($url);
         // if($contents !== false)
        // echo $contents;
    // else
        // echo "cant make http req";
	    // header('Location: menu.php'); 
        echo $userid." ".$day." ".$time." ".$regular" ";

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