<?php

  require "../../database_layer_get.php";
  require "../../database_layer.php";
  require "../../database_layer_delete.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //first make sure all input is included
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->UID;
  $val3 = $decoded_json->name;
  $val4 = $decoded_json->DOB;
  $val5 = $decoded_json->email;
  $val6 = $decoded_json->groupID;

  if($val1=='' || $val3=='' || $val4=='' || $val5==''){
    echo json_encode(array("response"=>'Some parameters were not set'));
  }
  else{
    $result = getUserWithID($mysqli, $val1);
    //verify equality of each
    if(strcmp($val3, $result[4])==0 && strcmp($val4, $result[5])==0 && strcmp($val5, $result[3])==0){
      removeUserFromGroupID($mysqli, $result[1], $val6);
      //now send an email to the guy
      sendEmailID($mysqli, $val1, $val1, 'Just got removed from Group '.getGroupName($mysqli,$val6), 'Hi there! This is a message to tell you you were removed from '.getGroupName($mysqli,$val6));
      echo json_encode(array("response" => 'OK'));
    }
    else{
      echo json_encode(array("response"=>'Some parameters were not the same'));
    }
  }

?>
