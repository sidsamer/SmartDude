<?php

// $date=$userId=htmlspecialchars($_GET["userId"]);
// $temp=shell_exec('wget http://smart-dude.herokuapp.com/Android_req.php/?order=on');
// echo $temp;

include_once 'includes/connection.php';
$sql="SELECT * FROM turnon";
	     $result=mysqli_query($conn,$sql);
	     $resultCheck=mysqli_num_rows($result); 
         if($resultCheck>0)
       {
           while($row=mysqli_fetch_assoc($result))
           {
               echo $row['userId']." ".$row['showerTime']." ";
           }
       }

?>