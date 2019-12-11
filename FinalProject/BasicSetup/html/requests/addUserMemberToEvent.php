<?php

  //Alexandre Therrien
  //admin functionality
  require "../../database_layer.php";
  require "../../database_layer_use_cases.php";
  require "../../database_layer_get.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //now create user into group and change status to member
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->UID;
  $val2 = $decoded_json->eventID;

  if(!is_numeric($val1) || !doesUIDExist($mysqli, $val1)){
    echo json_encode(array("response"=>"UID is not a numeric value or does not exist."));
  } else if(!is_numeric($val2) || !doesEventExistID($mysqli, $val2)){
    echo json_encode(array("response"=>"Event ID is not a numeric value or does not exist."));
  } else{
    $eventTitle = getEventTitle($mysqli, $val2);

    addUserToEvent($mysqli, getUsername($mysqli, $val1), $eventTitle[0]);
    setMemberToEventID($mysqli, $val1, $eventTitle[0]);
    echo json_encode(array("response"=>"1"));
  }

?>
