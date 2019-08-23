<?php
require_once "weights.txt";
require_once "data.txt";
include_once 'includes/connection.php';
class LinearRegression{
	
	var $w1; //temp diff weight.
	var $OutsideTemp;
    var $BoilerTemp;
    
	public function __construct($Otemp,$Btemp){
		$this->OutsideTemp=$Otemp;
		$this->BoilerTemp=$Btemp;
		$myfile = fopen("includes/weights.txt", "r") or die("Unable to open weights file!");
        $this->w1=fgets($myfile);
        fclose($myfile);
 }
 	function getW1()
	{
		return $this->w1;
	}
    function getOut()
	{
		return $this->OutsideTemp;
	}
    function getBoiler()
	{
		return $this->BoilerTemp;
	}
    function CalcDuration()
    {
        return 900;
    }
    function PredictTemp()
    {
       return $this->w1*($this->OutsideTemp-$this->BoilerTemp)+$this->BoilerTemp; 
    }
}
class LinearRegressionInput{
    
    var $ErrorThershold; 
    var $LearningRate;
    var $ExceptedSucssesRate;  ##a succses rate we want to achive.
    var $actualSucceseRate;
    var $avgdistance;  ## we want avg distance to be as low as possbile with that succses rate.
    var $actualAvgDistance; ##the actual avg distnace of the learning.
    var $Data; //array
    var $ExpectedOutputs; //array
    var $TestData; //array
    var $TestExpectedOutputs; //array
    
    public function __construct(){
        $this->ReadFile();
        $this->getData();
    }
    
    function ReadFile(){
        $myfile = fopen("includes/data.txt", "r") or die("Unable to open data file!");
        $this->ErrorThershold=fgets($myfile);
        $this->ExceptedSucssesRate=fgets($myfile);
        $this->avgdistance=fgets($myfile);
        $this->LearningRate=fgets($myfile);
        fclose($myfile);
    }
    function getData(){
        $host="eu-cdbr-west-02.cleardb.net";
        $dbuser="b930876c351ee7";
        $pass="0f8d4cc8";
        $dbname="heroku_c26d047c909fd55";
        $conn=mysqli_connect($host,$dbuser,$pass,$dbname);
        if(mysqli_connect_errno())
          {
	         die("Connection Faild!".mysqli_connect_error());
          }
         $sql='SELECT * FROM measurements;';
    $result=mysqli_query($conn,$sql);
    $resultCheck=mysqli_num_rows($result);
    if($resultCheck>0)
     {
         $i=0;
         while($i<$resultCheck)
         {
             $row=mysqli_fetch_assoc($result);
             //echo "<br> boiler:".$row['boilerTemp']." outside:".$row['outsideTemp'];
             $temps[$i]['boiler']=$row['boilerTemp'];
             $temps[$i]['outside']=$row['outsideTemp'];
             $i++;
         }
	 }
     $count=count($temps);
     $testsize=$count*0.1;
     $trainsize=$count-$testsize;
     for ($i=0;$i<$trainsize;$i++)
     {
         $this->Data[$i]['boiler']=$temps[$i]['boiler']; 
         $this->Data[$i]['out']=$temps[$i]['outside'];        
         $this->ExpectedOutputs[$i]= (($this->Data[$i]['boiler']*0.5)+($this->Data[$i]['out']*0.5) );  
         $this->Data[$i]['target']= (($this->Data[$i]['boiler']*0.5)+($this->Data[$i]['out']*0.5) );     
     }
     for ($j=0;$j<$testsize;$j++)
     {
         $this->TestData[$j]['boiler']=$temps[$i+$j]['boiler']); 
         $this->TestData[$j]['out']=$temps[$i+$j]['outside'];        
         $this->TestExpectedOutputs[$j]= (($this->TestData[$j]['boiler']*0.5)+($this->TestData[$j]['out']*0.5) );  
         $this->TestData[$j]['target']= (($this->TestData[$j]['boiler']*0.5)+($this->TestData[$j]['out']*0.5) );     
     }
    }
    function ToString(){
        echo $this->ErrorThershold. "   ".$this->LearningRate. "   ".$this->NumberOfMaximumIterations;
    }
}
class LinearRegressionTrainer extends LinearRegression{
    
