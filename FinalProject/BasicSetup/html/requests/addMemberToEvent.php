<?php
  //Alexandre Therrien
  require "../../database_layer_use_cases.php";
  require "../../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //editing user's email
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->UID;
  $val2 = $decoded_json->eventTitle;

  $return_val = setMemberToEventID($mysqli, $val1, $val2);

  //now send email to the user to say he/she got accepted to event
  /*$return_val = */sendEmailID($mysqli, $val1, $val1, 'Just got accepted to Event '.$val2.'!', 'You can now access the event '.$val2.'.');

  echo json_encode(array("response"=>$return_val));

?>
