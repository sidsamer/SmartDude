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
      $day=$_POST['dayButton'];
      $day=strtolower($day); //change day to lower case.
      $sql="select userId,showerTime,day,regular from turnon where day ='".$day."';";
 $result=mysqli_query($conn,$sql);
        $resultCheck=mysqli_num_rows($result);
        if($resultCheck>0)
       {
	     while($row=mysqli_fetch_assoc($result))
	   {
		   $day =$row['day'];
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
	   }
}
?>

</table>
</div>
</body>
</html>