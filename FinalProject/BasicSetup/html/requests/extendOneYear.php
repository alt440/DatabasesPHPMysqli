<?php

  //Alexandre Therrien
  require "../../database_layer.php";
  require "../../database_layer_use_cases.php";
  require "../../database_layer_get.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //now create user into group and change status to member
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->eventID;

  if(!is_numeric($val1) || !doesEventExistID($mysqli, $val1)){
    echo json_encode(array("response"=>"Event ID is not a numeric value or does not exist."));
  } else{
    //get event object
    $event = getEvent($mysqli, getEventTitle($mysqli, $val1)[0]);

    //get the date archived object
    $currentDatePlusOne = intval(date("Y"))+1;
    $dateExpired = $currentDatePlusOne.substr($event[4], 4);
    setEventExpiryDate($mysqli, $event[0], $dateExpired);
    echo json_encode(array("response"=>"1"));
  }

?>
