<?php

  require "../../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $source = $_REQUEST['sourceUser'];
  $target = $_REQUEST['targetUser'];
  $title = $_REQUEST['titleEmail'];
  $content = $_REQUEST['contentEmail'];
  sendEmail($mysqli, $source, $target, $title, $content);

?>
