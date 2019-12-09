<?php

  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->eventID;

  //make sure event exists
  $return_val = doesEventExistID($mysqli, $val1);
  if($return_val == 1){
    setEventActive($mysqli, $val1);
    echo json_encode(array("response"=>"1"));
  } else{
    echo json_encode(array("response"=>"Could not find event with ID ".$val1."."));
  }



?>
