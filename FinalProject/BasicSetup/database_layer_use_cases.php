<?php

  /*
  This file has the functions that will be used for the different test cases.
  */

  /*
  Privacy is 0 when group is private (no showing)
  Privacy is 1 when group is public (can show) - happens when a request is
  sent to the admin of the event and he/she approves creation of group.
  */

  /*
  This happens when a user creates an event. He/she needs confirmation from
  the administrator to be able to get a page for the event. ONLY ADMIN CAN DO
  THIS.
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event that we want to be active
  */
  function confirmCreationEvent($mysqli, $eventTitle){
    //set status to 0 to put the event active.
    $mysqli->query("UPDATE Event_ SET Status=0 WHERE Title='".$eventTitle."';");
    return $mysqli->error;
  }

  /*
  This function sets a user as a member of the event, from its pending state.
  $mysqli: Connection to the DB object
  $username: Username of the user that we want to join the event.
  $eventTitle: Title of the event we want to set the user a member to.
  */
  function setMemberToEvent($mysqli, $username, $eventTitle){
    //find userID
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' does not exist';
    }

    $result2 = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row_2 = mysqli_fetch_row($result2);

    if(is_bool($first_row_2[0])){
      return 'Event with event title '.$eventTitle.' does not exist.';
    }

    $mysqli->query("UPDATE Is_Member_Event SET requestStatus='member' WHERE UID=".$first_row[0]." AND EventID=".$first_row_2[0].";");
    return $mysqli->error;
  }

  /*
  This function sets a user as a member of the group, from its pending state.
  ONLY AVAILABLE TO GROUP ADMIN(s)
  $mysqli: Connection to the DB object
  $username: Username of the user that we want to join the group.
  $groupName: Name of the group that the user is going to join
  */
  function setMemberToGroup($mysqli, $username, $groupName){
    //find userID
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' does not exist';
    }

    //find GroupID
    $result2 = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");
    $first_row_2 = mysqli_fetch_row($result2);

    if(is_bool($first_row_2[0])){
      return 'Group with group name '.$groupName.' does not exist.';
    }

    $mysqli->query("UPDATE Is_Member_Group SET requestStatus='member' WHERE UID=".$first_row[0]." AND GroupID=".$first_row_2[0].";");
    return $mysqli->error;
  }

  /*
  Converts a date stamp to a timestamp: 'YYYY-MM-DD' to a long
  $dateStamp: Date in format 'YYYY-MM-DD'
  Returns long
  */
  function convertDateStampToTimeStamp($dateStamp){
    $a = strptime($dateStamp, '%Y-%m-%d');
    return mktime(0, 0, 0, $a['tm_mon']+1, $a['tm_mday'], $a['tm_year']+1900);
  }

  /*
  Converts a timestamp to a date stamp: long to a 'YYYY-MM-DD'
  $timestamp: Long representing the time in seconds since 1970
  Returns 'YYYY-MM-DD'
  */
  function convertTimeStampToDateStamp($timestamp){
    return date('Y-m-d', $timestamp);
  }

  /*
  Verify validity of the event (if it is archived)
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event - Must exist
  */
  function isEventArchived($mysqli, $eventTitle){
    $result = $mysqli->query("SELECT ExpiryDate FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'Event with title '.$eventTitle.' was not found.';
    }

    //current time
    $timestamp = time();

    //convert string to timestamp
    $event_timestamp = convertDateStampToTimeStamp($first_row[0]);

    return $event_timestamp < $timestamp;
  }

?>
