<?php
  //Alexandre Therrien
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //editing user's email
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->accountnb;
  $val2 = $decoded_json->username;

  if(is_numeric($val1)){
    updateUserAccountNumber($mysqli, $val2, $val1);
    echo json_encode(array("response"=>"1"));
  } else{
    echo json_encode(array("response"=>"Wrong format. Account number contains non numeric characters."));
  }



?>
