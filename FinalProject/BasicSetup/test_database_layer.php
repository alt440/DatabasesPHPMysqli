<?php

  require "database_layer.php";

  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

	//select database
	$mysqli->select_db("comp353_final_project");

  /*
  Adds user with username aaa, password aaa, email a@a.com, name abl arr,
  date of birth 1777-04-05, and privilege level 0 (basic user).
  Privilege level 1 would be the controller, and 2 would be the admin.
  */
  $return_val=addUser($mysqli, 'aaa','aaa','a@a.com','abl arr','1777-04-05',0);
  /*
  This will show me any error returned by my method.
  */
  echo $return_val."<br>";
  $return_val=addUser($mysqli, 'bbb','bbb','b@b.com','bbl brr','1777-04-25',0);
  echo $return_val."<br>";
  /*
  This is how to add an event type. However, some have already been added.
  A condition has been made in the database_layer.php (doesEventTypeExist method)
  to verify if the event type exists. Some event types have already been added
  by default (look at your Event_type table). You will have to modify the
  condition in the doesEventTypeExist method if you want to add any other (because
  I though the 4 I have set would suffice)
  */
  $return_val=addEventType($mysqli,'ordinary',0);
  echo $return_val."<br>";

  /*
  This returns an error because of the doesEventTypeExist method, as it does not
  contain the added event type (because I do not believe we would need any other)

  Sets a price (3.33$) for the number of events (5) that the user has created up
  to date, for the storage in GB (5) that the event takes, for the bandwidth in GB
  (5) that the event takes, for the event type ('ordinary') selected.
  */
  $return_val = addRates($mysqli,5,5,5,'ordinary',3.33);
  echo $return_val."<br>";
  $return_val = addRates($mysqli,5,5,5,'family',3.33);
  echo $return_val."<br>";

  /*
  This creates a new event with the title 'Origami2', with creation date 1999-01-11,
  with expiration date '1999-01-22', with event type 'family', and with the user
  that created with username 'aaa'. I need the username of the user that created
  it to add him/her as the admin of the new event. The event is under status
  'pending', because it needs to be validated by the administrator before showing
  up.
  */
  $return_val=createEvent($mysqli, '1999-01-11','Origami2','1999-01-22','family','aaa');
  echo $return_val."<br>";

  /*
  This is how to add a user to an event. However, the user is in a 'pending' state,
  which means that he not yet a part of the group. He still needs to get the
  request validated by the event manager. Or, if the event manager sent a request
  to the user, the user still needs to validate the request to be able to join the
  group.
  'bbb' is the username of the user, and 'Origami2' is the name of the Event
  */
  $return_val=addUserToEvent($mysqli, 'bbb','Origami2');
  echo $return_val."<br>";

  /*
  This is how to add a group to the event. However, this method below will not
  add a new group, because the user 'bbb' is still not part of the event. He is
  in a pending state, which does not allow him to create a group. Also, this will
  create the group as 'Private' (Privacy=0), because this group has not been
  validated by the Event Manager to be public on the Event page. If the group is
  Private, the conversation will only show on the home page of the users belonging
  to it, and not show on the home page of the Event. If the group is public, it
  will also show on the home page of the event. Finally, if all conditions pass,
  the user that created the group will be added as admin of the group.
  */
  $return_val=createGroup($mysqli, 'Origami2', 'BlackOrange2', 'bbb');
  echo $return_val."<br>";
  $return_val=createGroup($mysqli, 'Origami2', 'BlackOrange2', 'aaa');
  echo $return_val."<br>";

  /*
  This will add a user to the group in 'pending' state. Only a user that belongs
  to the Event can be added to a group of the Event. First parameter is the
  username of the user being added to the group, and the second parameter is the
  name of the group, the third is the name of the event (I have to make sure
  the user belongs to the event before he is added to the group).
  */
  $return_val=addUserToGroup($mysqli, 'aaa', 'BlackOrange2', 'Origami2');
  echo $return_val."<br>";

  //add to event page
  /*
  This will add some content to a certain event. This will not add it to a
  group, because only the name of the Event has been set (and not the name of the
  group). PermissionType is the second parameter to this function. Possible
  permission types: 0 - no comment, 1- comments, no links, 2-comments and links.
  Third parameter is the URI of the image (where it is located on the user's
  computer). Not working for now. Fourth parameter is the replyString of the
  content: The actual message. Fifth parameter is the title of the event, sixth
  parameter is the group name ('' means no group), and seventh parameter is the
  username of the user that published the content.
  */
  $return_val=addContent($mysqli, 1, '', 'Hello World4!', 'Origami2', '','aaa');
  echo $return_val."<br>";
  /*
  This adds a comment on a post made on the event page. Comments do not exist
  in groups; they are all content elements. This method gives us the replyString
  (the actual message), followed by the message of the post we want to comment
  on (this method is not perfect... could be errors if two posts have same
  content), followed by the username of the user that published the comment.
  Some comments may not be authorized because of the content's PermissionType
  value.
  */
  $return_val=addComment($mysqli, 'Hello aaa!','Hello World4!','aaa');
  echo $return_val."<br>";
  //add to group
  //addContent($mysqli, 0, '', 'Hello World2!', 'Origami', 'BlackOrange', 'aaa');
  //add a replyImage - NOT WORKING!
  //addContent($mysqli, 0, 'test_image_1.jpg', 'Hiya', 'Origami', 'BlackOrange','aaa');

  /*
  This will send an email from aaa to aaa, with title 'Hi boy!' and content
  (next param)
  */
  sendEmail($mysqli, 'aaa','aaa', 'Hi boy!','Hi boy! My name is aaa.');

?>
