<?php
/*
in this module we have our regression and all the functions we need in order to
learn the user system behaves the best way we can.
*/
include_once 'includes/connection.php';
class LinearRegression{ //this class used by the system to predict
	
	var $w1; //temp diff weight.
	var $OutsideTemp;
    var $BoilerTemp;
    
    //constructor
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
    //this func is used for calculation of the duration we need the boiler to be turn on.
    function CalcDuration($currTemp,$wantedTemp,$volume) 
    {
        $HeaterElementRating=2.5; //heater element rating in kW.
        $ConstantVar=4.2; //for calculating the pt(see bellow).
        $Liters=$volume; // amount of liters in the tank.
        //$pt=(4.2*L*T)/3600  L=size of boiler(150 liter) T=wanted temp minus current temp. 
        if($currTemp>=$wantedTemp) //if we dont need to turnon the boiler.
            return 0;
        $pt=($ConstantVar*$Liters*($wantedTemp-$currTemp))/3600; //Pt is the power used to heat the water, in kWh ((4.2*L*T)/3600). 
        $time=($pt/$HeaterElementRating)*60*60; //the heating time in seconds.
        return $time;
    }
    //this function used when we want to know what will be the future temp inside the boiler.
    function PredictTemp()
    {
       return $this->w1*($this->OutsideTemp-$this->BoilerTemp)+$this->BoilerTemp; //weight*diffBetweenTemps+BoilerTemp
    }
}
class LinearRegressionInput{ //this class contains all the data we need for 
    
    var $ErrorThershold; //the minimal erorr rate exepted by us.
    var $LearningRate; //represent the speed the model shift himself to the right weight
    var $ExceptedSucssesRate;  //a succses rate we want to achive.
    var $actualSucceseRate; //the succses rate we get from the model
    var $avgdistance;  // we want avg distance to be as low as possbile with that succses rate.
    var $actualAvgDistance; ##the actual avg distnace of the learning.
    var $Data; //the mesuraments for the learning
    var $ExpectedOutputs; //array
    var $TestData; //the mesuraments for the testing
    var $TestExpectedOutputs; //array
    
    public function __construct(){
        $this->ReadFile();
        $this->getData();
    }
    //in this func we read all the data we need for the learning
    function ReadFile(){
        $myfile = fopen("includes/data.txt", "r") or die("Unable to open data file!");
        $this->ErrorThershold=fgets($myfile);
        $this->ExceptedSucssesRate=fgets($myfile);
        $this->avgdistance=fgets($myfile);
        $this->LearningRate=fgets($myfile);
        fclose($myfile);
    }
    //in this func we get our temp mesuraments from the Database
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
    if($resultCheck>0) //getting all the mesuraments
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
     echo "mesuraments:".$count."<br><br>";
     $testsize=$count*0.1;
     $trainsize=$count-$testsize;
     $count=0;
     for ($i=0;$i<$trainsize;$i++) //taking 90% of the mesuraments for the training
     {
         $this->Data[$i]['boiler']=$temps[$i]['boiler']; 
         $this->Data[$i]['out']=$temps[$i]['outside'];        
         $this->ExpectedOutputs[$i]= (($this->Data[$i]['boiler']*0.5)+($this->Data[$i]['out']*0.5) );//give it specific tendency,we use it cause the the mesuraments arent real
         $this->Data[$i]['target']= (($this->Data[$i]['boiler']*0.5)+($this->Data[$i]['out']*0.5) );
         $count++;         
     }
     for ($j=0,$i;$i<$count+$testsize;$j++,$i++) //taking 10% of the mesuraments for the testing
     {
         $this->TestData[$j]['boiler']=$temps[$i]['boiler']; 
         $this->TestData[$j]['out']=$temps[$i]['outside'];        
         $this->TestExpectedOutputs[$j]= (($this->TestData[$j]['boiler']*0.5)+($this->TestData[$j]['out']*0.5) );  
         $this->TestData[$j]['target']= (($this->TestData[$j]['boiler']*0.5)+($this->TestData[$j]['out']*0.5) );     
     }
    }
    //to string just for testing
    function ToString(){
        echo $this->ErrorThershold. "   ".$this->LearningRate. "   ".$this->NumberOfMaximumIterations;
    }
}
class LinearRegressionTrainer extends LinearRegression{ //this class responsible for the training and testing
    
