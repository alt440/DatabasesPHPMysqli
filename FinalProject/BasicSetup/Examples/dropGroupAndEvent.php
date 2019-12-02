<?php

  /*
  This file can be used after having run addContentToGroup.php so that
  a group and an event have already been created. This script will delete
  a group and then an event.
  */

  require "../database_layer.php";
  require "../database_layer_use_cases.php";
  require "../database_layer_get.php";
  require "../database_layer_delete.php";

  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

  //select database
  $mysqli->select_db("comp353_final_project");

  $return_val = removeGroup($mysqli, 'Org');
  echo $return_val."<br>";
  $return_val = removeEvent($mysqli, 'Some_Event');
  echo $return_val."<br>";

?>
