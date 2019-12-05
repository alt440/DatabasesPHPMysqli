<?php

  require "../../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $username = $_REQUEST['username'];
  $groupName = $_REQUEST['groupName'];
  $eventTitle = $_REQUEST['eventTitle'];

  addUserToGroup($mysqli, $username, $groupName, $eventTitle);

?>