    var $Input;
    
    public function __construct($Otemp,$Btemp){
        LinearRegression::__construct($Otemp,$Btemp);
        $this->Input=new LinearRegressionInput();
    }
    function saveWeightsInFile(){
        echo "<br><br>######################new weights########################<br>";
        echo "w1: ".$this->w1;
        $myfile = fopen("includes/weights.txt", "w") or die("Unable to open weights file!");
        fwrite($myfile,$this->w1);
        fclose($myfile);
    }
    function saveData(){
        $myfile = fopen("includes/data.txt", "w") or die("Unable to open weights file!");
        fwrite($myfile,$this->Input->ErrorThershold);
        fwrite($myfile,$this->Input->ExceptedSucssesRate);
        fwrite($myfile,$this->Input->avgdistance);
        fwrite($myfile,$this->Input->LearningRate);
        fclose($myfile);
    }
    function Train(){
        $counter=0;
        $trend=0; ## find to each mesurament the weight.
        $this->Input->actualAvgDistance=0;
        $this->Input->actualSucceseRate=0; 
        foreach ($this->Input->Data as $val)
        {
        $guess=$this->w1*($val['out']-$val['boiler'])+$val['boiler'];
		$erorr=$guess-$val['target'];

        echo "<br>".$counter." boiler: ".$val['boiler']." out: ".$val['out']."=".$val['target']."guess: ",$guess." , error:".$erorr." ,w1:".$this->w1;
        if($val['out']-$val['boiler'] !=0) // to prevent deviding by zero
        {
        $trend=1/(($val['out']-$val['boiler'])/($val['target']-$val['boiler'])); //   1/((out-boiler)/target-boiler)
        $this->gradient($trend);
        }
        $counter++;
        }
        echo "#################################test time###############################";
        $counter=0;
        foreach ($this->Input->TestData as $val)
        {
        $guess=$this->w1*($val['out']-$val['boiler'])+$val['boiler'];
		$erorr=$guess-$val['target'];
        echo "<br>".$counter." boiler: ".$val['boiler']." out: ".$val['out']."=".$val['target']."guess: ",$guess." , error:".$erorr." ,w1:".$this->w1;
        if(abs($erorr)<=$this->Input->ErrorThershold)
         $this->Input->actualSucceseRate++;
        $this->Input->actualAvgDistance+=abs($erorr);
        $counter++;
        }
        $this->Input->actualSucceseRate/=$counter;
        $this->Input->actualAvgDistance/=$counter;
    }
    function Test(){
        $learningRate=0.05; //high learning rate, beacuse we dont have enough mesuraments. 
        $tempw=$this->w1;//temp weight, for equle testing for each learning rate.
        while($learningRate>0.001)
          {
           $this->w1=$tempw;
           $this->Input->LearningRate=$learningRate;
           $this->Train();
           $this->Input->actualSucceseRate*=100;
        if(($this->Input->actualSucceseRate >=$this->Input->ExceptedSucssesRate) && ($this->Input->actualAvgDistance<=$this->Input->avgdistance))
     {
     echo "<br>##########################################################<br>";
     echo "learning rate:".$learningRate." succes rate:".$this->Input->actualSucceseRate." avg distance:".$this->Input->actualAvgDistance."<br>";
     echo "<br>##########################################################<br>";
     $this->saveWeightsInFile();
     $this->saveData();
     echo "new weight found!";
     break;
     }
$learningRate-=0.001;
    }
    }
    function gradient($trend)
    {
        $this->w1=($this->w1*(1-$this->Input->LearningRate))+($trend*$this->Input->LearningRate);
    }
    function ToString(){
        echo $this->w1;
    }
}

?> 