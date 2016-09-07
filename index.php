<?php
  // this file is create to provide a visible window so that admin manager can add and change the languge field in databasel

  //read table from the tablecolumnsmapping file and then use choose the table to load
  //if change something and want to save call save function add save to change log
  //if change finished can choose to hiden the element


 ?>
<!doctype html>
<html>
  <head>
    <title> update output_cols table</title>
      <!-- <link rel="stylesheet" href="./table.css"> -->
  </head>
  <body>
    <form name = "tableSelect" method = "post" >
      <select name = "tables">
        <?php  // loop array
          // require_once  __DIR__ . '/tableColumnsMapping.php';
          require_once  __DIR__ . '/parserJson.php';
          require_once  __DIR__ . '/operate.php';
          $tablecolumnsmapping = getTableArray();
          $tableName = "";

          for ($i=0; $i <count($tablecolumnsmapping) ; $i++) {
            $tableName =$tablecolumnsmapping[$i]["table"];

            echo "<option value = '".$tableName."'>".$tableName."</option>";
          }
        ?>
      </select>
      <input type  = "submit" name = "getTableContains" value ="Get data from database "/>
  </form>

  <!-- <input name="sds" value = "dasd"/> -->
  <?php
  // get all the data from the database add then display
  // echo "hello";
    if(isset($_POST['tables'])){
        // echo "hello";
      echo $_POST['tables'];
      $tableMappingObj = new stdClass();
      $tableMappingObj = getMappingObjectFromJson( $_POST['tables'],$tablecolumnsmapping);
      $data = loadData($_POST['tables'],$tablecolumnsmapping);


      displayDataInTable($data);
    }
  ?>
  </body>
</html>
