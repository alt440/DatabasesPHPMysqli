<?php
  //Alexandre Therrien
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $UID = $_REQUEST['UID'];
  $eventID = $_REQUEST['eventID'];

  //does user exist? does event exist?
  $return_val_0 = doesUIDExist($mysqli, $UID);
  $return_val_1 = doesEventExistID($mysqli, $eventID);

  if($return_val_0 == 1 && $return_val_1 == 1){
    setEventManager($mysqli, $eventID, $UID);
    echo json_encode(array("response"=>"1"));
  } else if($return_val_1 == 0){
    echo json_encode(array("response"=>"Could not find event with ID ".$eventID."."));
  } else if($return_val_0 == 0){
    echo json_encode(array("response"=>"Could not find user with ID ".$UID."."));
  }


?>
