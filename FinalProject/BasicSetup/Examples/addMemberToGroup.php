<?php

  /*
  This file is an extension to addMemberToEvent.php. To understand uncommented
  code, please refer to addMemberToEvent.php.

  This script covers how to add a group and add new members to the group.
  */
  require "../database_layer.php";
  require "../database_layer_use_cases.php";
  //this displays our data.
  require "../database_layer_get.php";

  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

  //select database
  $mysqli->select_db("comp353_final_project");

  //adding a new user
  $return_val=addUser($mysqli, 'ccc','ccc','c@c.com','cbl crr','1777-12-05',0);
  echo $return_val."<br>";

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
  $return_val=addUserToEvent($mysqli, 'bbb', 'Some_Event');
  echo $return_val."<br>";
  $return_val=setMemberToEvent($mysqli, 'bbb', 'Some_Event');
  echo $return_val."<br>";

  $return_val=addUserToEvent($mysqli, 'ccc', 'Some_Event');
  echo $return_val."<br>";
  $return_val=setMemberToEvent($mysqli, 'ccc', 'Some_Event');
  echo $return_val."<br>";

  //now extend upon what was built
  //let bbb create a group inside of Some_Event with name Org.
  $return_val=createGroup($mysqli, 'Some_Event', 'Org', 'bbb');
  echo $return_val."<br>";
  //group is set to private, so it will not show on the event page as privacy=0
  //because the event manager has not approved it.
  //create pending user ccc.
  $return_val=addUserToGroup($mysqli, 'ccc', 'Org', 'Some_Event');
  echo $return_val."<br>";

  //show pending users and members of the group
  echo 'Members of the group Org (1):'."<br>";
  $results = getGroupMembers($mysqli, 'Org');
  while($row = mysqli_fetch_row($results)){
    echo getUsername($mysqli, $row[0])."<br>";
  }

  echo 'Pending members of the group Org (1):'."<br>";
  $results = getGroupPendingUsers($mysqli, 'Org');
  while($row = mysqli_fetch_row($results)){
    echo getUsername($mysqli, $row[0])."<br>";
  }

  //make ccc a member of the group. ccc will get added to the list.
  $return_val=setMemberToGroup($mysqli, 'ccc', 'Org');
  echo $return_val."<br>";

  //show members of the group
  echo 'Members of the group Org (2):'."<br>";
  //this returns me the UID of all members
  $results = getGroupMembers($mysqli, 'Org');
  while($row = mysqli_fetch_row($results)){
    echo getUsername($mysqli, $row[0])."<br>";
  }
?>
