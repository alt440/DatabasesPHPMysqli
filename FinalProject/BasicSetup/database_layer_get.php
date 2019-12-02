<?php

  /*
  This file has many functions used for extracting data from the database.
  Unlike the other database_layer file, this does not do any modification.
  */

  /*
  Shows emails destinated for the user
  $mysqli: Connection to the DB object
  $username: Username of the user

  Returns list of emails that were targeted to the user
  */
  function showEmailsReceived($mysqli, $username){
    //find if the user really exists
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' does not exist';
    }

    $result2 = $mysqli->query("SELECT SourceUser, TitleEmail, Content FROM Emails WHERE TargetUser=".$first_row[0]." ORDER BY TimeStamp DESC;");
    return $result2;
  }

  /*
  Shows emails sent by the user
  $mysqli: Connection to the DB object
  $username: Username of the user

  Returns list of emails that were sent by the user
  */
  function showEmailsSent($mysqli, $username){
    //find if the user really exists
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' does not exist';
    }

    $result2 = $mysqli->query("SELECT TargetUser, TitleEmail, Content FROM Emails WHERE SourceUser=".$first_row[0]." ORDER BY TimeStamp DESC;");
    return $result2;
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

  /*
  Get all content for a certain event
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event

  Returns the Content ID (CID) and the replyString of the post, along with the UID
  (User ID) of the person that did the post.
  */
  function getContentEvent($mysqli, $eventTitle){
    $result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'Event with title '.$eventTitle.' does not exist';
    }

    $result2 = $mysqli->query("SELECT Content.CID, Content.replyString, Post.UID FROM Content INNER JOIN Post ON Content.CID=Post.CID WHERE EventID=".$first_row[0]." AND GroupID IS NULL ORDER BY TimeStamp DESC;");
    return $result2;
  }

  /*
  Get all comments for a certain content post. Only for posts in Events.
  $mysqli: Connection to the DB object
  $CID: Content ID

  Returns the string and the UID (ID of user that did the post) of the post.
  */
  function getCommentsContent($mysqli, $CID){
    $result = $mysqli->query("SELECT Comment.replyString, Post_Comment.UID FROM Comment INNER JOIN Post_Comment ON Comment.CoID=Post_Comment.CoID WHERE CID=".$CID." ORDER BY TimeStamp ASC;");
    return $result;
  }

  /*
  Get all content for a certain group (already ordered correctly)
  $mysqli: Connection to the DB object
  $groupName: Name of the event

  Returns the strings and the UID (ID of user posting content)
  */
  function getContentGroup($mysqli, $groupName){
    //find the group
    $result = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'Group with name '.$groupName.' could not be found';
    }

    //now look through DB.
    $result2 = $mysqli->query("SELECT Content.replyString, Post.UID FROM Content INNER JOIN Post ON Content.CID=Post.CID WHERE GroupID=".$first_row[0]." ORDER BY TimeStamp ASC;");
    return $result2;
  }

  /*
  Get all the events a user belongs to.
  $mysqli: Connection to the DB object
  $username: Username of the user - User must exist!

  Returns table of events user belongs to
  */
  function getEventsOfUser($mysqli, $username){
    //find userID (UID)
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' was not found.';
    }

    //get all the titles of the events the user belongs to and the EventID of those
    $result2 = $mysqli->query("SELECT Event_.EventID, Event_.Title FROM Event_ INNER JOIN Is_Member_Event ON Is_Member_Event.EventID=Event_.EventID WHERE Is_Member_Event.UID=".$first_row[0].";");
    return $result2;
  }

  /*
  Get the latest post of the event
  $mysqli: Connection to the DB object
  $eventID: ID of the event
  */
  function getLatestPostEvent($mysqli, $eventID){
    $result = $mysqli->query("SELECT replyString, MAX(TimeStamp) FROM Content WHERE EventID=".$eventID." AND GroupID IS NULL GROUP BY replyString;");
    return $result;
  }

  /*
  Get all the groups the user belongs to
  $mysqli: Connection to the DB object
  $username: Username of the user

  Returns the GroupIDs and the GroupNames of the groups the user belongs to.
  */
  function getGroupsOfUser($mysqli, $username){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' was not found.';
    }

    $result2 = $mysqli->query("SELECT Group_.GroupID, Group_.GroupName FROM Group_ INNER JOIN Is_Member_Group ON Is_Member_Group.GroupID=Group_.GroupID WHERE Is_Member_Group.UID=".$first_row[0].";");
    return $result2;
  }

  /*
  Get the latest post of the group
  $mysqli: Connection to the DB object
  $groupID: ID of the group

  Returns the replyString that has the greatest timestamp value (most recent post)
  */
  function getLatestPostGroup($mysqli, $groupID){
    $result = $mysqli->query("SELECT replyString, MAX(TimeStamp) FROM Content WHERE GroupID=".$groupID." GROUP BY replyString;");
    return $result;
  }

  /*
  Get all user info based on username
  $mysqli: Connection to the DB object
  $username: Username of the user
  Returns all the user info in a 1D array (can refer as $first_row[0],... look
  in DB for order of variables)
  */
  function getUser($mysqli, $username){
    $result = $mysqli->query("SELECT * FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);
    return $first_row;
  }


?>
