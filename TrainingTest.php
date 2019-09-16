<?php
require_once "includes/Training.php";
$learningRate=0.05; 
 $trainer=new LinearRegressionTrainer(30,50);
$tempw=$trainer->w1;//temp weight, for equle testing for each learning rate.
while($learningRate>0.001)
{
 $trainer->w1=$tempw;
 $trainer->Input->LearningRate=$learningRate;
 $trainer->Train();
 $trainer->Input->actualSucceseRate*=100;
 if(($trainer->Input->actualSucceseRate >=$trainer->Input->ExceptedSucssesRate) && ($trainer->Input->actualAvgDistance<=$trainer->Input->avgdistance))
 {
     echo "<br>##########################################################<br>";
     echo "learning rate:".$learningRate." succes rate:".$trainer->Input->actualSucceseRate." avg distance:".$trainer->Input->actualAvgDistance."<br>";
     echo "<br>##########################################################<br>";
     $trainer->saveWeightsInFile();
     $trainer->saveData();
     break;
 }
$learningRate-=0.001;
}

