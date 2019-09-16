<?php
include_once 'includes/connection.php';
session_start();
?>
<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>

td{
    border-bottom: 2px solid white;
	padding-up: 5px;
	padding-right: 10px;
}
</style>
<body>
<div class="tasks"; id="boards"; style="display:block; background-color:black;">
<table>
<?php
if(isset($_POST['dayButton']))
{
	$date =new DateTime($_POST['dayButton']);
	date_add($date, date_interval_create_from_date_string('1 days'));
	$result = $date->format('Y-m-d H:i:s');
      $sql="select userId,showerTime,day from turnon where userId=".$_SESSION['Uid']." and day ='".$_POST['dayButton']."';";
 $result=mysqli_query($conn,$sql);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck>0)
       {
	     while($row=mysqli_fetch_assoc($result))
	   {
		   $day =$row['day'];
           $showerTime=$row['showerTime'];
		   echo "<tr>";
		    echo "<td>".$row['userId']."</td><td style='color:Chartreuse;'>".$day."</td>".$showerTime."</td>";
		   echo "</tr>";
	   }
	   }
}
?>

</table>
</div>
</body>
</html>