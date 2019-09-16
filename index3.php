<?php
//date('H:i:s', strtotime($turnOnTime));

$day=strtolower(date("l"));
$nowTime=date("h:i:s");
$pastTime=date('H:i:s',strtotime("".$nowTime." -300 seconds"));
$futureTime=date('H:i:s',strtotime("".$nowTime." +300 seconds"));
echo "day:".$day."<br>";
echo "time now:".$nowTime."<br>";
echo "time -5 minutes: ".$pastTime."<br>";
echo "time +5 minutes: ".$futureTime."<br>";



?>