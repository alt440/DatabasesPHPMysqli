<?php

  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //editing user's email
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->new_password;//['email'];
  $val2 = $decoded_json->username;//['username'];
  $val3 = $decoded_json->old_password;


  $return_val = updateUserPassword($mysqli, $val2, $val3, $val1);
  echo json_encode(array("response"=>$return_val));

?>
