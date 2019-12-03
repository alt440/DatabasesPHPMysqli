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
  $val1 = $decoded_json->email;//['email'];
  $val2 = $decoded_json->username;//['username'];

  updateUserEmail($mysqli, $val2, $val1);

?>
