<?php

  require "../../database_layer.php";
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val2 = $decoded_json->username;
  $password = $decoded_json->password;
  $email = $decoded_json->email;
  $name = $decoded_json->name;
  $dateofbirth = $decoded_json->dateofbirth;
  $privilegelevel = $decoded_json->privilegelevel;

  $return_val = doesUsernameExist($mysqli, $val2);
  if($return_val == 1){
    echo json_encode(array("response"=>"Username already exist. Select a different username."));
  } else if($dateofbirth[4] !='-' || $dateofbirth[7]!='-' || strlen($dateofbirth) !=10){
    echo json_encode(array("response"=>"Wrong date format: Must be YYYY-MM-DD."));
  } else if($privilegelevel>2 || $privilegelevel<0){
    echo json_encode(array("response"=>"Privilege must be 0 for user, 1 for controller, 2 for admin."));
  } else if (strstr($email, '@') == false){
    echo json_encode(array("response"=>"Email is in the wrong format. Should contain @."));
  } else{
    addUser($mysqli, $val2, $password, $email, $name, $dateofbirth, $privilegelevel);
    echo json_encode(array("response"=>"1"));
  }

?>
