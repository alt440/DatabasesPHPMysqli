<?php
  //Alexandre Therrien
  /*
  This file contains the delete operations needed for the project.
  Prepared statements for queries are required to protect against SQL injection.
  Done for all queries which depend on input from user.
  */


  /*
  Removes a user from an event (for admin, event manager)
  $mysqli: Connection to the DB object
  $username: Username of the user
  $eventTitle: Title of the event we want to get the user out
  */
  function removeUserFromEvent($mysqli, $username, $eventTitle){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=? ;");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'removeUserFromEvent: User with username '.$username.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("SELECT EventID FROM Event_ WHERE Title=? ;");
    $stmt->bind_param('s', $eventTitle);
    $stmt->execute();
    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 'removeUserFromEvent: Event with title '.$eventTitle.' was not found.';
    }
    $first_row_2 = mysqli_fetch_row($result2);

    //now remove the user with Is_Member_Event table
    $stmt = $mysqli->prepare("DELETE FROM Is_Member_Event WHERE UID=? AND EventID=? ;");
    $stmt->bind_param('ii', $first_row[0], $first_row_2[0]);
    $stmt->execute();
    //$mysqli->query("DELETE FROM Is_Member_Event WHERE UID=".$first_row[0]." AND EventID=".$first_row_2[0].";");
    //also remove all groups that the user belongs to in this event...
    $stmt = $mysqli->prepare("SELECT GroupID FROM Group_ WHERE MainEventID=? ;");
    $stmt->bind_param('i', $first_row_2[0]);
    $stmt->execute();
    $result3 = $stmt->get_result();
    //$result3 = $mysqli->query("SELECT GroupID FROM Group_ WHERE MainEventID=".$first_row_2[0].";");
    //now for all of them, remove the user's membership to the group
    while($row=mysqli_fetch_row($result3)){
      $stmt = $mysqli->prepare("DELETE FROM Is_Member_Group WHERE UID=? AND GroupID=? ;");
      $stmt->bind_param('ii', $first_row[0], $row[0]);
      $stmt->execute();
      //$result = $stmt->get_result();
      //$mysqli->query("DELETE FROM Is_Member_Group WHERE UID=".$first_row[0]." AND GroupID=".$row[0].";");
    }
    return 'removeUserFromEvent: '.$mysqli->error;

  }

  /*
  Removes a user from an event (for admin, event manager)
  $mysqli: Connection to the DB object
  $username: Username of the user
  $eventID: Id of the event
  */
  function removeUserFromEventID($mysqli, $username, $eventID){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=? ;");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'removeUserFromEvent: User with username '.$username.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);

    //now remove the user with Is_Member_Event table
    $stmt = $mysqli->prepare("DELETE FROM Is_Member_Event WHERE UID=? AND EventID=? ;");
    $stmt->bind_param('ii', $first_row[0], $eventID);
    $stmt->execute();
    //$mysqli->query("DELETE FROM Is_Member_Event WHERE UID=".$first_row[0]." AND EventID=".$eventID.";");
    //also remove all groups that the user belongs to in this event...
    $stmt = $mysqli->prepare("SELECT Group_.GroupID, Is_Member_Group.requestStatus FROM Group_ INNER JOIN Is_Member_Group ON Is_Member_Group.GroupID=Group_.GroupID WHERE Group_.MainEventID=? ;");
    $stmt->bind_param('i', $eventID);
    $stmt->execute();
    $result3 = $stmt->get_result();
    //$result3 = $mysqli->query("SELECT Group_.GroupID, Is_Member_Group.requestStatus FROM Group_ INNER JOIN Is_Member_Group ON Is_Member_Group.GroupID=Group_.GroupID WHERE Group_.MainEventID=".$eventID.";");
    //now for all of them, remove the user's membership to the group
    while($row=mysqli_fetch_row($result3)){
      if($row[1]!='admin'){
        $stmt = $mysqli->prepare("DELETE FROM Is_Member_Group WHERE UID=? AND GroupID=? ;");
        $stmt->bind_param('ii', $first_row[0], $row[0]);
        $stmt->execute();
        //$result = $stmt->get_result();
        //$mysqli->query("DELETE FROM Is_Member_Group WHERE UID=".$first_row[0]." AND GroupID=".$row[0].";");
      }
      else{
        removeGroupID($mysqli, $row[0]);
      }

    }
    return 'removeUserFromEvent: '.$mysqli->error;

  }

  /*
  Removes a user from a group (for admin, group manager)
  $mysqli: Connection to the DB object
  $username: Username of the user
  $groupName: Name of the group
  */
  function removeUserFromGroup($mysqli, $username, $groupName){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=? ;");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'removeUserFromGroup: User with username '.$username.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("SELECT GroupID FROM Group_ WHERE GroupName=? ;");
    $stmt->bind_param('s', $groupName);
    $stmt->execute();
    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");

    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 'removeUserFromGroup: Group with name '.$groupName.' was not found.';
    }

    $first_row_2 = mysqli_fetch_row($result2);

    $stmt = $mysqli->prepare("DELETE FROM Is_Member_Group WHERE UID=? AND GroupID=? ;");
    $stmt->bind_param('ii', $first_row[0], $first_row_2[0]);
    $stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("DELETE FROM Is_Member_Group WHERE UID=".$first_row[0]." AND GroupID=".$first_row_2[0].";");
    return 'removeUserFromGroup: '.$mysqli->error;
  }

  /*
  Removes a user from a group (for admin, group manager)
  $mysqli: Connection to the DB object
  $username: Username of the user
  $groupID: ID of the group
  */
  function removeUserFromGroupID($mysqli, $username, $groupID){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=? ;");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'removeUserFromGroup: User with username '.$username.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("DELETE FROM Is_Member_Group WHERE UID=? AND GroupID=? ;");
    $stmt->bind_param('ii', $first_row[0], $groupID);
    $stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("DELETE FROM Is_Member_Group WHERE UID=".$first_row[0]." AND GroupID=".$groupID.";");
    return 'removeUserFromGroup: '.$mysqli->error;
  }

  /*
  Removes a user from the system.
  $mysqli: Connection to the DB object
  $username: Username of the user
  */
  function removeUser($mysqli, $username){
    //first find his UID
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=? ;");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'removeUser: User with username '.$username.' does not exist.';
    }
    $first_row = mysqli_fetch_row($result);

    //now delete all:Emails, Event member, Group member, Content, Comment. Because there are foreign keys, I cannot replace certain values by bogus values
    //start with emails: easiest
    $stmt = $mysqli->prepare("DELETE FROM Emails WHERE SourceUser=? OR TargetUser=? ;");
    $stmt->bind_param('ii', $first_row[0], $first_row[0]);
    $stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("DELETE FROM Emails WHERE SourceUser=".$first_row[0]." OR TargetUser=".$first_row[0].";");
    //now go in post/post comment and find all content published by this user
    $stmt = $mysqli->prepare("SELECT CID FROM Post WHERE UID=? ;");
    $stmt->bind_param('i', $first_row[0]);
    $stmt->execute();
    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT CID FROM Post WHERE UID=".$first_row[0].";");
    //do the same for post_comment
    $stmt = $mysqli->prepare("SELECT CoID FROM Post_Comment WHERE UID=? ;");
    $stmt->bind_param('i', $first_row[0]);
    $stmt->execute();
    $result3 = $stmt->get_result();
    //$result3 = $mysqli->query("SELECT CoID FROM Post_Comment WHERE UID=".$first_row[0].";");
    //find all comments on posts
    $stmt = $mysqli->prepare("SELECT Post_Comment.CoID FROM Post_Comment INNER JOIN Comment ON Comment.CoID=Post_Comment.CoID WHERE CID= ANY(SELECT CID FROM Post WHERE UID=?) ;");
    $stmt->bind_param('i', $first_row[0]);
    $stmt->execute();
    $result4 = $stmt->get_result();
    //$result4 = $mysqli->query("SELECT Post_Comment.CoID FROM Post_Comment INNER JOIN Comment ON Comment.CoID=Post_Comment.CoID WHERE CID= ANY(SELECT CID FROM Post WHERE UID=".$first_row[0].");");
    //remove all comments that are from a post of this user
    //then remove the post
    while($row = mysqli_fetch_row($result4)){
      $mysqli->query("DELETE FROM Post_Comment WHERE CoID=".$row[0].";");
      $mysqli->query("DELETE FROM Comment WHERE CoID=".$row[0].";");
    }

    while($row = mysqli_fetch_row($result3)){
      $mysqli->query("DELETE FROM Post_Comment WHERE CoID=".$row[0].";");
      $mysqli->query("DELETE FROM Comment WHERE CoID=".$row[0].";");
    }

    while($row = mysqli_fetch_row($result2)){
      $mysqli->query("DELETE FROM Post WHERE CID=".$row[0].";");
      $mysqli->query("DELETE FROM Content WHERE CID=".$row[0].";");
    }

    //now delete group/event membership
    $mysqli->query("DELETE FROM Is_Member_Group WHERE UID=".$first_row[0].";");
    $mysqli->query("DELETE FROM Is_Member_Event WHERE UID=".$first_row[0].";");

    //finally, delete the user
    $mysqli->query("DELETE FROM User_ WHERE UID=".$first_row[0].";");
    return 'removeUser: '.$mysqli->error;
  }

  /*
  Remove a group and all its users
  $mysqli: Connection to the DB object
  $groupName: Name of the group
  */
  function removeGroup($mysqli, $groupName){
    //must remove all content from group, all membership to group, and the group itself.
    $stmt = $mysqli->prepare("SELECT GroupID FROM Group_ WHERE GroupName=? ;");
    $stmt->bind_param('s', $groupName);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'removeGroup: Could not find Group with GroupName '.$groupName.'.';
    }
    $first_row = mysqli_fetch_row($result);

    //find all posts belonging to the group
    $result2 = $mysqli->query("SELECT CID FROM Content WHERE GroupID=".$first_row[0].";");
    //now delete all
    while($row=mysqli_fetch_row($result2)){
      $mysqli->query("DELETE FROM Post WHERE CID=".$row[0].";");
      $mysqli->query("DELETE FROM Content WHERE CID=".$row[0].";");
    }

    //then remove all membership
    $mysqli->query("DELETE FROM Is_Member_Group WHERE GroupID=".$first_row[0].";");
    //then remove group
    $mysqli->query("DELETE FROM Group_ WHERE GroupID=".$first_row[0].";");
    return 'removeGroup: '.$mysqli->error;
  }

  /*
  Remove a group and all its users
  $mysqli: Connection to the DB object
  $groupID: Group ID
  */
  function removeGroupID($mysqli, $groupID){
    //must remove all content from group, all membership to group, and the group itself.

    //find all posts belonging to the group
    $stmt = $mysqli->prepare("SELECT CID FROM Content WHERE GroupID=? ;");
    $stmt->bind_param('i', $groupID);
    $stmt->execute();
    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT CID FROM Content WHERE GroupID=".$groupID.";");
    //now delete all
    while($row=mysqli_fetch_row($result2)){
      $mysqli->query("DELETE FROM Post WHERE CID=".$row[0].";");
      $mysqli->query("DELETE FROM Content WHERE CID=".$row[0].";");
    }

    //then remove all membership
    $stmt = $mysqli->prepare("DELETE FROM Is_Member_Group WHERE GroupID=? ;");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("DELETE FROM Is_Member_Group WHERE GroupID=".$groupID.";");
    //then remove group
    $stmt = $mysqli->prepare("DELETE FROM Group_ WHERE GroupID=? ;");
    $stmt->bind_param('i', $groupID);
    $stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("DELETE FROM Group_ WHERE GroupID=".$groupID.";");
    return 'removeGroup: '.$mysqli->error;
  }

  /*
  Remove an event and all its users
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event
  */
  function removeEvent($mysqli, $eventTitle){
    //find the eventID, then all the groups associated with it
    $stmt = $mysqli->prepare("SELECT EventID FROM Event_ WHERE Title=? ;");
    $stmt->bind_param('s', $eventTitle);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");

    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'removeEvent: Event with title '.$eventTitle.' was not found.';
    }
    $first_row = mysqli_fetch_row($result);

    $result_main = $mysqli->query("SELECT GroupID FROM Group_ WHERE MainEventID=".$first_row[0].";");

    while($row_main = mysqli_fetch_row($result_main)){
      //find all posts belonging to the group
      $result2 = $mysqli->query("SELECT CID FROM Content WHERE GroupID=".$row_main[0].";");
      //now delete all
      while($row=mysqli_fetch_row($result2)){
        $mysqli->query("DELETE FROM Post WHERE CID=".$row[0].";");
        $mysqli->query("DELETE FROM Content WHERE CID=".$row[0].";");
      }

      //then remove all membership
      $mysqli->query("DELETE FROM Is_Member_Group WHERE GroupID=".$row_main[0].";");
      //then remove group
      $mysqli->query("DELETE FROM Group_ WHERE GroupID=".$row_main[0].";");
    }

    //now all groups are deleted. Only posts and comments from the event left.
    $result3 = $mysqli->query("SELECT CID FROM Content WHERE EventID=".$first_row[0].";");
    $result4 = $mysqli->query("SELECT CoID FROM Comment WHERE CID=ANY(SELECT CID FROM Content WHERE EventID=".$first_row[0].");");

    //remove all comments before
    while($row = mysqli_fetch_row($result4)){
      $mysqli->query("DELETE FROM Post_Comment WHERE CoID=".$row[0].";");
      $mysqli->query("DELETE FROM Comment WHERE CoID=".$row[0].";");
    }

    //then all content
    while($row = mysqli_fetch_row($result3)){
      $mysqli->query("DELETE FROM Post WHERE CID=".$row[0].";");
      $mysqli->query("DELETE FROM Content WHERE CID=".$row[0].";");
    }

    //then remove all membership to group
    $mysqli->query("DELETE FROM Is_Member_Event WHERE EventID=".$first_row[0].";");
    //then remove the event
    $mysqli->query("DELETE FROM Event_ WHERE EventID=".$first_row[0].";");
    return 'removeEvent: '.$mysqli->error;
  }

  /*
  Remove an event and all its users
  $mysqli: Connection to the DB object
  $eventID: ID of the event
  */
  function removeEventID($mysqli, $eventID){

    $stmt = $mysqli->prepare("SELECT GroupID FROM Group_ WHERE MainEventID=? ;");
    $stmt->bind_param('i', $eventID);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result_main = $mysqli->query("SELECT GroupID FROM Group_ WHERE MainEventID=".$eventID.";");
    if(!is_bool($result_main)){
      while($row_main = mysqli_fetch_row($result_main)){
        //find all posts belonging to the group
        $result2 = $mysqli->query("SELECT CID FROM Content WHERE GroupID=".$row_main[0].";");
        //now delete all
        while($row=mysqli_fetch_row($result2)){
          $mysqli->query("DELETE FROM Post WHERE CID=".$row[0].";");
          $mysqli->query("DELETE FROM Content WHERE CID=".$row[0].";");
        }

        //then remove all membership
        $mysqli->query("DELETE FROM Is_Member_Group WHERE GroupID=".$row_main[0].";");
        //then remove group
        $mysqli->query("DELETE FROM Group_ WHERE GroupID=".$row_main[0].";");
      }
    }


    //now all groups are deleted. Only posts and comments from the event left.
    $stmt = $mysqli->prepare("SELECT CID FROM Content WHERE EventID=? ;");
    $stmt->bind_param('i', $eventID);
    $stmt->execute();
    $result3 = $stmt->get_result();
    //$result3 = $mysqli->query("SELECT CID FROM Content WHERE EventID=".$eventID.";");
    $stmt = $mysqli->prepare("SELECT CoID FROM Comment WHERE CID=ANY(SELECT CID FROM Content WHERE EventID=? ) ;");
    $stmt->bind_param('i', $eventID);
    $stmt->execute();
    $result4 = $stmt->get_result();
    //$result4 = $mysqli->query("SELECT CoID FROM Comment WHERE CID=ANY(SELECT CID FROM Content WHERE EventID=".$eventID.");");

    //remove all comments before
    while($row = mysqli_fetch_row($result4)){
      $mysqli->query("DELETE FROM Post_Comment WHERE CoID=".$row[0].";");
      $mysqli->query("DELETE FROM Comment WHERE CoID=".$row[0].";");
    }

    //then all content
    while($row = mysqli_fetch_row($result3)){
      $mysqli->query("DELETE FROM Post WHERE CID=".$row[0].";");
      $mysqli->query("DELETE FROM Content WHERE CID=".$row[0].";");
    }

    //then remove all membership to group
    $stmt = $mysqli->prepare("DELETE FROM Is_Member_Event WHERE EventID=? ;");
    $stmt->bind_param('i', $eventID);
    $stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("DELETE FROM Is_Member_Event WHERE EventID=".$eventID.";");
    //then remove the event
    $stmt = $mysqli->prepare("DELETE FROM Event_ WHERE EventID=? ;");
    $stmt->bind_param('i', $eventID);
    $stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("DELETE FROM Event_ WHERE EventID=".$eventID.";");
    return 'removeEvent: '.$mysqli->error;
  }

  /*
  This function deletes the one time code of event
  $mysqli: Connection to the DB
  $username: Username of the user
  $eventTitle: Title of the event
  */
  function deleteOneTimeCode($mysqli, $username, $eventTitle){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=? ;");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."'");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'deleteOneTimeCode: Could not find user with username '.$username.'.';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("SELECT EventID FROM Event_ WHERE Title=? ;");
    $stmt->bind_param('s', $eventTitle);
    $stmt->execute();
    $result2 = $stmt->get_result();
    //$result2 = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    if(is_bool($result2) || mysqli_num_rows($result2) == 0){
      return 'deleteOneTimeCode: Could not find event with title '.$eventTitle.'.';
    }

    $first_row_2 = mysqli_fetch_row($result2);

    $mysqli->query("UPDATE Is_Member_Event SET OneTimeCode = NULL WHERE UID=".$first_row[0]." AND EventID=".$first_row_2[0].";");
    return $mysqli->error;
  }

  /*
  This function deletes the one time code of group
  $mysqli: Connection to the DB
  $username: Username of the user
  $groupID: ID of the group
  */
  function deleteOneTimeCodeGroup($mysqli, $username, $groupID){
    $stmt = $mysqli->prepare("SELECT UID FROM User_ WHERE Username=? ;");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    //$result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."'");
    if(is_bool($result) || mysqli_num_rows($result) == 0){
      return 'deleteOneTimeCode: Could not find user with username '.$username.'.';
    }

    $first_row = mysqli_fetch_row($result);

    $stmt = $mysqli->prepare("UPDATE Is_Member_Group SET OneTimeCode = NULL WHERE UID=? AND GroupID=? ;");
    $stmt->bind_param('ii', $first_row[0], $groupID);
    $stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("UPDATE Is_Member_Group SET OneTimeCode = NULL WHERE UID=".$first_row[0]." AND GroupID=".$groupID.";");
    return $mysqli->error;
  }

  /*
  This function deletes an entity of rates
  $mysqli: Connection to the DB object
  $RID: ID to be deleted
  */
  function deleteRates($mysqli, $RID){
    $stmt = $mysqli->prepare("DELETE FROM Rates WHERE RID=? ;");
    $stmt->bind_param('i', $RID);
    $stmt->execute();
    //$result = $stmt->get_result();
    //$mysqli->query("DELETE FROM Rates WHERE RID=".$RID);
  }

?>
