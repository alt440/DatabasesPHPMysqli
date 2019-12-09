<?php

  //this adds a comment to the list of comments for a post
  require "../../database_layer.php";
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $username = $_REQUEST['username'];
  $privilegelevel = $_REQUEST['privilegeLevel'];
  $replyString = $_REQUEST['replyString'];
  $eventTitle = $_REQUEST['eventTitle'];

  //do input validation first
  if($privilegelevel > 2 || $privilegelevel < 0){
    echo json_encode(array("response"=>"Incorrect privilege level: Number is between 0 and 2"));
  } else if(strlen($replyString) > 500){
    echo json_encode(array("response"=>"Content is too long! Reduce length"));
  } else{
    //make sure event exists
    $return_val = doesEventExist($mysqli, $eventTitle);
    if($return_val == 1){
      $return_val = addContent($mysqli, $privilegelevel, '', $replyString, $eventTitle, '', $username);
      if(strstr($return_val, 'username') == false){
        echo json_encode(array("response"=>"1"));
      } else{
        echo json_encode(array("response"=>"Could not find user with username ".$username."."));
      }
    } else{
      echo json_encode(array("response"=>"The event with title ".$eventTitle." does not exist."));
    }

  }



?>
