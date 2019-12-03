<?php

  require "../../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //processes a join request from the event page
  //get the request parameters (username, eventTitle)

  $username = $_REQUEST["username"];
  $eventTitle = $_REQUEST["eventTitle"];
  //then do the function
  addUserToEvent($mysqli, $username, $eventTitle);

?>
