<?php

  //this adds a comment to the list of comments for a post
  require "../../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $username = $_REQUEST['username'];
  $privilegelevel = $_REQUEST['privilegeLevel'];
  $replyString = $_REQUEST['replyString'];
  $eventTitle = $_REQUEST['eventTitle'];

  addContent($mysqli, $privilegelevel, '', $replyString, $eventTitle, '', $username);

?>
