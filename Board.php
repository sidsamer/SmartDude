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
      $sql="select userId,showerTime,day from turnon where day ='".$_POST['dayButton']."';";
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