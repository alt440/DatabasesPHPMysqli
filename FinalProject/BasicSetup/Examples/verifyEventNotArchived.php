<?php

  /*
  This small script creates an event that is supposed to be archived by now.
  Then, it applies a verification to see if the event should be archived. If
  the event is supposed to be archived, the event (from the GUI) is blocked.
  */

  require "../database_layer.php";
  require "../database_layer_use_cases.php";
  require "../database_layer_get.php";

  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

	//select database
	$mysqli->select_db("comp353_final_project");

  //from addMemberToEvent.php
  $return_val=addUser($mysqli, 'aaa','aaa','a@a.com','abl arr','1777-04-05',0);
  echo $return_val."<br>";
  $return_val=addUser($mysqli, 'bbb','bbb','b@b.com','bbl brr','1777-04-25',0);
  echo $return_val."<br>";
  $return_val=addUser($mysqli, 'rrr','rrr','r@r.com','rrl rrr','1777-04-30',2);
  echo $return_val."<br>";
  $return_val=createEvent($mysqli, '1999-01-11','Some_Event','2009-01-22','family','aaa');
  echo $return_val."<br>";
  $return_val=confirmCreationEvent($mysqli, 'Some_Event');
  echo $return_val."<br>";

  //now verify that the event should be archived. isEventArchived returns 1 if it should, 0 if it should not.
  echo 'The event should be archived: '.isEventArchived($mysqli, 'Some_Event');


?>
