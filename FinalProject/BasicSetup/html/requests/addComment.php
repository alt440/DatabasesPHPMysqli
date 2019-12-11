<?php
  //Alexandre Therrien
  //this adds a comment to the list of comments for a post
  require "../../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $username = $_REQUEST['username'];
  $CID = $_REQUEST['CID'];
  $replyString = $_REQUEST['replyString'];

  addCommentCID($mysqli, $replyString, $CID, $username);

?>
