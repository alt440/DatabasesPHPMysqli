<?php
  //Alexandre Therrien
  require "../../database_layer_get.php";
  require "../../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //first make sure all input is included
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->UID;
  $val2 = $decoded_json->eventTitle;
  $val3 = $decoded_json->name;
  $val4 = $decoded_json->DOB;
  $val5 = $decoded_json->email;

  if($val1=='' || $val3=='' || $val4=='' || $val5==''){
    echo json_encode(array("response"=>'Some parameters were not set'));
  }
  else{
    $result = getUserWithID($mysqli, $val1);
    //verify equality of each
    if(strcmp($val3, $result[4])==0 && strcmp($val4, $result[5])==0 && strcmp($val5, $result[3])==0){
      //get one time code
      $rand_val = rand(1000000,10000000000);
      addUserToEventWithCode($mysqli, $result[1], $val2, $rand_val);
      //now send an email to the guy
      sendEmailID($mysqli, $val1, $val1, 'Just got invited to Event '.$val2, 'Hi there! This is a message to tell you you were invited to '.$val2.'. Here is your one time code: '.$rand_val);
      echo json_encode(array("response" => 'OK'));
    }
    else{
      echo json_encode(array("response"=>'Some parameters were not the same'));
    }
  }

?>
