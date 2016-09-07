<?php
// provide some functions here
//which operating on the database
require_once  __DIR__ . '/db.php';
require_once __DIR__ . '/output.php';

function loadData($tableName,$tableColumnsArray){
  // with table name check

  $data = "";

  $columns = "";
  $colArray = null;
  for ($i=0; $i < count($tableColumnsArray); $i++) {
    if ($tableColumnsArray[$i]["table"] == $tableName) {
      $col = $tableColumnsArray[$i]["columns"];
    }
  }

  if ($colArray == null) {
    $columns = "*";
  }else {
    for ($i=0; $i <count($colArray) ; $i++) {
      $columns = $columns.$colArray[$i];
      if ($i<count($colArray)-1) {
        $columns=$columns.",";
      }
    }
  }

  $sql = "select ".$columns." from ".$tableName;
  $data = getDataFromDbBySql($sql);
  return $data;
}

function displayDataInTable($data){
    $colDef = [];
    foreach ($data[0] as $key => $value) {
      // add class propertiy to colDef
      array_push($colDef,$key);
    }
    $columnsMaxLength = [];
    foreach ($colDef as $key1 => $value1) {
      $columnsMaxLength[$key1] = 0;
      foreach ($data as $key2 => $value2) {
        if (strlen($value2[$colDef[$key1]]) > $columnsMaxLength[$key1]) {
          $columnsMaxLength[$key1] = strlen($value2[$colDef[$key1]]);
        }
      }
    }



    echo generateWholeTable($data,$colDef,$columnsMaxLength);

}

function addRowDataToArray($rows,$content){
  //create the
  return $rows.$content;

}
function createColumnDef($columns,$columnsMaxLength){
  $columnsContent = "";
  for ($i=0; $i <count($columns) ; $i++) {
    // $columnsContent = $columnsContent."<th>";
    // $columnsContent = $columnsContent.$columns[$i];
    // $columnsContent = $columnsContent."</th>";

    // $columnsContent = $columnsContent.getNewTagWithContent("th",$columns[i]);
    $columnsContent = addRowDataToArray($columnsContent,getNewTagWithContent("th",$columns[$i],$columnsMaxLength[$i]));

  }
  // $output = new phpoutput();
  // $output->setObject(getNewTagWithContent("th",$columns[0]),"coulumns html","");
  // $output->output();

  return getNewTagWithContent("tr",$columnsContent);
  // $columnsDef = "<tr>";
  // $columnsDef = $columnsDef."</tr>";
}
function createDataRow($data,$columns,$columnsMaxLength){
  $rows = "";

  for ($i=0; $i <count($data) ; $i++) {
    $rowData = "";
    for ($j=0; $j <count($columns) ; $j++) {
      $rowData = addRowDataToArray($rowData,getNewTagWithContent("td",getNewInputWithEditCheck($columns[$j],$data[$i][$columns[$j]],$i,$columnsMaxLength[$j]),$columnsMaxLength[$j]));
      // $rowData = $rowData.getNewTagWithContent("td",getNewInputWithEditCheck($columns[$j],$data[$i][$columns[$j]]),$i);
    }
    $row = getNewTagWithContent("tr",$rowData);
    $rows = $rows.$row;
  }

  return $rows;
}
function generateWholeTable($data,$columnsDef,$columnsMaxLength){
  // the table contains two part , one is the columns define then is the data
  $columns = createColumnDef($columnsDef,$columnsMaxLength);

  $dataRow = createDataRow($data,$columnsDef,$columnsMaxLength);



  return getNewTagWithContent("table",$columns.$dataRow);

}
function getNewTagWithContent($tageName,$content,$maxlength = null){
  $str = "";
  $str = $str."<$tageName ";
  //get the max length for the columns
  // $str = $str.'maxlength= "'.strlen($content).'"';
  // if (isset($maxlength)&&$maxlength!=null) {
  //   $str = $str . "maxlength = '".$maxlength."'";
  // }
  $str = $str.' width ="100%" ';
  $str = $str.">";
  $str = $str.$content;
  $str = $str."</$tageName>";
  return $str;
}

function getNewInputWithEditCheck($key,$value,$objectIndexInData,$maxlength=null){
  //<input idspable
  // $tableMappingObj = getMappingObjectFromJson($GLOABLES['tableName'],$GLOABLES['tablecolumnsmapping']);
  global $tableMappingObj;
  $str = "<input ";
  //if is the key is the key  ,can't change
  //only used for one primary key
  if($key == $tableMappingObj["keyId"][0] ){

    $str = $str.' readonly="readonly" ';
  }
  $str = $str . ' type = "text" ';


  $str = $str.' name='.$key.$objectIndexInData." ";
  // $str = $str. ' maxlength ='.strlen($value).' ';
  // if (isset($maxlength)&&$maxlength!=null) {
  //   $str = $str . "maxlength = '".$maxlength."'";
  // }
  $str = $str. ' width ="100%" ';

  if(is_null($value)){
    $str = $str .' value = "" ';
  }else{
    $str = $str.' value ="'.$value.'"';
  }
  $str = $str.">";
  $str = $str.'</input>';

  return $str;

}
function getDataFromDbBySql($sql){
  $connection = getDbConnection();

  $statement=$connection->prepare($sql);
  $statement->execute();
  $output=$statement->fetchAll(PDO::FETCH_ASSOC);

  return $output;
}
function getMappingObjectFromJson($tableName,$tableColumnsArray){
  for ($i=0; $i < count($tableColumnsArray); $i++) {
    if ($tableColumnsArray[$i]["table"] == $tableName) {
      // $col = $tableColumnsArray[$i]["columns"];
      return $tableColumnsArray[$i];
    }
  }
}

function generateUpdateStatement(){

}
function updateOneField(){


}

function updateAllField(){

}
function SaveChangeLog(){
  //save every chang to log file at every save operation
  // revert ?
}


// function displayDataInTable($data){
//   //output the columes define and the table
//   $colDef = [];
//   foreach ($data[0] as $key => $value) {
//     // add class propertiy to colDef
//     array_push($colDef,$key);
//   }
//
//
//   $table = "";
//
//   $table=$table."<table border='1'>";
//   //cols def
//   $table = $table."<tr>";
//   for ($i=0; $i <count($colDef) ; $i++) {
//     $table = $table."<th>";
//     $table = $table.$colDef[$i];
//     $table = $table."</th>";
//   }
//
//   $table = $table."</tr>";
//   //loaded data here
//   for ($i=0; $i <count($data) ; $i++) {
//     $table = $table. "<tr>";
//     //every cols
//     for ($i=0; $i <count($colDef) ; $i++) {
//       $table = $table."<tr>";
//       // here read data according to the columes
//       //if it's key then disable to edit
//
//       $table = $table."</tr>";
//     }
//
//     $table = $table."</tr>";
//   }
//   echo $table;
//
// }
 ?>
