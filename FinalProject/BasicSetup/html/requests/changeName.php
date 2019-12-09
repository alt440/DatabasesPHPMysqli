<?php

  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  /*$json = '{
    "title": "PHP",
    "site": "GeeksforGeeks"
  }';*/

  //editing user's email
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->name;//['email'];
  $val2 = $decoded_json->username;//['username'];

  //check if name contains only alpha chars
  if(ctype_alpha($val1)){
    updateUser_Name($mysqli, $val2, $val1);
    echo json_encode(array("response"=>"1"));
  } else{
    echo json_encode(array("response"=>"Wrong format. Name contains non-alphabetic characters."));
  }



?>
