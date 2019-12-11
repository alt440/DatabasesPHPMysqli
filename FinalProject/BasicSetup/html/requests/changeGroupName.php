<?php
  //Alexandre Therrien
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $groupID = $decoded_json->groupID;
  $newGroupName = $decoded_json->groupName;

  changeGroupName($mysqli, $groupID, $newGroupName);

?>
