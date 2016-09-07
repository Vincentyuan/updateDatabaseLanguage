<?php

function getTableArray(){
  $file = "tableColumnsMapping.json";

  $string = file_get_contents($file);
  $arrays = json_decode($string,true);
  return $arrays;
}
?>
