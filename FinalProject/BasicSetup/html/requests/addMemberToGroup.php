<?php

  require "../../database_layer_use_cases.php";
  require "../../database_layer.php";
  require "../../database_layer_get.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  /*$json = '{
    "title": "PHP",
    "site": "GeeksforGeeks"
  }';*/

  //editing user's email
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->UID;
  $val2 = $decoded_json->groupID;

  $return_val = setMemberToGroupID($mysqli, getUsername($mysqli,$val1), $val2);

  //now send email to the user to say he/she got accepted to event
  sendEmailID($mysqli, $val1, $val1, 'Just got accepted to Group '.getGroupName($mysqli,$val2).'!', 'You can now access the group '.getGroupName($mysqli,$val2).'.');

  echo json_encode(array("response"=>$return_val));

?>
