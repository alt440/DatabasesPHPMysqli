<?php

  //this adds a comment to the list of comments for a post
  require "../../database_layer_delete.php";
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //$username = $_REQUEST['username'];
  $eventID = $_REQUEST['eventID'];

  $result_val = doesEventExistID($mysqli, $eventID);
  if($result_val == 1){
    removeEventID($mysqli, $eventID);
    echo json_encode(array("response"=>"1"));
  } else{
    echo json_encode(array("response"=>"Could not find event with ID ".$eventID."."));
  }


?>
