<?php

  //this adds a comment to the list of comments for a post
  require "../../database_layer.php";
  require "../../database_layer_get.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $usernamep1 = getUsername($mysqli,$_REQUEST['UID']);
  $usernamep2 = $_REQUEST['username'];
  $eventTitle = $_REQUEST['eventTitle'];

  //create a private group
  createGroupPrivate($mysqli, $eventTitle, "Group".$usernamep2.$usernamep1, $usernamep2);
  //now add the other one to the group
  addUserToGroup($mysqli, $usernamep1, "Group".$usernamep2.$usernamep1, $eventTitle);

?>
