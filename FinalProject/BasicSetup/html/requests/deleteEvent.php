<?php

  //this adds a comment to the list of comments for a post
  require "../../database_layer_delete.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //$username = $_REQUEST['username'];
  $eventID = $_REQUEST['eventID'];
  removeEventID($mysqli, $eventID);

?>
