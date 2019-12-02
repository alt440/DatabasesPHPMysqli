<?php

  /*
  This file contains the delete operations needed for the project.
  */


  /*
  Removes a user from an event (for admin, event manager)
  $mysqli: Connection to the DB object
  $username: Username of the user
  $eventTitle: Title of the event we want to get the user out
  */
  function removeUserFromEvent($mysqli, $username, $eventTitle){
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'removeUserFromEvent: User with username '.$username.' was not found.';
    }

    $result2 = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row_2 = mysqli_fetch_row($result2);

    if(is_bool($first_row_2[0])){
      return 'removeUserFromEvent: Event with title '.$eventTitle.' was not found.';
    }

    //now remove the user with Is_Member_Event table
    $mysqli->query("DELETE FROM Is_Member_Event WHERE UID=".$first_row[0]." AND EventID=".$first_row_2[0].";");
    //also remove all groups that the user belongs to in this event...
    $result3 = $mysqli->query("SELECT GroupID FROM Group_ WHERE MainEventID=".$first_row_2[0].";");
    //now for all of them, remove the user's membership to the group
    while($row=mysqli_fetch_row($result3)){
      $mysqli->query("DELETE FROM Is_Member_Group WHERE UID=".$first_row[0]." AND GroupID=".$row[0].";");
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
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'removeUserFromGroup: User with username '.$username.' was not found.';
    }

    $result2 = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");
    $first_row_2 = mysqli_fetch_row($result2);

    if(is_bool($first_row_2[0])){
      return 'removeUserFromGroup: Group with name '.$groupName.' was not found.';
    }

    $mysqli->query("DELETE FROM Is_Member_Group WHERE UID=".$first_row[0]." AND GroupID=".$first_row_2[0].";");
    return 'removeUserFromGroup: '.$mysqli->error;
  }

  /*
  Removes a user from the system.
  $mysqli: Connection to the DB object
  $username: Username of the user
  */
  function removeUser($mysqli, $username){
    //first find his UID
    $result = $mysqli->query("SELECT UID FROM User_ WHERE Username='".$username."';");
    $first_row = mysqli_fetch_row($result);
    if(is_bool($first_row[0])){
      return 'removeUser: User with username '.$username.' does not exist.';
    }

    //now delete all:Emails, Event member, Group member, Content, Comment. Because there are foreign keys, I cannot replace certain values by bogus values
    //start with emails: easiest
    $mysqli->query("DELETE FROM Emails WHERE SourceUser=".$first_row[0]." OR TargetUser=".$first_row[0].";");
    //now go in post/post comment and find all content published by this user
    $result2 = $mysqli->query("SELECT CID FROM Post WHERE UID=".$first_row[0].";");
    //do the same for post_comment
    $result3 = $mysqli->query("SELECT CoID FROM Post_Comment WHERE UID=".$first_row[0].";");
    //find all comments on posts
    $result4 = $mysqli->query("SELECT Post_Comment.CoID FROM Post_Comment INNER JOIN Comment ON Comment.CoID=Post_Comment.CoID WHERE CID= ANY(SELECT CID FROM Post WHERE UID=".$first_row[0].");");
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
    $result = $mysqli->query("SELECT GroupID FROM Group_ WHERE GroupName='".$groupName."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'removeGroup: Could not find Group with GroupName '.$groupName.'.';
    }

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
  Remove an event and all its users
  $mysqli: Connection to the DB object
  $eventTitle: Title of the event
  */
  function removeEvent($mysqli, $eventTitle){
    //find the eventID, then all the groups associated with it
    $result = $mysqli->query("SELECT EventID FROM Event_ WHERE Title='".$eventTitle."';");
    $first_row = mysqli_fetch_row($result);

    if(is_bool($first_row[0])){
      return 'removeEvent: Event with title '.$eventTitle.' was not found.';
    }

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

?>
