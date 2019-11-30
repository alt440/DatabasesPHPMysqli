<?php

  require "../database_layer.php";
  require "../database_layer_use_cases.php";
  require "../database_layer_get.php";

  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

	//select database
	$mysqli->select_db("comp353_final_project");

  //first, create the users
  $return_val=addUser($mysqli, 'aaa','aaa','a@a.com','abl arr','1777-04-05',0);
  echo $return_val."<br>";

  $return_val=addUser($mysqli, 'bbb','bbb','b@b.com','bbl brr','1777-04-25',0);
  echo $return_val."<br>";

  //then make an admin user
  $return_val=addUser($mysqli, 'rrr','rrr','r@r.com','rrl rrr','1777-04-30',2);
  echo $return_val."<br>";

  //let aaa create an event... sets status to 'pending' (1)
  $return_val=createEvent($mysqli, '1999-01-11','Some_Event','2009-01-22','family','aaa');
  echo $return_val."<br>";

  //once the event is created, admin can see that the event is in list. Validate
  //event creation. Sets status to '' (0) ONLY FOR ADMIN.
  $return_val=confirmCreationEvent($mysqli, 'Some_Event');
  echo $return_val."<br>";

  //then, event is public!
  //now, people can be added to the event. Add bbb to Some_Event (the event just created)
  //this can be a request from bbb to event manager, or event manager to bbb.
  $return_val=addUserToEvent($mysqli, 'bbb', 'Some_Event');
  echo $return_val."<br>";

  //show pending users and members of the group
  echo 'Members of the event Some_Event (1):'."<br>";
  $results = getEventMembers($mysqli, 'Some_Event');
  while($row = mysqli_fetch_row($results)){
    echo getUsername($mysqli, $row[0])."<br>";
  }

  echo 'Pending members of the event Some_Event (1):'."<br>";
  $results = getEventPendingUsers($mysqli, 'Some_Event');
  while($row = mysqli_fetch_row($results)){
    echo getUsername($mysqli, $row[0])."<br>";
  }

  //since the request to add bbb is pending, we have to make him a member of the
  //group. Either bbb or event manager will have to approve this.
  $return_val=setMemberToEvent($mysqli, 'bbb', 'Some_Event');
  echo $return_val."<br>";

  echo 'Members of the event Some_Event (2):'."<br>";
  $results = getEventMembers($mysqli, 'Some_Event');
  while($row = mysqli_fetch_row($results)){
    echo getUsername($mysqli, $row[0])."<br>";
  }
?>
