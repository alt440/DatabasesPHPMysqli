<?php
  //Alexandre Therrien
  /*
  This file has the functions that will be used for the different test cases.
  Prepared statements for queries are required to protect against SQL injection.
  Done for all queries which depend on input from user.
  */

  /*
  To protect against sql injection, we do prepared statement queries.
  $stmt = $mysqli->prepare("SELECT * FROM User_ WHERE Username=?");
	$stmt->bind_param('s',$username);
	$stmt->execute();

  $results = $stmt->get_result();
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
    $stmt = $mysqli->prepare("UPDATE Event_ SET Status=0 WHERE Title=? ;");
  	$stmt->bind_param('s',$eventTitle);
  	$stmt->execute();
    //$mysqli->query("UPDATE Event_ SET Status=0 WHERE Title='".$eventTitle."';");
    return 'confirmCreationEvent: '.$mysqli->error;
  }

  /*
  This function sets a user as a member of the event, from its pending state.
  $mysqli: Connection to the DB object
  $username: Username of the user that we want to join the event.
  $eventTitle: Title of the event we want to set the user a member to.
  */
  function setMemberToEvent($mysqli, $username, $eventTitle){
    //find userID
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();

    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result)==0){
      return 'setMemberToEvent: User with username '.$username.' does not exist';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("SELECT EventID FROM Event_ WHERE Title=?");
  	$stmt->bind_param('s',$eventTitle);
  	$stmt->execute();

    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");

    if(is_bool($result2) || mysqli_num_rows($result2)==0){
      return 'setMemberToEvent: Event with event title '.$eventTitle.' does not exist.';
    }

    $first_row_2 = mysqli_fetch_row($result2);

    $stmt = $mysqli->prepare("UPDATE Is_Member_Event SET requestStatus='member' WHERE UID=? AND EventID=?");
  	$stmt->bind_param('ii',$first_row[0], $first_row_2[0]);
  	$stmt->execute();

    $stmt->get_result();
    //$mysqli->query("UPDATE Is_Member_Event SET requestStatus='member' WHERE UID=".$first_row[0]." AND EventID=".$first_row_2[0].";");
    return 'setMemberToEvent: '.$mysqli->error;
  }

  /*
  This function sets a user as a member of the event, from its pending state.
  $mysqli: Connection to the DB object
  $username: Username of the user that we want to join the event.
  $eventTitle: Title of the event we want to set the user a member to.
  */
  function setMemberToEventID($mysqli, $UID, $eventTitle){

    $stmt = $mysqli->prepare("SELECT EventID FROM Event_ WHERE Title=?");
  	$stmt->bind_param('s',$eventTitle);
  	$stmt->execute();

    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");

    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 'setMemberToEvent: Event with event title '.$eventTitle.' does not exist.';
    }

    $first_row_2 = mysqli_fetch_row($result2);

    $stmt = $mysqli->prepare("UPDATE Is_Member_Event SET requestStatus='member' WHERE UID=? AND EventID=?");
  	$stmt->bind_param('ii',$UID, $first_row_2[0]);
  	$stmt->execute();

    //$mysqli->query("UPDATE Is_Member_Event SET requestStatus='member' WHERE UID=".$UID." AND EventID=".$first_row_2[0].";");
    return 'setMemberToEvent: '.$mysqli->error;
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
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'setMemberToGroup: User with username '.$username.' does not exist';
    }

    $first_row = mysqli_fetch_row($result);

    //find GroupID
    $stmt = $mysqli->prepare("SELECT GroupID FROM Group_ WHERE GroupName=?");
  	$stmt->bind_param('s',$groupName);
  	$stmt->execute();
    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");

    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 'setMemberToGroup: Group with group name '.$groupName.' does not exist.';
    }

    $first_row_2 = mysqli_fetch_row($result2);

    $stmt = $mysqli->prepare("UPDATE Is_Member_Group SET requestStatus='member' WHERE UID=? AND GroupID=?");
  	$stmt->bind_param('ii', $first_row[0], $first_row_2[0]);
  	$stmt->execute();
    //$mysqli->query("UPDATE Is_Member_Group SET requestStatus='member' WHERE UID=".$first_row[0]." AND GroupID=".$first_row_2[0].";");
    return 'setMemberToGroup: '.$mysqli->error;
  }

  /*
  This function sets a user as a member of the group, from its pending state.
  ONLY AVAILABLE TO GROUP ADMIN(s)
  $mysqli: Connection to the DB object
  $username: Username of the user that we want to join the group.
  $groupID: Group ID
  */
  function setMemberToGroupID($mysqli, $username, $groupID){
    //find userID
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'setMemberToGroup: User with username '.$username.' does not exist';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("UPDATE Is_Member_Group SET requestStatus='member' WHERE UID=? AND GroupID=?");
  	$stmt->bind_param('ii', $first_row[0], $groupID);
  	$stmt->execute();
    //$mysqli->query("UPDATE Is_Member_Group SET requestStatus='member' WHERE UID=".$first_row[0]." AND GroupID=".$groupID.";");
    return 'setMemberToGroup: '.$mysqli->error;
  }

  /*
  Converts a date stamp to a timestamp: 'YYYY-MM-DD' to a long
  $dateStamp: Date in format 'YYYY-MM-DD'
  Returns long
  */
  function convertDateStampToTimeStamp($dateStamp){
    $a = strptime($dateStamp, '%Y-%m-%d');
    //$a = date('Y-m-d', $dateStamp);
    //$a = strtotime($dateStamp);
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
  Converts a timestamp to a date stamp: long to a 'YYYY-MM-DD H:i'
  $timestamp: Long representing the time in seconds since 1970
  Returns 'YYYY-MM-DD H:i'
  */
  function convertTimeStampToDateStampHourMinute($timestamp){
    return date('Y-m-d H:i', $timestamp);
  }

  /*
  Verify validity of the event (if it is archived)
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event - Must exist
  */
  function isEventArchived($mysqli, $eventTitle){
    $stmt = $mysqli->prepare("SELECT ExpiryDate FROM Event_ WHERE Title=?");
  	$stmt->bind_param('s',$eventTitle);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT ExpiryDate FROM Event_ WHERE Title='".$eventTitle."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'isEventArchived: Event with title '.$eventTitle.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);
    //current time
    $timestamp = time();

    //convert string to timestamp
    $event_timestamp = convertDateStampToTimeStamp($first_row[0]);

    return $event_timestamp < $timestamp? 1: 0;
  }

  /*
  Verify if the event should be deleted (More than 7 years archived)
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event - Must exist
  */
  function shouldEventBeDeleted($mysqli, $eventTitle){
    $stmt = $mysqli->prepare("SELECT ExpiryDate FROM Event_ WHERE Title=?");
  	$stmt->bind_param('s',$eventTitle);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT ExpiryDate FROM Event_ WHERE Title='".$eventTitle."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'shouldEventBeDeleted: Event with title '.$eventTitle.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);

    //increase by 7 the year on the expiry date
    $year = strval(intval(substr($first_row[0],0,4))+7);
    //then put this back in
    $expiry_date = $year.substr($first_row[0],4);

    //current time
    $timestamp = time();

    //convert string to timestamp
    $event_timestamp = convertDateStampToTimeStamp($expiry_date);

    return $event_timestamp < $timestamp? 1: 0;
  }

  /*
  Change the email of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $email: New email the user wants
  */
  function updateUserEmail($mysqli, $username, $email){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'updateUserEmail: User with username '.$username.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("UPDATE User_ SET Email=? WHERE Username=?");
  	$stmt->bind_param('ss',$email, $username);
  	$stmt->execute();
    $mysqli->query("UPDATE User_ SET Email='".$email."' WHERE Username='".$username."';");
    return 'updateUserEmail: '.$mysqli->error;
  }

  /*
  Change the password of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $password: Preceding password
  $new_password: New password
  */
  function updateUserPassword($mysqli, $username, $password, $new_password){
    $stmt = $mysqli->prepare("SELECT UID, Password FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result= $mysqli->query("SELECT UID, Password FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'updateUserPassword: User with username '.$username.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);

    $auth = password_verify($password, $first_row[1]);
    if($auth !=true){
      return 'Wrong credentials!';
    }

    $new_password = password_hash($new_password, PASSWORD_BCRYPT);
    $stmt = $mysqli->prepare("UPDATE User_ SET Password=? WHERE UID=?");
  	$stmt->bind_param('si',$new_password, $first_row[0]);
  	$stmt->execute();
    //$mysqli->query("UPDATE User_ SET Password='".$new_password."' WHERE UID=".$first_row[0].";");
    //return 'updateUserPassword: '.$mysqli->error;
    return 1;
  }

  /*
  Chnge the name of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $name: New name of the user
  */
  function updateUser_Name($mysqli, $username, $name){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'updateUser_Name: User with username '.$username.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("UPDATE User_ SET Name=? WHERE Username=?");
  	$stmt->bind_param('ss', $name, $username);
  	$stmt->execute();
    //$mysqli->query("UPDATE User_ SET Name='".$name."' WHERE Username='".$username."';");
    return 'updateUser_Name: '.$mysqli->error;
  }

  /*
  Change BankName of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $bank_name: New bank name
  */
  function updateUserBankName($mysqli, $username, $bank_name){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'updateUserBankName: User with username '.$username.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("UPDATE User_ SET BankName=? WHERE Username=?");
  	$stmt->bind_param('ss', $bank_name, $username);
  	$stmt->execute();
    //$mysqli->query("UPDATE User_ SET BankName='".$bank_name."' WHERE Username='".$username."';");
    return 'updateUserBankName: '.$mysqli->error;
  }

  /*
  Change CreditCardNumber of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $credit_card_number: New credit card number of the user
  */
  function updateUserCreditCardNumber($mysqli, $username, $credit_card_number){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'updateUserCreditCardNumber: User with username '.$username.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("UPDATE User_ SET CreditCardNumber=? WHERE Username=? ;");
  	$stmt->bind_param('ss', $credit_card_number, $username);
  	$stmt->execute();
    //$mysqli->query("UPDATE User_ SET CreditCardNumber=".$credit_card_number." WHERE Username='".$username."';");
    return 'updateUserCreditCardNumber: '.$mysqli->error;
  }

  /*
  Change AccountNumber of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $account_number: Account number of the account of user
  */
  function updateUserAccountNumber($mysqli, $username, $account_number){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'updateUserAccountNumber: User with username '.$username.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("UPDATE User_ SET AccountNumber=? WHERE Username=?");
  	$stmt->bind_param('is', $account_number, $username);
  	$stmt->execute();
    //$mysqli->query("UPDATE User_ SET AccountNumber=".$account_number." WHERE Username='".$username."';");
    return 'updateUserAccountNumber: '.$mysqli->error;
  }

  /*
  Change PhoneNumber of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $phone_number: Phone number of the user
  */
  function updateUserPhoneNumber($mysqli, $username, $phone_number){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=? ;");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'updateUserPhoneNumber: User with username '.$username.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("UPDATE User_ SET PhoneNumber=? WHERE Username=?");
  	$stmt->bind_param('ss', $phone_number, $username);
  	$stmt->execute();
    //$mysqli->query("UPDATE User_ SET PhoneNumber='".$phone_number."' WHERE Username='".$username."';");
    return 'updateUserPhoneNumber: '.$mysqli->error;
  }

  /*
  Change Address of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $address: Address of the user
  */
  function updateUserAddress($mysqli, $username, $address){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'updateUserAddress: User with username '.$username.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("UPDATE User_ SET Address=? WHERE Username=? ;");
  	$stmt->bind_param('ss', $address, $username);
  	$stmt->execute();
    //$mysqli->query("UPDATE User_ SET Address='".$address."' WHERE Username='".$username."';");
    return 'updateUserAddress: '.$mysqli->error;
  }

  /*
  Is user a member of the event
  $mysqli: Connection to the DB object
  $username: Username of the user
  $eventTitle: Title of the event

  Returns boolean
  */
  function isUserMemberOfEvent($mysqli, $username, $eventTitle){
    //find EventID and UID
    $stmt = $mysqli->prepare("SELECT EventID FROM Event_ WHERE Title=?");
  	$stmt->bind_param('s',$eventTitle);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'Event with title '.$eventTitle.' could not be found.';
    }
    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=?");
  	$stmt->bind_param('s',$username);
  	$stmt->execute();
    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 'User with username '.$username.' could not be found.';
    }
    $first_row_2 = mysqli_fetch_row($result2);

    $stmt = $mysqli->prepare("SELECT UID, requestStatus FROM Is_Member_Event WHERE UID=? AND EventID=? ;");
  	$stmt->bind_param('ii', $first_row_2[0], $first_row[0]);
  	$stmt->execute();
    $result3 = $stmt->get_result();
    //$result3 = $mysqli->query("SELECT UID, requestStatus FROM Is_Member_Event WHERE UID=".$first_row_2[0]." AND EventID=".$first_row[0].";");
    if(is_bool($result3) || mysqli_num_rows($result3)==0){
      return 0;
    }
    $first_row_3 = mysqli_fetch_row($result3);
    if(strcmp($first_row_3[1],'pending')==0){
      return 0;
    }

    return 1;
  }

  /*
  Is user a member of the event
  $mysqli: Connection to the DB object
  $UID: Username of the user
  $eventID: ID of the event

  Returns boolean
  */
  function isUserMemberOfEventID($mysqli, $UID, $eventID){
    $stmt = $mysqli->prepare("SELECT UID, requestStatus FROM Is_Member_Event WHERE UID=? AND EventID=? ;");
  	$stmt->bind_param('ii', $UID, $eventID);
  	$stmt->execute();
    $result3 = $stmt->get_result();
    //$result3 = $mysqli->query("SELECT UID, requestStatus FROM Is_Member_Event WHERE UID=".$UID." AND EventID=".$eventID.";");
    if(is_bool($result3) || mysqli_num_rows($result3)==0){
      return 0;
    }
    $first_row_3 = mysqli_fetch_row($result3);
    if(strcmp($first_row_3[1],'pending')==0){
      return 0;
    }

    return 1;
  }

  /*
  Get user as a member of the event
  $mysqli: Connection to the DB object
  $username: Username of the user
  $eventTitle: Title of the event

  Returns UID, requestStatus
  */
  function getUserMemberOfEvent($mysqli, $username, $eventTitle){
    //find EventID and UID
    $stmt = $mysqli->prepare("SELECT EventID FROM Event_ WHERE Title=? ;");
  	$stmt->bind_param('s', $eventTitle);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'Event with title '.$eventTitle.' could not be found.';
    }
    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=? ;");
  	$stmt->bind_param('s', $username);
  	$stmt->execute();
    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 'User with username '.$username.' could not be found.';
    }
    $first_row_2 = mysqli_fetch_row($result2);

    $stmt = $mysqli->prepare("SELECT UID, requestStatus FROM Is_Member_Event WHERE UID=? AND EventID=? ;");
  	$stmt->bind_param('ii', $first_row_2[0], $first_row[0]);
  	$stmt->execute();
    $result3 = $stmt->get_result();
    //$result3 = $mysqli->query("SELECT UID, requestStatus FROM Is_Member_Event WHERE UID=".$first_row_2[0]." AND EventID=".$first_row[0].";");
    if(is_bool($result3) || mysqli_num_rows($result3)==0){
      return 0;
    }
    $first_row_3 = mysqli_fetch_row($result3);
    if(strcmp($first_row_3[1],'pending')==0){
      return 0;
    }

    return $first_row_3;
  }

  /*
  This function verifies if all the user parameters are set to create an event.
  $mysqli: Connection to the DB object
  $username: Username of the user
  */
  function verifyUserDetails($mysqli, $username){
    //get the user first
    $stmt = $mysqli->prepare("SELECT * FROM User_ WHERE Username=? ;");
  	$stmt->bind_param('s', $username);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT * FROM User_ WHERE Username='".$username."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'User with username '.$username.' was not found.';
    }

    $first_row = mysqli_fetch_row($result);

    $array_vals = array(
      7 => "Bank Number",
      8 => "Account Number",
      9 => "Credit Card Number",
      10 => "Address",
      11 => "Phone Number"
    );

    //now verify that all values are set
    for($i=7;$i<sizeof($first_row);$i++){
      if($first_row[$i]==null){
        return 'Parameter '.$array_vals[$i].' is not set.';
      }
    }

    return 1;
  }

  /*
  Changes group name
  $mysqli: Connection to the DB object
  $groupID: ID of the group
  $newGroupName: New group name
  */
  function changeGroupName($mysqli, $groupID, $newGroupName){
    $stmt = $mysqli->prepare("UPDATE Group_ SET GroupName=? WHERE GroupID=? ;");
  	$stmt->bind_param('si', $newGroupName, $groupID);
  	$stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("UPDATE Group_ SET GroupName='".$newGroupName."' WHERE GroupID=".$groupID.";");
  }

  /*
  Set event active
  $mysqli: Connection to the DB object
  $eventID: ID of the event
  */
  function setEventActive($mysqli, $eventID){
    $stmt = $mysqli->prepare("UPDATE Event_ SET Status=0 WHERE EventID=? ;");
  	$stmt->bind_param('i', $eventID);
  	$stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("UPDATE Event_ SET Status=0 WHERE EventID=".$eventID.";");
  }

  /*
  Set new event manager
  $mysqli: Connection to the DB object
  $eventID: ID of the event
  $UID: User ID
  */
  function setEventManager($mysqli, $eventID, $UID){
    //verify if user in event
    $stmt = $mysqli->prepare("SELECT User_.UID, User_.Email, User_.Name, User_.Address, User_.PhoneNumber FROM User_ INNER JOIN Is_Member_Event ON User_.UID=Is_Member_Event.UID WHERE Is_Member_Event.requestStatus='admin' AND EventID=? ;");
  	$stmt->bind_param('i', $UID);
  	$stmt->execute();
    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT User_.UID, User_.Email, User_.Name, User_.Address, User_.PhoneNumber FROM User_ INNER JOIN Is_Member_Event ON User_.UID=Is_Member_Event.UID WHERE Is_Member_Event.requestStatus='admin' AND EventID=".$eventID.";");
    $first_row_2 = mysqli_fetch_row($result2);

    $stmt = $mysqli->prepare("SELECT User_.Username FROM User_ INNER JOIN Is_Member_Event ON User_.UID=Is_Member_Event.UID WHERE User_.UID=? ;");
  	$stmt->bind_param('i', $UID);
  	$stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT User_.Username FROM User_ INNER JOIN Is_Member_Event ON User_.UID=Is_Member_Event.UID WHERE User_.UID=".$UID.";");
    if(mysqli_num_rows($result)!=0){
      //then just set request status
      $stmt = $mysqli->prepare("UPDATE Is_Member_Event SET requestStatus='admin' WHERE UID=? AND EventID=? ;");
    	$stmt->bind_param('ii', $UID, $eventID);
    	$stmt->execute();
      //$result3 = $mysqli->query("UPDATE Is_Member_Event SET requestStatus='admin' WHERE UID=".$UID." AND EventID=".$eventID.";");
    } else{
      $stmt = $mysqli->prepare("INSERT INTO Is_Member_Event (EventID, UID, requestStatus, hasSeenLastMessage) VALUES (?,?,'admin',1);");
    	$stmt->bind_param('ii', $eventID, $UID);
    	$stmt->execute();
      //$mysqli->query("INSERT INTO Is_Member_Event (EventID, UID, requestStatus, hasSeenLastMessage) VALUES (".$eventID.",".$UID.",'admin',1)");
    }
    //remove other admin status
    $stmt = $mysqli->prepare("UPDATE Is_Member_Event SET requestStatus='member' WHERE UID=? ;");
    $stmt->bind_param('i', $UID);
    $stmt->execute();
    //$mysqli->query("UPDATE Is_Member_Event SET requestStatus='member' WHERE UID=".$first_row_2[0].";");
  }

  /*
  Set group public
  $mysqli: Connection to the DB object
  $groupID: ID of the group
  */
  function setGroupPublic($mysqli, $groupID){
    $stmt = $mysqli->prepare("UPDATE Group_ SET Privacy=0 WHERE GroupID=? ;");
    $stmt->bind_param('i', $groupID);
    $stmt->execute();
    //$mysqli->query("UPDATE Group_ SET Privacy=0 WHERE GroupID=".$groupID.";");
  }

  /*
  Does event exist?
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event
  */
  function doesEventExist($mysqli, $eventTitle){
    $stmt = $mysqli->prepare("SELECT * FROM Event_ WHERE Title=? ;");
    $stmt->bind_param('s', $eventTitle);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT * FROM Event_ WHERE Title='".$eventTitle."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    return 1;
  }

  /*
  Does event exist? With ID
  $mysqli: Connection to the DB object
  $eventID: ID of the event
  */
  function doesEventExistID($mysqli, $eventID){
    $stmt = $mysqli->prepare("SELECT * FROM Event_ WHERE EventID=? ;");
    $stmt->bind_param('i', $eventID);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT * FROM Event_ WHERE EventID=".$eventID.";");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    return 1;
  }

  /*
  Does group exist? With ID
  $mysqli: Connection to the DB object
  $groupID: ID of the group
  */
  function doesGroupExistID($mysqli, $groupID){
    $stmt = $mysqli->prepare("SELECT * FROM Group_ WHERE GroupID=? ;");
    $stmt->bind_param('i', $groupID);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT * FROM Group_ WHERE GroupID=".$groupID.";");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    return 1;
  }

  /*
  Does UID exist?
  $mysqli: Connection to the DB object
  $UID: ID of the user
  */
  function doesUIDExist($mysqli, $UID){
    $stmt = $mysqli->prepare("SELECT * FROM User_ WHERE UID=? ;");
    $stmt->bind_param('i', $UID);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT * FROM User_ WHERE UID=".$UID.";");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    return 1;
  }

  /*
  Does username exist?
  $mysqli: Connection to the DB object
  $username: Username of the user
  */
  function doesUsernameExist($mysqli, $username){
    $stmt = $mysqli->prepare("SELECT * FROM User_ WHERE Username=? ;");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT * FROM User_ WHERE Username='".$username."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    return 1;
  }

  /*
  Does Rates ID exist?
  $mysqli: Connection to the DB object
  $RID: Rates ID
  */
  function doesRIDExist($mysqli, $RID){
    $stmt = $mysqli->prepare("SELECT * FROM Rates WHERE RID=? ;");
    $stmt->bind_param('i', $RID);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT * FROM Rates WHERE RID='".$RID."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 0;
    }
    return 1;
  }

  /*
  To increase duration of an event, add an extra year
  $mysqli: Connection to the DB object
  $eventID: ID of the event
  $expiryDate: New expiry date of format YYYY-mm-dd
  */
  function setEventExpiryDate($mysqli, $eventID, $expiryDate){
    $stmt = $mysqli->prepare("UPDATE Event_ SET ExpiryDate=? WHERE EventID=? ;");
    $stmt->bind_param('si', $expiryDate, $eventID);
    $stmt->execute();
    $result = $stmt->get_result();
  }

?>
