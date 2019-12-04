<?php
  session_start();
  //this adds a comment to the list of comments for a post
  require "../../database_layer_get.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $username = getUsername($mysqli, $_REQUEST['UID']);

  $_SESSION['searchUser']=$username;

?>
