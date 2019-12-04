<?php

  require "../../database_layer_delete.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val2 = $decoded_json->username;

  $return_val = removeUser($mysqli, $val2);

?>
