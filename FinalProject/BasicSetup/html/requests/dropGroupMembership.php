<?php

  //this adds a comment to the list of comments for a post
  require "../../database_layer_delete.php";
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $username = $_REQUEST['username'];
  $groupID = $_REQUEST['groupID'];

  $return_val_0 = doesUsernameExist($mysqli, $username);
  $return_val_1 = doesGroupExistID($mysqli, $groupID);

  if($return_val_0 == 1 && $return_val_1 == 1){
    removeUserFromGroupID($mysqli, $username, $groupID);
    echo json_encode(array("response"=>"1"));
  } else if($return_val_0 == 0){
    echo json_encode(array("response"=>"Could not find user with username ".$username."."));
  } else if($return_val_1 == 0){
    echo json_encode(array("response"=>"Could not find group with ID ".$groupID."."));
  }


?>
