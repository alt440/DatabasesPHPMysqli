<?php

  //this adds a comment to the list of comments for a post
  require "../../database_layer_delete.php";
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //$username = $_REQUEST['username'];
  $groupID = $_REQUEST['groupID'];

  $return_val = doesGroupExistID($mysqli, $groupID);
  if($return_val == 1){
    removeGroupID($mysqli, $groupID);
    echo json_encode(array("response"=>"1"));
  } else{
    echo json_encode(array("response"=>"Could not find group with ID ".$groupID."."));
  }
?>
