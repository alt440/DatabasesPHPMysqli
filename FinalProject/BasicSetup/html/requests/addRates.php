<?php

  require "../../database_layer_get.php";
  require "../../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //first make sure all input is included
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->nbEvents;
  $val2 = $decoded_json->eventType;
  $val3 = $decoded_json->storageGB;
  $val4 = $decoded_json->bandwidthGB;
  $val5 = $decoded_json->price;
  $val6 = $decoded_json->overflowBandwidth;
  $val7 = $decoded_json->overflowStorage;

  //$return_val = addRates($mysqli, $val1, $val3, $val4, $val2, $val5, $val7, $val6);
  //if(strlen($return_val)==0){
    echo json_encode(array("response" => 'OK'));
  //} else{
  //  echo json_encode(array("response" => $return_val));
  //}
?>
