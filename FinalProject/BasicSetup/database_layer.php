<?php

  /*
  This file applies modifications to the database.
  */

  /*
  To create a new comment (from post in Event)
  $mysqli: Connection to the DB object
  $replyString: If the comment contains any text to be displayed (string) - can be empty
  $replyStringCID: Text for the content which we are commenting on (There could also be timestamp comparison...) (string)
  $username: Username of the user that published the comment. (string)
  */
  function addComment($mysqli, $replyString, $replyStringCID, $username){
    //search for the content made with timestamp timestampCID
    $result = $mysqli->query("SELECT CID,PermissionType,GroupID FROM Content WHERE replyString='".$replyStringCID."';");
    $first_row = mysqli_fetch_row($result);
    if(is_bool($first_row[0])){
      return 'There is no such content which has this string as content: '.$replyStringCID;
    }

    //get user and see if exists
    $result2 = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row_2 = mysqli_fetch_row($result2);
    if(is_bool($first_row_2[0])){
      return 'There is no such user that has this username: '.$username;
    }

    if($first_row[2]!=''){
      return 'Content comes from a group. Cannot comment on some content from a group.';
    }

    if($first_row[1]==0){
      return 'Content cannot be commented due to privilege level';
    }

    if($first_row[1]==1){
      //only comment is accepted. No links!
      if(strpos($replyString, "www.") !== false){
        return 'There is a link inside the comment; according to the privilege level, cannot be sent.';
      }
    }
    $timestamp = time();
    $mysqli->query("INSERT INTO Comment (CID, replyString, TimeStamp) VALUES (".$first_row[0].",'".$replyString."',".$timestamp.");");
    //find CoID of just added entity
    $result3 = $mysqli->query("SELECT CoID FROM Comment WHERE TimeStamp=".$timestamp.";");
    $first_row_3 = mysqli_fetch_row($result3);
    $mysqli->query("INSERT INTO Post_Comment (CoID, UID) VALUES (".$first_row_3[0].",".$first_row_2[0].");");
    return $mysqli->error;

  }

  /*
  To create some new content (reply in Group, post in Event)
  $mysqli: Connection to the DB object
  $permissionType: 0 for no comments, 1 for comments, 2 for comments and links (not valid for group content. See notes below)
  $replyImage: If the content contains any image to be displayed - path to the image (string)
  $replyString: If the content contains any text to be displayed (string) - can be empty
  $eventTitle: The title of the event this content is getting into (string) - Must exist
  $groupName: The name of the group this content is getting into (string) - can be empty
  $username: Username of the user that posted the content
  Note: $replyImage and $replyString cannot both be empty
  Note: If only $eventTitle is given, then the content will go into an event.
  If both $eventTitle and $groupName are given, then content will go into a group.
  */
  function addContent($mysqli, $permissionType, $replyImage, $replyString,
    $eventTitle, $groupName, $username){
    //find event related
    $result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'Could not find any Event with the name '.$eventTitle."<br>";
    }

    //find group related (if any)
    $result2 = 0;
    $first_row_2 = [FALSE];
    if($groupName!=''){
      $result2 = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");
      $first_row_2 = mysqli_fetch_row($result2);
      if(is_bool($first_row_2[0])){
        return 'Could not find any Group with the name '.$groupName."<br>";
      }
    }

    //find the user that posted this content
    $result3 = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row_3 = mysqli_fetch_row($result3);

    if(is_bool($first_row_3[0])){
      return 'Could not find any user with username '.$username."<br>";
    }

    if($permissionType>2 || $permissionType<0){
      return 'Error: Invalid permission type';
    }

    //get current timestamp
    $timestamp = time();

    //add to content
    if(is_bool($first_row_2[0])){
      if($replyImage==''){
        $mysqli->query("INSERT INTO Content (EventID, PermissionType, TimeStamp, replyString) VALUES (".$first_row[0].",".$permissionType.",".$timestamp.",'".$replyString."');");
      } else{
        //not working
        $mysqli->query("INSERT INTO Content (EventID, PermissionType, TimeStamp, replyImage, replyString) VALUES (".$first_row[0].",".$permissionType.",".$timestamp.",".file_get_contents($replyImage).",'".$replyString."');");
      }

    } else{
      if($replyImage==''){
        $mysqli->query("INSERT INTO Content (EventID, GroupID, PermissionType, TimeStamp, replyString) VALUES (".$first_row[0].",".$first_row_2[0].",0,".$timestamp.",'".$replyString."');");
      } else{
        //not working
        $mysqli->query("INSERT INTO Content (EventID, GroupID, PermissionType, TimeStamp, replyImage, replyString) VALUES (".$first_row[0].",".$first_row_2[0].",0,".$timestamp.",".file_get_contents($replyImage).",'".$replyString."');");
      }

    }

    $val=$mysqli->query("SELECT CID FROM Content WHERE TimeStamp=".$timestamp.";");
    $first_row_4 = mysqli_fetch_row($val);
    if(!is_bool($first_row_4[0])){
      $mysqli->query("INSERT INTO Post (CID, UID) VALUES (".$first_row_4[0].",".$first_row_3[0].");");
    }


    return $mysqli->error;
  }

  /*
  To create a new email message
  $mysqli: Connection to the DB object
  $sourceUsername: Username of the sender of the email (string) - User must exist
  $targetUsername: Username of the target of the email (string) - User must exist
  $titleEmail: Title of the email being sent
  $replyString: Content of the email (string)
  */
  function sendEmail($mysqli, $sourceUsername, $targetUsername, $titleEmail, $replyString){
    //find source user and target user
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$sourceUsername."';");
    $first_row = mysqli_fetch_row($result);
    if(is_bool($first_row[0])){
      return 'Source username does not exist';
    }

    $result2 = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$targetUsername."';");
    $first_row_2 = mysqli_fetch_row($result2);
    if(is_bool($first_row_2[0])){
      return 'Target username does not exist';
    }

    $timestamp = time();

    //now add to email list
    $mysqli->query("INSERT INTO Emails (TimeStamp, SourceUser, TargetUser, TitleEmail, Content) VALUES (".$timestamp.",".$first_row[0].",".$first_row_2[0].",'".$titleEmail."','".$replyString."');");
    return $mysqli->error;
  }

  /*
  To create a new event
  $mysqli: Connection to the DB object
  $date: date object of format 'YYYY-MM-DD'
  $title: Name of the event
  $expiryDate: date object of format 'YYYY-MM-DD'
  $eventType: type of the event
  $usernameCreator: The username of the person that created the event, so he can be set as admin.
  Status object being sent as 1 tells us that the event is pending for approval.
  */
  function createEvent($mysqli, $date, $title, $expiryDate, $eventType, $usernameCreator){
    if(doesEventTypeExist($eventType)){
        return 'Was this event type added to the event types table?';
      }

    if($date[4] !='-' || $date[7]!='-' || strlen($date) !=10){
      return 'date';
    }
    if($expiryDate[4] !='-' || $expiryDate[7]!='-' || strlen($expiryDate)!=10){
      return 'expiryDate';
    }
    //make sure expiryDate > date! Otherwise event will be archived

    //make sure the creator actually exists, and make him the admin of the event.
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$usernameCreator."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'The username provided does not correspond to any user';
    }

    $mysqli->query("INSERT INTO Event_ (Status, Date, Title, ExpiryDate, EventType) values (1,'".$date."','".$title."','".$expiryDate."','".$eventType."');");
    $error = $mysqli->error;

    //get the event id just created
    $result2 = $mysqli->query("SELECT EventID FROM Event_ WHERE Date='".$date."' AND Title='".$title."';");
    $first_row_2 = mysqli_fetch_row($result2);

    //no need to verify if insertion worked; I just added the event

    //now add the user to the event
    //this tells that the user is the admin and that he has seen the latest message (event not created, nothing in it)
    $mysqli->query("INSERT INTO Is_Member_Event (EventID, UID, requestStatus, hasSeenLastMessage) VALUES (".$first_row_2[0].",".$first_row[0].",'admin',1)");

    return $error;
  }

  /*
  To add an event type
  $mysqli: Connection to the DB object
  $eventType: The type of event (string)
  $isProfitable: If this type of event is profitable. 0 for no, 1 for yes (int)
  */
  function addEventType($mysqli, $eventType, $isProfitable){
    $mysqli->query("INSERT INTO Event_Type values ('".$eventType."',".$isProfitable.");");
    return $mysqli->error;
  }

  /*
  To create a new group
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event the group belongs to (string) - Must exist
  $groupName: Name of the new group (string)
  $usernameCreator: Username of the user that created the group - Must exist
  */
  function createGroup($mysqli, $eventTitle, $groupName, $usernameCreator){
    //first search for event
    $result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'Event with title '.$eventTitle.' does not exist';
    }

    //then search for user creator. Need to verify the user is already a member of the event!
    $result2 = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$usernameCreator."';");
    $first_row_2 = mysqli_fetch_row($result2);

    if(is_bool($first_row_2[0])){
      return 'User with username '.$usernameCreator.' does not exist';
    }

    //because only a user in the event can create a group...
    $findUserInEvent = $mysqli->query("SELECT UID, requestStatus FROM Is_Member_Event WHERE EventID=".$first_row[0].";");
    $hasFoundUser = 0;
    echo "Searching for user in event...";
    while($row = mysqli_fetch_row($findUserInEvent)){
      echo $row[0].' '.$row[1]."<br>";
      //if the user belongs in the event and is actually a member (and not a pending request)
      if($row[0] == $first_row_2[0] && strcmp($row[1],'pending')!=0){
        //then break because its OK for him/her to create the event.
        $hasFoundUser = 1;
        echo 'done'."<br>";
        break;
      }
    }

    if($hasFoundUser == 0){
      return 'User with username '.$usernameCreator.' cannot create a group because he has sent a demand to join the event that has not been answered or is simply not part of the group.';
    }

    //then add group with privacy 0 (group is private, so no showing)
    $mysqli->query("INSERT INTO Group_ (MainEventID, GroupName, Privacy) values (".intval($first_row[0]).",'".$groupName."',0);");
    $error=$mysqli->error;

    //find our new group ID
    $result3 = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");
    $first_row_3 = mysqli_fetch_row($result3);

    //then add user as admin of group. hasSeenLastMessage=0 means he has seen the last message of the group (since there is nothing...).
    $mysqli->query("INSERT INTO Is_Member_Group (GroupID, UID, requestStatus, hasSeenLastMessage) VALUES (".$first_row_3[0].",".$first_row_2[0].",'admin',1)");

    return $error;
  }

  /*
  To add a member to an event
  $mysqli: Connection to the DB object
  $username: Username of the user being added to the event (string) - Must exist
  $eventTitle: Title of the event the user is being added to (string) - Must exist
  */
  function addUserToEvent($mysqli, $username, $eventTitle){
    //find member with that username
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);
    //find event with that title
    $result2 = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row_2 = mysqli_fetch_row($result2);
    //now create the entry. set user with status 'pending'
    $mysqli->query("INSERT INTO Is_Member_Event (EventID, UID, requestStatus, hasSeenLastMessage) values (".intval($first_row_2[0]).",".intval($first_row[0]).",'pending',0);");
    return $mysqli->error;
  }

  /*
  To add a member to a group
  $mysqli: Connection to the DB object
  $groupName: Name of the group (string) - Must exist
  $usernameCreator: Username of the user (string) - Must exist
  $eventTitle: Title of the event in which the group belongs in - Must exist
  */
  function addUserToGroup($mysqli, $usernameCreator, $groupName, $eventTitle){
    //find member with that username
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$usernameCreator."';");
    $first_row = mysqli_fetch_row($result);

    $result3 = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row_3 = mysqli_fetch_row($result3);

    //because only a user in the event can create a group...
    $findUserInEvent = $mysqli->query("SELECT UID, requestStatus FROM Is_Member_Event WHERE EventID=".$first_row_3[0].";");
    $hasFoundUser = 0;
    while($row = mysqli_fetch_row($findUserInEvent)){
      //if the user belongs in the event and is actually a member (and not a pending request)
      if($row[0] == $first_row[0] && strcmp($row[1],'pending')!=0){
        //then break because its OK for him/her to create the event.
        $hasFoundUser = 1;
        break;
      }
    }

    if($hasFoundUser == 0){
      return 'User with username '.$usernameCreator.' cannot join a group because he has sent a demand to join the event that has not been answered or is simply not part of the group.';
    }

    //find event with that title
    $result2 = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");
    $first_row_2 = mysqli_fetch_row($result2);
    //now create the entry. set user with status 'pending'
    $mysqli->query("INSERT INTO Is_Member_Group (GroupID, UID, requestStatus, hasSeenLastMessage) values (".intval($first_row_2[0]).",".intval($first_row[0]).",'pending',0);");
    return $mysqli->error;
  }

  /*
  To add some new rates
  $mysqli: Connection to the DB object
  $numberEvents: Positive integer that tells how many events for a price (int)
  $storageGB: Storage that the event takes in gigabytes (int)
  $bandwidthGB: Bandwidth that the event takes in gigabytes (int)
  $eventType: Type of event (family, community, non-profit, profit) (string)
  $price: Price for this package (double)
  */
  function addRates($mysqli, $numberEvents, $storageGB, $bandwidthGB, $eventType,
    $price){
    if(doesEventTypeExist($eventType)){
        return 'Was this event type added to the event types table?';
      }

    $mysqli->query("INSERT INTO Rates values (".$numberEvents.",'".$eventType."',".$storageGB.",".$bandwidthGB.",".$price.");");
    return $mysqli->error;
  }

  /*
  To add a new user
  $mysqli: your connection object to the DB.
  $username: Username of the new user
  $password: Password of the new user
  $email: Email of the new user
  $name: Full name (First name and last name) of the new user
  $dateofbirth: Date of birth of user in format 'YEAR-MONTH-DAY'. Ex.:'1998-09-22'
  $privilegelevel: 0 is standard privilege for new user, 1 is for controller
  privileges, 2 is for admin privileges.

  Outputs: possible errors with the query, or strings that show what is wrong.
  */
  function addUser($mysqli, $username, $password, $email, $name, $dateofbirth,
      $privilegelevel){
    //check date of birth format
    if($dateofbirth[4] !='-' || $dateofbirth[7]!='-' || strlen($dateofbirth) !=10){
      return 'dateofbirth';
    }
    //check privilege level
    if($privilegelevel>2||$privilegelevel<0){
      return 'privilegelevel';
    }
    $mysqli->query("INSERT INTO User_ (Username, Password, Email, Name, DateOfBirth, PrivilegeLevel) values ('".$username."','".$password."','".$email."','".$name."','".$dateofbirth."',".$privilegelevel.");");
    return $mysqli->error;
  }

  /*
  This is the boolean condition to evaluate if an event type is in the DB
  $eventType: The event type (string)

  Outputs bool
  */
  function doesEventTypeExist($eventType){
    return strcmp($eventType,'family')!=0 && strcmp($eventType, 'community')!=0 &&
      strcmp($eventType,'non-profit')!=0 && strcmp($eventType, 'profit')!=0;
  }
?>