    var $Input; //object that contains valuble data to our learning process
    
    public function __construct($Otemp,$Btemp){
        LinearRegression::__construct($Otemp,$Btemp);
        $this->Input=new LinearRegressionInput();
    }
    //this func save the weight inside a file after the training process
    function saveWeightsInFile(){
        echo "<br><br>######################new weights########################<br>";
        echo "w1: ".$this->w1;
        $myfile = fopen("includes/weights.txt", "w") or die("Unable to open weights file!");
        fwrite($myfile,$this->w1);
        fclose($myfile);
    }
    //this func save the all the data we used for the learning
    function saveData(){
        $myfile = fopen("includes/data.txt", "w") or die("Unable to open weights file!");
        fwrite($myfile,$this->Input->ErrorThershold);
        fwrite($myfile,$this->Input->ExceptedSucssesRate);
        fwrite($myfile,$this->Input->avgdistance);
        fwrite($myfile,$this->Input->LearningRate);
        fclose($myfile);
    }
    //this is the train func,this is where all the 'magic' hapening
    function Train(){
        $counter=0;
        $trend=0; ## find to each mesurament the weight.
        $this->Input->actualAvgDistance=0;
        $this->Input->actualSucceseRate=0; 
        foreach ($this->Input->Data as $val)//this loop checks the guess next to expected and decide error,then sends the error to the gradient which turn the weight to the right side.
        {
        $guess=$this->w1*($val['out']-$val['boiler'])+$val['boiler'];
		$erorr=$guess-$val['target'];
        echo "<br>".$counter." boiler: ".$val['boiler']." out: ".$val['out']."=".$val['target']."guess: ",$guess." , error:".$erorr." ,w1:".$this->w1;
        if($val['out']-$val['boiler'] !=0) // to prevent deviding by zero
        {
        $trend=1/(($val['out']-$val['boiler'])/($val['target']-$val['boiler'])); //   1/((out-boiler)/target-boiler)
        $this->gradient($trend); //calling gradient func.
        }
        $counter++;
        }
        echo "<br><br>#################################test time###############################<br><br>";
        $counter=0;
        foreach ($this->Input->TestData as $val) //this is where we testing our learning process,and giving ourself a 'grade'.
        {
        $guess=$this->w1*($val['out']-$val['boiler'])+$val['boiler']; //weight * diff + boilerTemp
		$erorr=$guess-$val['target']; //difrent between guess and expected.
        echo "<br>".$counter." boiler: ".$val['boiler']." out: ".$val['out']."=".$val['target']."guess: ",$guess." , error:".$erorr." ,w1:".$this->w1;
        if(abs($erorr)<=$this->Input->ErrorThershold) //only if error is in fine margin
         $this->Input->actualSucceseRate++; //counting how much guess were right out of n guess
        $this->Input->actualAvgDistance+=abs($erorr); //calculating the avg distance from the real values
        $counter++;
        }
        $this->Input->actualSucceseRate/=$counter;
        $this->Input->actualAvgDistance/=$counter;
    }
    //this func searching for the best learningRate for our data set.
    function Test(){
        $learningRate=0.1; //starting with high learning rate, beacuse we dont have enough mesuraments. 
        $tempw=$this->w1;//temp weight, for equle testing for each learning rate.
        while($learningRate>0.01)
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
$learningRate-=0.001; //slowing down the learning rate to be more acurate.
    }
    }
    //this func 
    function gradient($trend)
    {
        $this->w1=($this->w1*(1-$this->Input->LearningRate))+($trend*$this->Input->LearningRate); //weight=weight*(1-LearningRate)+((1/((out-boiler)/target-boiler)*LearningRate)
    }
    function ToString(){
        echo $this->w1;
    }
}

?> 