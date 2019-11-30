<?php

  /*
  This script is a continuation of the addMemberToEvent.php script.
  Some lines will not be explained. Go to addMemberToEvent.php to get more
  explanations.
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
  $return_val=addUserToEvent($mysqli, 'bbb', 'Some_Event');
  echo $return_val."<br>";

  //now add some posts on the event page
  /*
  this one cannot be commented on - privilege level 0
  no images in the post - replyImage is set to ''
  not sent to a group - groupName set to ''

  For some reason, replyString cannot contain any special characters
  */
  addContent($mysqli, 0, '', 'Howdy yall! Zis my new event', 'Some_Event','','aaa');
  sleep(1);//for different timestamp
  addContent($mysqli, 1, '', 'So how about we set this up for the 5th of December?', 'Some_Event','','aaa');
  sleep(1);//for different timestamp
  addContent($mysqli, 1, '', 'Hail is the worst!', 'Some_Event','','aaa');

  //now add some comments. cannot comment on the first post because it has
  //PermissionType=0, but can comment on the two next ones (no link though)
  //essentially, give the text of the content you want to comment on, the comment
  //you want to give on the content, and the username of the person sending the comment.
  $result_val=addComment($mysqli, 'Sounds good to me!', 'So how about we set this up for the 5th of December?', 'bbb');
  echo $return_val."<br>";
  sleep(1); //for different timestamp
  $result_val=addComment($mysqli, 'But it would need to be in the evening', 'So how about we set this up for the 5th of December?', 'bbb');
  echo $return_val."<br>";

  echo "<br>";
  //now show all of the text (username, text) - indented if comment
  $allContent = getContentEvent($mysqli, 'Some_Event');
  while($row = mysqli_fetch_row($allContent)){

    echo getUsername($mysqli, $row[2]).' '.$row[1]."<br>";
    $allComments = getCommentsContent($mysqli, $row[0]);
    while($row_c = mysqli_fetch_row($allComments)){
      echo "________".getUsername($mysqli, $row_c[1]).' '.$row_c[0]."<br>";
    }
  }


?>
