<?php
//require_once "includes/weights.txt";
class LinearRegression{
	
	var $w1; //outside weight
	var $w2; //boiler weight
	var $OutsideTemp;
    var $BoilerTemp;
    var $text;
    
	public function __construct($Otemp,$Btemp){
		$this->OutsideTemp=$Otemp;
		$this->BoilerTemp=$Btemp;
		$myfile = fopen("includes/weights.txt", "r") or die("Unable to open weights file!");
        $this->w1=fgets($myfile);
        $this->w2=fgets($myfile);
        fclose($myfile);
 }
 	function getText()
	{
		return $this->w1." ".$this->w2;
	}
    function getOut()
	{
		return $this->OutsideTemp;
	}
    function getBoiler()
	{
		return $this->BoilerTemp;
	}
}
?> 