<?php
  //Alexandre Therrien
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //editing user's email
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->bankname;//['email'];
  $val2 = $decoded_json->username;//['username'];

  updateUserBankName($mysqli, $val2, $val1);

?>
