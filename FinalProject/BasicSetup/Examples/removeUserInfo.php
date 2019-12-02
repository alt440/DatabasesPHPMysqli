<?php

  /*
  This file removes the information on a user. Continuation on all the files that
  contribute to adding stuff to the aaa user.
  */

  require "../database_layer.php";
  require "../database_layer_use_cases.php";
  require "../database_layer_get.php";
  require "../database_layer_delete.php";

  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

	//select database
	$mysqli->select_db("comp353_final_project");

  //normally this function does not go before the next one, because this function
  //deletes all the membership from the groups that belong to a certain event.
  $return_val= removeUserFromEvent($mysqli, 'aaa', 'Some_Event');
  echo $return_val."<br>";

  $return_val= removeUserFromGroup($mysqli, 'aaa', 'Org');
  echo $return_val."<br>";

  $return_val = removeUser($mysqli, 'aaa');
  echo $return_val."<br>";

?>
