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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'showEmailsReceived: User with username '.$username.' does not exist';
    }
    $first_row = mysqli_fetch_row($result);


    $result2 = $mysqli->query("SELECT SourceUser, TitleEmail, Content, EID FROM Emails WHERE TargetUser=".$first_row[0]." ORDER BY TimeStamp DESC;");
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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'showEmailsSent: User with username '.$username.' does not exist';
    }
    $first_row = mysqli_fetch_row($result);

    $result2 = $mysqli->query("SELECT TargetUser, TitleEmail, Content, EID FROM Emails WHERE SourceUser=".$first_row[0]." ORDER BY TimeStamp DESC;");
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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getEventMembers: Event with title '.$eventTitle.' does not exist.';
    }
    $first_row = mysqli_fetch_row($result);

    //get UIDs of the people that belong to the event
    $result2 = $mysqli->query("SELECT UID,requestStatus FROM Is_Member_Event WHERE EventID=".$first_row[0]." AND (requestStatus='member' OR requestStatus='admin')");
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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getEventPendingUsers: Event with title '.$eventTitle.' does not exist.';
    }
    $first_row = mysqli_fetch_row($result);

    //get UIDs of the people that belong to the event. Use OneTimeCode when inviting someone else to the event.
    $result2 = $mysqli->query("SELECT UID FROM Is_Member_Event WHERE EventID=".$first_row[0]." AND requestStatus='pending' AND OneTimeCode IS NULL");
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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getGroupMembers: Group with group name '.$groupName.' does not exist.';
    }
    $first_row = mysqli_fetch_row($result);

    //get UIDs of the people that belong to the group.
    $result2 = $mysqli->query("SELECT UID, requestStatus FROM Is_Member_Group WHERE GroupID=".$first_row[0]." AND (requestStatus='member' OR requestStatus='admin');");
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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getGroupPendingUsers: Group with group name '.$groupName.' does not exist.';
    }
    $first_row = mysqli_fetch_row($result);

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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getUsername: Could not find username of user with ID '.$UID.'.';
    }
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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getContentEvent: Event with title '.$eventTitle.' does not exist';
    }
    $first_row = mysqli_fetch_row($result);



    $result2 = $mysqli->query("SELECT Content.CID, Content.replyString, Post.UID, Content.TimeStamp, Content.PermissionType FROM Content INNER JOIN Post ON Content.CID=Post.CID WHERE EventID=".$first_row[0]." AND GroupID IS NULL ORDER BY TimeStamp DESC;");
    return $result2;
  }

  /*
  Get all comments for a certain content post. Only for posts in Events.
  $mysqli: Connection to the DB object
  $CID: Content ID

  Returns the string and the UID (ID of user that did the post) of the post.
  */
  function getCommentsContent($mysqli, $CID){
    $result = $mysqli->query("SELECT Comment.replyString, Post_Comment.UID, Comment.TimeStamp FROM Comment INNER JOIN Post_Comment ON Comment.CoID=Post_Comment.CoID WHERE CID=".$CID." ORDER BY TimeStamp ASC;");
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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getContentGroup: Group with name '.$groupName.' could not be found';
    }
    $first_row = mysqli_fetch_row($result);



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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getEventsOfUser: User with username '.$username.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);



    //get all the titles of the events the user belongs to and the EventID of those
    $result2 = $mysqli->query("SELECT Event_.EventID, Event_.Title, Is_Member_Event.requestStatus FROM Event_ INNER JOIN Is_Member_Event ON Is_Member_Event.EventID=Event_.EventID WHERE Is_Member_Event.UID=".$first_row[0]." AND (Is_Member_Event.requestStatus='member' OR Is_Member_Event.requestStatus='admin');");
    return $result2;
  }

  /*
  Get the latest post of the event
  $mysqli: Connection to the DB object
  $eventID: ID of the event
  */
  function getLatestPostEvent($mysqli, $eventID){
    $result = $mysqli->query("SELECT replyString, MAX(TimeStamp) FROM Content WHERE EventID=".$eventID." AND GroupID IS NULL GROUP BY replyString;");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    $first_row = mysqli_fetch_row($result);
    return $first_row;
  }

  /*
  Get all the groups the user belongs to
  $mysqli: Connection to the DB object
  $username: Username of the user

  Returns the GroupIDs and the GroupNames of the groups the user belongs to.
  */
  function getGroupsOfUser($mysqli, $username){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getGroupsOfUser: User with username '.$username.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);



    $result2 = $mysqli->query("SELECT Group_.GroupID, Group_.GroupName, Group_.MainEventID, Is_Member_Group.requestStatus FROM Group_ INNER JOIN Is_Member_Group ON Is_Member_Group.GroupID=Group_.GroupID WHERE Is_Member_Group.UID=".$first_row[0]." AND (Is_Member_Group.requestStatus='member' OR Is_Member_Group.requestStatus='admin');");
    return $result2;
  }

  /*
  Get the latest post of the group
  $mysqli: Connection to the DB object
  $groupID: ID of the group

  Returns the replyString that has the greatest timestamp value (most recent post)
  */
  function getLatestPostGroup($mysqli, $groupID){
    $result = $mysqli->query("SELECT MAX(TimeStamp) FROM Content WHERE GroupID=".$groupID.";");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    $first_row = mysqli_fetch_row($result);
    $result2 = $mysqli->query("SELECT replyString, TimeStamp FROM Content WHERE GroupID=".$groupID." AND TimeStamp=".$first_row[0].";");
    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 0;
    }
    $first_row_2 = mysqli_fetch_row($result2);
    return $first_row_2;
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
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    $first_row = mysqli_fetch_row($result);
    return $first_row;
  }

  /*
  Get all user info based on username
  $mysqli: Connection to the DB object
  $UID: User ID
  Returns all the user info in a 1D array (can refer as $first_row[0],... look
  in DB for order of variables)
  */
  function getUserWithID($mysqli, $UID){
    $result = $mysqli->query("SELECT * FROM User_ WHERE UID='".$UID."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    $first_row = mysqli_fetch_row($result);
    return $first_row;
  }

  /*
  Gets all the groups that belong to an event
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event

  Return all the group names that belong to the event.
  */
  function getGroupsInEvent($mysqli, $eventTitle){
    //first find event
    $result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getGroupsInEvent: Event with title '.$eventTitle.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);

    //now find all groups
    $result2 = $mysqli->query("SELECT Group_.GroupName, Group_.GroupID, Is_Member_Group.UID FROM Group_ INNER JOIN Is_Member_Group ON Is_Member_Group.GroupID=Group_.GroupID WHERE Group_.MainEventID=".$first_row[0]." AND Is_Member_Group.requestStatus='admin' AND Group_.Privacy = 0;");
    return $result2;
  }

  /*
  Gets all the groups that belong to an event
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event

  Return all the group names that belong to the event.
  */
  function getGroupsInEventHidden($mysqli, $eventTitle){
    //first find event
    $result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getGroupsInEvent: Event with title '.$eventTitle.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);

    //now find all groups
    $result2 = $mysqli->query("SELECT Group_.GroupName, Group_.GroupID, Is_Member_Group.UID FROM Group_ INNER JOIN Is_Member_Group ON Is_Member_Group.GroupID=Group_.GroupID WHERE Group_.MainEventID=".$first_row[0]." AND Is_Member_Group.requestStatus='admin' AND Group_.Privacy = 1;");
    return $result2;
  }

  /*
  Basic getter that gets all the associated details with the event
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event
  */
  function getEvent($mysqli, $eventTitle){
    $result = $mysqli->query("SELECT * FROM Event_ WHERE Title='".$eventTitle."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    $first_row = mysqli_fetch_row($result);
    return $first_row;
  }

  /*
  Basic getter that gets all the user info of the event admin
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event
  */
  function getEventAdmin($mysqli, $eventTitle){
    $result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getEventAdmin: Event with title '.$eventTitle.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);
    //find admin from Is_Member_Event
    $result2 = $mysqli->query("SELECT User_.Email, User_.Name, User_.Address, User_.PhoneNumber FROM User_ INNER JOIN Is_Member_Event ON User_.UID=Is_Member_Event.UID WHERE Is_Member_Event.requestStatus='admin' AND EventID=".$first_row[0].";");
    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 'getEventAdmin: Oops! Something went wrong.';
    }
    return mysqli_fetch_row($result2);
  }

  /*
  Basic getter that gets all the user info of the group admin
  $mysqli: Connection to the DB object
  $groupName: Name of the group
  */
  function getGroupAdmin($mysqli, $groupName){
    $result = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getGroupAdmin: Event with title '.$eventTitle.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);
    //find admin from Is_Member_Event
    $result2 = $mysqli->query("SELECT User_.UID, User_.Username, User_.Email, User_.Name, User_.Address, User_.PhoneNumber FROM User_ INNER JOIN Is_Member_Group ON User_.UID=Is_Member_Group.UID WHERE Is_Member_Group.requestStatus='admin' AND GroupID=".$first_row[0].";");
    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 'getGroupAdmin: Oops! Something went wrong.';
    }
    return mysqli_fetch_row($result2);
  }

  /*
  Basic getter that returns all the groups the user belongs in from the event
  $mysqli: Connection to the DB object
  $groupID: ID of the group
  $username: Username of the user
  */
  function isMemberGroup($mysqli, $groupID, $username){
    //get UID
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'isMemberGroup: User with username '.$username.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);
    //assuming groupID is right...
    $result2 = $mysqli->query("SELECT UID FROM Is_Member_Group WHERE UID=".$first_row[0]." AND GroupID=".$groupID." AND requestStatus!='pending';");
    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 0;
    }
    return 1;
  }

  /*
  To get the event types
  $mysqli: Connection to the DB object
  Returns the results
  */
  function getEventTypes($mysqli){
    return $mysqli->query("SELECT EventType FROM Event_Type");
  }

  /*
  To get the one time code of the user (if any)
  $mysqli: Connection to the DB object
  Returns the one time code
  */
  function getOneTimeCode($mysqli, $username, $eventTitle){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getOneTimeCode: User with username '.$username.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);
    $result2 = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 'getOneTimeCode: Event with title '.$eventTitle.' was not found.';
    }
    $first_row_2 = mysqli_fetch_row($result2);

    $result3 = $mysqli->query("SELECT OneTimeCode FROM Is_Member_Event WHERE UID=".$first_row[0]." AND EventID=".$first_row_2[0].";");
    if(is_bool($result3) || mysqli_num_rows($result3) == 0){
      return 0;
    }
    $first_row_3 = mysqli_fetch_row($result3);
    if($first_row_3[0]==null){
      return 0;
    }
    return $first_row_3[0];
  }

  /*
  To get the one time code of the user (if any)
  $mysqli: Connection to the DB object
  Returns the one time code
  */
  function getOneTimeCodeGroup($mysqli, $username, $groupID){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'getOneTimeCodeGroup: User with username '.$username.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);
    $result3 = $mysqli->query("SELECT OneTimeCode FROM Is_Member_Group WHERE UID=".$first_row[0]." AND GroupID=".$groupID.";");
    if(is_bool($result3) || mysqli_num_rows($result3) == 0){
      return 0;
    }
    $first_row_3 = mysqli_fetch_row($result3);
    if($first_row_3[0]==null){
      return 0;
    }
    return $first_row_3[0];
  }

  /*
  This function gets the group name from the ID
  $mysqli: Connection to the DB object
  $groupID: Group ID
  */
  function getGroupName($mysqli, $groupID){
    $result = $mysqli->query("SELECT GroupName FROM Group_ WHERE GroupID=".$groupID.";");
    if(is_bool($result) || mysqli_num_rows($result)==0){
      return 'Could not find group with ID '.$groupID.'.';
    }
    $first_row = mysqli_fetch_row($result);
    return $first_row[0];
  }

  /*
  This function returns the rates that were set
  $mysqli: Connection to the DB object
  */
  function getRates($mysqli){
    $result = $mysqli->query("SELECT * FROM Rates");
    return $result;
  }

  /*
  This function shows all the events
  */
  function getAllEvents($mysqli){
    return $mysqli->query("SElECT EventID, Title, Status FROM Event_");
  }

  /*
  This function shows all the groups
  */
  function getAllGroups($mysqli){
    return $mysqli->query("SELECT GroupID, GroupName FROM Group_");
  }

  /*
  This function gets all users
  */
  function getAllUsers($mysqli){
    return $mysqli->query("SELECT UID, Username FROM User_");
  }

  /*
  Get event title
  $mysqli: Connection to the DB object
  $eventID: ID of the event
  */
  function getEventTitle($mysqli, $eventID){
    $result = $mysqli->query("SELECT Title FROM Event_ WHERE EventID=".$eventID.";");
    return mysqli_fetch_row($result);
  }

  /*
  Get event id of group
  $mysqli: Connection to the DB object
  $groupID: ID of the group
  */
  function getGroupMainEventID($mysqli, $groupID){
    $result = $mysqli->query("SELECT MainEventID FROM Group_ WHERE GroupID=".$groupID);
    return mysqli_fetch_row($result);
  }

  /*
  Get email info with ID.
  $mysqli: Connection to the DB object
  $emailID: ID of the email
  */
  function getEmailInfo($mysqli, $emailID){
    $result = $mysqli->query("SELECT * FROM Emails WHERE EID=".$emailID.";");
    return mysqli_fetch_row($result);
  }

?>
