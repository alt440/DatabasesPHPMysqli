<?php

  /*
  This file only contains the command that will allow us to see the groups that belong
  to a certain event. Make sure you have run addMemberToGroup.php before to see some
  results below.
  */

  require "../database_layer.php";
  require "../database_layer_use_cases.php";
  require "../database_layer_get.php";

  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

	//select database
	$mysqli->select_db("comp353_final_project");

  $return_val = getGroupsInEvent($mysqli, 'Some_Event');
  echo 'Groups in Some_Event: '."<br>";
  while($row=mysqli_fetch_row($return_val)){
    echo $row[0]."<br>";
  }



?>
