<?php

  /*
  This test case covers the emails. It does not actually send out an email, but
  it does it in a similar concept.
  */
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

  //now send emails between them
  sendEmail($mysqli, 'aaa', 'bbb', 'Invitation to the party', 'Hey bbb, just wanted to let you know I wanted to invite you to my party.');
  sleep(1); //for change timestamp
  sendEmail($mysqli, 'bbb', 'aaa', 'Re: Invitation to the party', 'I would be happy to join!');
  sleep(1); //for change timestamp
  sendEmail($mysqli, 'aaa', 'bbb', 'After-party', 'Hey bbb, what did you think of the party? I wish to hold another one soon.');

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


?>
