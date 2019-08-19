<?php
require_once "weights.txt";
require_once "data.txt";
require_once "connection.php";
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
    var $ExceptedSucssesRate;
    var $NumberOfMaximumIterations;
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
        $this->LearningRate=fgets($myfile);
        $this->NumberOfMaximumIterations=fgets($myfile);
        $this->ExceptedSucssesRate=fgets($myfile);
        fclose($myfile);
    }
    function getData(){
     for ($i=0;$i<100;$i++)
     {
         $this->Data[$i]['boiler']=rand(10,40); 
         $this->Data[$i]['out']=rand(10,40);        
         $this->ExpectedOutputs[$i]= (($this->Data[$i]['boiler']*0.5)+($this->Data[$i]['out']*0.5) );  
         $this->Data[$i]['target']= (($this->Data[$i]['boiler']*0.5)+($this->Data[$i]['out']*0.5) );     
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

    function Train(){
        $i=0;
        $avg=0;
        foreach ($this->Input->Data as $val)
        {
        $guess=$this->w1*($val['out']-$val['boiler'])+$val['boiler'];
		$erorr=$guess-$val['target'];
        echo "<br>".$i." boiler: ".$val['boiler']." out: ".$val['out']."=".$val['target']."guess: ",$guess." , error:".$erorr." ,w1:".$this->w1;
        if($val['out']-$val['boiler'] !=0) // to prevent deviding by zero
        {
        $avg=1/(($val['out']-$val['boiler'])/($val['target']-$val['boiler'])); //   1/((out-boiler)/target-boiler)
        $this->gradient($avg);
        }
        $i++;
        }
        $this->saveWeightsInFile();
    }
    function gradient($avg)
    {
        $this->w1=($this->w1*(1-$this->Input->LearningRate))+($avg*$this->Input->LearningRate);
    }
    function test()
    {
        
        return  true; ///pass/faild.
    }
    function ToString(){
        echo $this->w1;
    }
}

?> 