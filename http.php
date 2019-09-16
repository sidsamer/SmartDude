<?php
include_once 'includes/connection.php';
 $sql='SELECT * FROM tasks ORDER BY id asc LIMIT 1;';
    $result=mysqli_query($conn,$sql);
    $resultCheck=mysqli_num_rows($result);
    if($resultCheck>0)
     {
	    $row=mysqli_fetch_assoc($result);
        $Str=$row["task"];
        do{
        $sql='DELETE FROM tasks WHERE id='.$row["id"].';';
          $result=mysqli_query($conn,$sql);
        }while(!$result);
        echo($Str);
	 }
     else
     {
         echo("none");
     }
?>