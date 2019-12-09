<?php

  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //editing user's email
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->phonenumber;//['email'];
  $val2 = $decoded_json->username;//['username'];

  if(is_numeric($val1) && strlen($val1)>=10){
    updateUserPhoneNumber($mysqli, $val2, $val1);
    echo json_encode(array("response"=>"1"));
  } else if(strlen($val1)<10){
    echo json_encode(array("response"=>"Phone number is too short!"));
  } else{
    echo json_encode(array("response"=>"Wrong format. Phone number must contain only numbers."));
  }



?>
