<?php
include_once "weights.txt";
require_once "data.txt";
require_once "connection.php";
class LinearRegression{
	
	var $w1; //outside weight
	var $w2; //boiler weight
	var $OutsideTemp;
    var $BoilerTemp;
    var $text;
    
	public function __construct($Otemp,$Btemp){
		$this->OutsideTemp=$Otemp;
		$this->BoilerTemp=$Btemp;
		$myfile = fopen("weights.txt", "r") or die("Unable to open weights file!");
        $this->w1=fgets($myfile);
        $this->w2=fgets($myfile);
        fclose($myfile);
 }
 	function getW1()
	{
		return $this->w1;
	}
    function getW2()
	{
		return $this->w2;
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
}
class LinearRegressionInput{
    
    var $ErrorThershold; 
    var $LearningRate; 
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
        $myfile = fopen("data.txt", "r") or die("Unable to open data file!");
        $this->ErrorThershold=fgets($myfile);
        $this->LearningRate=fgets($myfile);
        $this->NumberOfMaximumIterations=fgets($myfile);
        fclose($myfile);
    }
    function getData(){
        
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
        
    }

    function Train(){
        foreach ($this->Input->Data as $val)
        {
        $guess=$this->w1*$val['boiler']+$this->w2*$val['out'];
		$erorr=$val['target']-$guess;
        gradient();
        }
    }
    function gradient(){
        
       foreach ($this->Input->Data as $val)
       {
			$this->w1+=$erorr * $val['boiler']*$Input->LearningRate;
            $this->w2+=$erorr * $val['out']*$Input->LearningRate;            
       }
    }
    function ToString(){
        echo $this->w1;
    }
}

$test= new LinearRegressionInput();
$test2=new LinearRegressionTrainer(12,2);
$test2->ToString();
?> 