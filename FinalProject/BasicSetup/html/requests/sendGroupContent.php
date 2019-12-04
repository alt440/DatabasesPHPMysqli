<?php

  require "../../database_layer_get.php";
  require "../../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $json = $_POST['json'];
  $decoded_json = json_decode($json);

  $val1 = $decoded_json->username;
  $val2 = $decoded_json->eventID;
  $val3 = $decoded_json->groupID;
  $val4 = $decoded_json->replyString;

  $return_val = addContentWithIDs($mysqli, 0, '', $val4, $val2, $val3, $val1);
  echo json_encode(array("response"=>$return_val));
  //echo $val1.' '.$val2.' '.$val3.' '.$val4;
?>
