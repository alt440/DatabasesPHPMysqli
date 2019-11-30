<?php

  require "database_layer.php";

  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

	//select database
	$mysqli->select_db("comp353_final_project");

  addUser($mysqli, 'aaa','aaa','a@a.com','abl arr','1777-04-05',0);
  /*addEventType($mysqli,'ordinary',0);
  $return_val = addRates($mysqli,5,5,5,'ordinary',3.33);
  echo $return_val;
  addRates($mysqli,5,5,5,'family',3.33);*/

  createEvent($mysqli, '1999-01-11','Origami','1999-01-22','family');
  addMemberToEvent($mysqli, 'aaa','Origami');

  createGroup($mysqli, 'Origami', 'BlackOrange');
  addMemberToGroup($mysqli, 'aaa', 'BlackOrange');

  //add to event page
  addContent($mysqli, 1, '', 'Hello World4!', 'Origami', '','aaa');
  addComment($mysqli, 'Hello aaa!','Hello World4!','aaa');
  //add to group
  //addContent($mysqli, 0, '', 'Hello World2!', 'Origami', 'BlackOrange', 'aaa');
  //add a replyImage - NOT WORKING!
  //addContent($mysqli, 0, 'test_image_1.jpg', 'Hiya', 'Origami', 'BlackOrange','aaa');

?>
