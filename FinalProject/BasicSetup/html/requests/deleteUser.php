<?php

  require "../../database_layer_delete.php";
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val2 = $decoded_json->username;

  $return_val = doesUsernameExist($mysqli, $val2);

  if($return_val == 1){
    removeUser($mysqli, $val2);
    echo json_encode(array("response"=>"1"));
  } else{
    echo json_encode(array("response"=>"User with username ".$val2." does not exist."));
  }

?>
