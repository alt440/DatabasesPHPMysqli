<?php

  /*
  This file is an extension to addMemberToGroup.php. To understand uncommented
  code, please refer to addMemberToGroup.php.

  This script covers how to add content to the group and display it.
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
  $return_val=createEvent($mysqli, '1999-01-11','Some_Event','2009-01-22','family','aaa', 1);
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
  $return_val=createGroup($mysqli, 'Some_Event', 'Org', 'bbb');
  echo $return_val."<br>";
  $return_val=addUserToGroup($mysqli, 'ccc', 'Org', 'Some_Event');
  echo $return_val."<br>";
  $return_val=setMemberToGroup($mysqli, 'ccc', 'Org');
  echo $return_val."<br>";

  //starting here: from here, ccc and bbb are members of group Org, belonging in
  //event Some_Event. Privilege Level set here does not matter: always considered
  //as 0.
  addContent($mysqli, 0, '', 'Howdy yall! Zis my new group', 'Some_Event','Org','bbb');
  sleep(1); //for different timestamp
  addContent($mysqli, 0, '', 'Hi bbb! Glad that you added me to the group.', 'Some_Event', 'Org', 'ccc');
  sleep(1); //for different timestamp
  addContent($mysqli, 0, '', 'Couldnt do it without you ccc!', 'Some_Event', 'Org', 'bbb');

  //now get the content of the group conversation
  $content = getContentGroup($mysqli, 'Org');
  while($row = mysqli_fetch_row($content)){
    echo getUsername($mysqli, $row[1]).' '.$row[0]."<br>";
  }

  //add aaa for the getHomePageUser.php script
  $return_val=addUserToGroup($mysqli, 'aaa', 'Org', 'Some_Event');
  echo $return_val."<br>";
  $return_val=setMemberToGroup($mysqli, 'aaa', 'Org');
  echo $return_val."<br>";

?>
