<?php
//this script handle the microconroller http requests.
//we used a fifo of tasks like on/off/temp.
include_once 'includes/connection.php';

    $sql='SELECT * FROM tasks ORDER BY id asc LIMIT 1;'; //return the first task from fifo
    $result=mysqli_query($conn,$sql);
    $resultCheck=mysqli_num_rows($result);
    if($resultCheck>0)
     {
	    $row=mysqli_fetch_assoc($result);
        $Str=$row["task"];
        do{
        $sql='DELETE FROM tasks WHERE id='.$row["id"].';'; //delete it after use.
          $result=mysqli_query($conn,$sql);
        }while(!$result);
        echo($Str);
	 }
     else
     {
         echo("none");
     }
?>