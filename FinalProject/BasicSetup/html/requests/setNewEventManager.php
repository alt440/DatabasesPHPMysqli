<?php

  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $UID = $_REQUEST['UID'];
  $eventID = $_REQUEST['eventID'];

  setEventManager($mysqli, $eventID, $UID);
?>
