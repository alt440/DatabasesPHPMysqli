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

    return $event_timestamp < $timestamp? 1: 0;
  }

  /*
  Verify if the event should be deleted (More than 7 years archived)
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event - Must exist
  */
  function shouldEventBeDeleted($mysqli, $eventTitle){
    $result = $mysqli->query("SELECT ExpiryDate FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'Event with title '.$eventTitle.' was not found.';
    }

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
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' was not found.';
    }

    $mysqli->query("UPDATE User_ SET Email='".$email."' WHERE Username='".$username."';");
    return $mysqli->error;
  }

  /*
  Change the password of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $password: Preceding password
  $new_password: New password
  */
  function updateUserPassword($mysqli, $username, $password, $new_password){
    $result= $mysqli->query("SELECT UID, Password FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' was not found.';
    }

    if(strcmp($password, $first_row[1])!=0){
      return 'Wrong credentials!';
    }

    $mysqli->query("UPDATE User_ SET Password='".$new_password."' WHERE UID=".$first_row[0].";");
    return $mysqli->error;
  }

  /*
  Chnge the name of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $name: New name of the user
  */
  function updateUser_Name($mysqli, $username, $name){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' was not found.';
    }
    $mysqli->query("UPDATE User_ SET Name='".$name."' WHERE Username='".$username."';");
    return $mysqli->error;
  }

  /*
  Change BankName of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $bank_name: New bank name
  */
  function updateUserBankName($mysqli, $username, $bank_name){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' was not found.';
    }
    $mysqli->query("UPDATE User_ SET BankName='".$bank_name."' WHERE Username='".$username."';");
    return $mysqli->error;
  }

  /*
  Change CreditCardNumber of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $credit_card_number: New credit card number of the user
  */
  function updateUserCreditCardNumber($mysqli, $username, $credit_card_number){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' was not found.';
    }
    $mysqli->query("UPDATE User_ SET CreditCardNumber=".$credit_card_number." WHERE Username='".$username."';");
    return $mysqli->error;
  }

  /*
  Change AccountNumber of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $account_number: Account number of the account of user
  */
  function updateUserAccountNumber($mysqli, $username, $account_number){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' was not found.';
    }
    $mysqli->query("UPDATE User_ SET AccountNumber=".$account_number." WHERE Username='".$username."';");
    return $mysqli->error;
  }

  /*
  Change PhoneNumber of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $phone_number: Phone number of the user
  */
  function updateUserPhoneNumber($mysqli, $username, $phone_number){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' was not found.';
    }
    $mysqli->query("UPDATE User_ SET PhoneNumber='".$phone_number."' WHERE Username='".$username."';");
    return $mysqli->error;
  }

  /*
  Change Address of a user
  $mysqli: Connection to the DB object
  $username: Username of the user
  $address: Address of the user
  */
  function updateUserAddress($mysqli, $username, $address){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'User with username '.$username.' was not found.';
    }
    $mysqli->query("UPDATE User_ SET Address='".$address."' WHERE Username='".$username."';");
    return $mysqli->error;
  }

?>
