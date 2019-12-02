<?php

  /*
  This script shows the events, the groups, and the emails that make the user
  account. This is shown through their home page. Other users cannot view their
  emails...

  You should see content for each of the categories if you run:
  -sendEmails.php
  -addContentToEvent.php
  -addContentToGroup.php
  */

  require "../database_layer.php";
  require "../database_layer_use_cases.php";
  require "../database_layer_get.php";

  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

	//select database
	$mysqli->select_db("comp353_final_project");

  //I ran sendEmails.php before, which is why I have content below.

  //now get the emails (those sent by aaa, and those received by aaa)
  $received = showEmailsReceived($mysqli, 'aaa');
  echo 'Emails received by aaa: '."<br>";
  echo 'Source User | Title Email | Content'."<br>";
  while($row = mysqli_fetch_row($received)){
    echo getUsername($mysqli, $row[0]).' | '.$row[1].' | '.$row[2]."<br>";
  }

  echo "<br>";

  $sent = showEmailsSent($mysqli, 'aaa');
  echo 'Emails sent by aaa: '."<br>";
  echo 'Target User | Title Email | Content'."<br>";
  while($row = mysqli_fetch_row($sent)){
    echo getUsername($mysqli, $row[0]).' | '.$row[1].' | '.$row[2]."<br>";
  }

  echo "<br>";

  //now show the events that user belongs to
  $events = getEventsOfUser($mysqli, 'aaa');
  echo 'Events that user aaa belongs to: '."<br>";
  //now also get their latest posts
  while($row = mysqli_fetch_row($events)){
    $latest_post = getLatestPostEvent($mysqli, $row[0]);
    $first_row = mysqli_fetch_row($latest_post);
    //look at whether there is an error (or no data received) by looking at if the result is a boolean
    if(!is_bool($first_row[0])){
      echo $row[1].' '.$first_row[0]."<br>";
    } else{
      echo $row[1]."<br>";
    }
  }

  echo "<br>";

  $groups = getGroupsOfUser($mysqli, 'aaa');
  echo 'Groups that user aaa belongs to: '."<br>";
  //now also get their latest posts
  while($row = mysqli_fetch_row($groups)){
    $latest_post = getLatestPostGroup($mysqli, $row[0]);
    $first_row = mysqli_fetch_row($latest_post);
    //look at whether there is an error (or no data received) by looking at if the result is a boolean
    if(!is_bool($first_row[0])){
      echo $row[1].' '.$first_row[0]."<br>";
    } else{
      echo $row[1]."<br>";
    }
  }



?>
