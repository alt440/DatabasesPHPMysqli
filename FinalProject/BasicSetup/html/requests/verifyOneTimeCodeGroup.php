<?php
  //Alexandre Therrien
  require "../../database_layer_get.php";
  require "../../database_layer_use_cases.php";
  require "../../database_layer_delete.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //first make sure all input is included
  $json = $_POST['json'];
  $decoded_json = json_decode($json);
  $val1 = $decoded_json->groupID;
  $val2 = $decoded_json->username;
  $val3 = $decoded_json->oneTimeCode;

  $result = getOneTimeCodeGroup($mysqli, $val2, $val1);

  if($result != 0){
    if($val3 == $result){
      //add user as member of the event
      setMemberToGroupID($mysqli, $val2, $val1);
      deleteOneTimeCodeGroup($mysqli, $val2, $val1);
      echo json_encode(array("response"=>'Hurray! You got in.'));
    } else{
      echo json_encode(array("response"=>'Oops! Wrong code.'));
    }
  }else{
    echo json_encode(array("response"=>'You do not have a one time code'));
  }

?>
