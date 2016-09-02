<?php

class phpOutput{

	private $objectName;
	private $message;
	private $objectToHandle;
	private $filehandle;
	private $fileName;
	private $filePath;

	private $nextline="\n";

	private $level=0; //control the tab number to show beautiful


	//to initial the $filename /$filepass(default current dictionary ) and so on  !!! $filehandle ,
	function __construct($fileName='test.txt',$filePath="./"){

		$this->fileName=$fileName;
		$this->filePath=$filePath;

		if ($this->filePath == "./") {
			$this->filehandle=fopen($this->fileName,"w");
		}else {
			$path=$this->filePath.$this->fileName;
			$this->filehandle=fopen($path,"w");
		}


		//echo $this->filePath." ".$this->fileName;
//		ftruncate($this->filehandle, 0);


	}

	function setObject($objectToHandle1,$objectName1,$message1){

		$this->objectToHandle=$objectToHandle1;
		$this->objectName = "\$".$objectName1;
		$this->message=$message1;


		$this->outputHead();
		$this->level=0;
	}


	function outputHead(){
		fwrite($this->filehandle,$this->getLevelTab()."this is the information of $this->objectName( the test is for  $this->message )  \n");
	}

	//out put controller to determine the which method will be called.
	function output(){

		if (gettype($this->objectToHandle) == "Null") {
			fwrite($this->filehandle, $this->getLevelTab()."the $this->objectToHandle is null \n");
		}elseif(gettype($this->objectToHandle) == "object"){
			$this->outputUnknowType($this->objectToHandle);
		}elseif (gettype($this->objectToHandle) == "array") {
			$this->outputArray($this->objectToHandle);
		}else {
			$this->outputGeneral($this->objectToHandle);
		}

		$this->closeFile();
	}



	//out put the array by example.
	function outputArray($typecallArray){
		fwrite($this->filehandle,$this->getLevelTab()."this is one array \n");
		$this->level++;

	//	foreach($typecallArray as $key => $value){
		foreach($typecallArray as $key => $value) {
			fwrite($this->filehandle,$this->getLevelTab()."$key :");
			if(gettype($value) == "object"){
			//	$this->level++;
				$this->outputUnknowType($value);
			}elseif (gettype($value) == "array") {
			//		$this->level++;
				$this->outputArray($value);
			}else {
				$this->outputGeneral($value);
			}
		}



		$this->level--;
		fwrite($this->filehandle,$this->getLevelTab()."the array is end \n");

	}


	//out put the complex variable such as nest object
	function outputUnknowType($unknowObject){


		fwrite($this->filehandle,$this->getLevelTab()."the object start\n");
		$this->level++;
		if(gettype($unknowObject) =="object"){
			//use loop to check every attribute in the class
			foreach($unknowObject as $key => $value) {

					fwrite($this->filehandle, $this->getLevelTab()." $key :");

					if(gettype($value) == "object"){ //if there exist another object as an atrribute
						$this->outputUnknowType($value);
					}elseif (gettype($value) == "array") { //the atrribute is array.
						$this->outputArray($value);
					}else {                           //the atrribute is a gneral variable
						$this->outputGeneral($value);
					}

			}

		}

		$this->level--;
		fwrite($this->filehandle,$this->getLevelTab()."the object end\n");

	}

	//out put the general variable with string boolean and others.
	//will out put the type of the variable;
	function outputGeneral($generalVariable){
		fwrite($this->filehandle,$this->getLevelTab().$generalVariable."      ======>the type of the value is :".gettype($generalVariable)."\n");
	//	echo $generalVariable;
	}



	//close the file
	function closeFile(){
		fwrite($this->filehandle,"this is end of object $this->objectName \n");
	}

	function getLevelTab(){

		$tab="";
		for ($i=0; $i <$this->level ; $i++) {

			$tab=$tab."\t";

		}
		return $tab;
	}


	function writeStringMessage($message ){

		fwrite($this->filehandle," \n\n $message \n\n");
	}

}


?>
