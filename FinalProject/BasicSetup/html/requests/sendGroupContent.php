<?php

  require "../../database_layer_get.php";
  require "../../database_layer.php";
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $json = $_POST['json'];
  $decoded_json = json_decode($json);

  $val1 = $decoded_json->username;
  $val2 = $decoded_json->eventID;
  $val3 = $decoded_json->groupID;
  $val4 = $decoded_json->replyString;

  if(strcmp($val2,"missing")==0){
    $val2 = getGroupMainEventID($mysqli, $val3)[0];
  }

  //do input validation first
  if(strlen($val4) > 500){
    echo json_encode(array("response"=>"Content is too long! Reduce length"));
  } else{
    //make sure event exists
    $return_val = doesEventExistID($mysqli, $val2);
    if($return_val == 1){
      //make sure group ID exists
      $return_val = doesGroupExistID($mysqli, $val3);
      if($return_val == 1){
        $return_val = addContentWithIDs($mysqli, 0, '', $val4, $val2, $val3, $val1);
        if(strstr($return_val, 'username') == false){
          echo json_encode(array("response"=>"1"));
        } else{
          echo json_encode(array("response"=>"Could not find user with username ".$username."."));
        }
      } else{
          echo json_encode(array("response"=>"Could not find group with ID ".$val3."."));
      }

    } else{
      echo json_encode(array("response"=>"The group with ID ".$val3." does not exist."));
    }

  }

?>
