<?php

  require "../../database_layer_delete.php";
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //first make sure all input is included
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->RID;

  $return_val = doesRIDExist($mysqli, $val1);

  if($return_val == 1){
    deleteRates($mysqli, $val1);
    echo json_encode(array("response" => "1"));
  } else{
    echo json_encode(array("response" => "Could not find Rates having the ID ".$val1."."));
  }

?>
