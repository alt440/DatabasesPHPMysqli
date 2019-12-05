<?php

  require "../../database_layer_delete.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //first make sure all input is included
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->RID;

  deleteRates($mysqli, $val1);
  echo json_encode(array("response" => 'OK'));
?>
