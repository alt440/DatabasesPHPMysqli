<?php
  //Alexandre Therrien
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

  $return_val_0 = is_numeric($val1) && $val1>=0?1:0;
  $return_val_1 = is_numeric($val3) && $val3>0?1:0;
  $return_val_2 = is_numeric($val4) && $val4>0?1:0;
  $return_val_3 = is_numeric($val5) && $val5>0?1:0;
  $return_val_4 = is_numeric($val6) && $val6>0?1:0;
  $return_val_5 = is_numeric($val7) && $val7>0?1:0;

  if($return_val_0 == 1 && $return_val_1 == 1 && $return_val_2 == 1 &&
  $return_val_3 == 1 && $return_val_4 == 1 && $return_val_5 == 1){
    addRates($mysqli, $val1, $val3, $val4, $val2, $val5, $val7, $val6);
    echo json_encode(array("response" => "1"));
  } else if($return_val_0 == 0){
    echo json_encode(array("response"=>"The number of events is non numeric or lower than 0."));
  } else if($return_val_1 == 0){
    echo json_encode(array("response"=>"The storage in GB is non numeric or lower or equal to 0."));
  } else if($return_val_2 == 0){
    echo json_encode(array("response"=>"The bandwidth in GB is non numeric or lower or equal to 0."));
  } else if($return_val_3 == 0){
    echo json_encode(array("response"=>"The price is non numeric or lower or equal to 0."));
  } else if($return_val_4 == 0){
    echo json_encode(array("response"=>"The overflow bandwidth price is non numeric or lower or equal to 0."));
  } else if($return_val_5 == 0){
    echo json_encode(array("response"=>"The overflow storage price is non numeric or lower or equal to 0."));
  }
?>
