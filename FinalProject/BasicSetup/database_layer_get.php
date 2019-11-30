<?php

  /*
  This file has many functions used for extracting data from the database.
  Unlike the other database_layer file, this does not do any modification.
  */

  /*
  Shows emails destinated for the user
  */
  function showEmailsReceived(){

  }

  /*
  Shows emails sent by the user
  */
  function showEmailsSent(){

  }

  /*
  Shows group conversation
  */
  function showGroupConversation(){

  }

  /*
  Shows event conversation (also applies to comments)
  */
  function showEventConversation(){

  }

  /*
  Shows the members of an event
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event - Must exist!

  Returns: result from mysqli query giving all UIDs of the members (includes admin!)
  */
  function getEventMembers($mysqli, $eventTitle){
    //find eventID
    $result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'Event with title '.$eventTitle.' does not exist.';
    }

    //get UIDs of the people that belong to the event
    $result2 = $mysqli->query("SELECT UID FROM Is_Member_Event WHERE EventID=".$first_row[0]." AND (requestStatus='member' OR requestStatus='admin')");
    return $result2;
  }

  /*
  Get users UID of all pending users (all of those which want to join the event
  or have been invited by the event manager but are not members yet)
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event - Must exist!

  Returns: result from mysqli query giving all UIDs of the pending users
  */
  function getEventPendingUsers($mysqli, $eventTitle){
    //find eventID
    $result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'Event with title '.$eventTitle.' does not exist.';
    }

    //get UIDs of the people that belong to the event
    $result2 = $mysqli->query("SELECT UID FROM Is_Member_Event WHERE EventID=".$first_row[0]." AND requestStatus='pending'");
    return $result2;
  }

  /*
  Shows the members of a group
  $mysqli: Connection to the DB object
  $groupName: Name of the group

  Returns: result from mysqli query giving all UIDs of the members (includes admin!)
  */
  function getGroupMembers($mysqli, $groupName){
    //find groupID
    $result = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'Group with group name '.$groupName.' does not exist.';
    }

    //get UIDs of the people that belong to the group.
    $result2 = $mysqli->query("SELECT UID FROM Is_Member_Group WHERE GroupID=".$first_row[0]." AND (requestStatus='member' OR requestStatus='admin');");
    return $result2;
  }

  /*
  Get users UID of all pending users (all of those which want to join the group
  or have been invited by the group manager but are not members yet)
  $mysqli: Connection to the DB object
  $groupName: Name of the group

  Returns: result from mysqli query giving all UIDs of the pending users
  */
  function getGroupPendingUsers($mysqli, $groupName){
    //find groupID
    $result = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'Group with group name '.$groupName.' does not exist.';
    }

    //get UIDs of the people that belong to the group.
    $result2 = $mysqli->query("SELECT UID FROM Is_Member_Group WHERE GroupID=".$first_row[0]." AND requestStatus='pending';");
    return $result2;
  }

  /*
  This function gives us the username of a user based on their UIDs.
  $mysqli: Connection to the DB object
  $UID: UID of the user we want to get the username of

  Returns false if the UID does not exist.
  */
  function getUsername($mysqli, $UID){
    $result = $mysqli->query("SELECT Username FROM User_ WHERE UID=".$UID.";");
    $first_row = mysqli_fetch_row($result);
    return $first_row[0];
  }

?>
