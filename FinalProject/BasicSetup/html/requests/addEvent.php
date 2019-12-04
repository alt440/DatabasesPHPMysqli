<?php

  //create an event
  require "../../database_layer_use_cases.php";
  require "../../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //first verify that all information is set before
  $username = $_REQUEST['username'];
  $eventType = $_REQUEST['eventType'];
  $eventTitle = $_REQUEST['eventTitle'];
  $templateNb = $_REQUEST['templateNb'];
  $result = verifyUserDetails($mysqli, $username);
  if($result != 1){
    echo json_encode(array("response"=>$result));
  } else{
    //now send the request to the admin
    //current date
    $time = convertTimeStampToDateStamp(time());
    //by default, event valid for 1 year
    $time_next_year = convertTimeStampToDateStamp(time()+31557600);

    //now create event
    createEvent($mysqli, $time, $eventTitle, $time_next_year, $eventType, $username, $templateNb);

    echo json_encode(array("response"=>1));
  }


?>
