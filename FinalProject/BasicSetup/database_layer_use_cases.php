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
  }

  /*
  This function sets a user as a member of the group, from its pending state.
  */
  function setMemberToGroup(){

  }

?>
