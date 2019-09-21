<?php
//this module shows the temps inserted into the Database.
include_once 'connection.php';

    $sql='SELECT * FROM measurements;';
    $result=mysqli_query($conn,$sql);
    $resultCheck=mysqli_num_rows($result);
    if($resultCheck>0)
     {
         $i=0;
         while($i<$resultCheck)
         {
             	    $row=mysqli_fetch_assoc($result);
             echo "<br> boiler:".$row['boilerTemp']." outside:".$row['outsideTemp'];
             $i++;
         }
	 }
     else
     {
         echo("resultCheck:".$resultCheck);
     }