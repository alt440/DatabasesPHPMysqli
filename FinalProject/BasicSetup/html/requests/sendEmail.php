<?php
  //Alexandre Therrien
  require "../../database_layer.php";
  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $source = $_REQUEST['sourceUser'];
  $target = $_REQUEST['targetUser'];
  $title = $_REQUEST['titleEmail'];
  $content = $_REQUEST['contentEmail'];

  $return_val_0 = doesUsernameExist($mysqli, $source);
  $return_val_1 = doesUsernameExist($mysqli, $target);
  $return_val_2 = strlen($title)<100?1:0;
  $return_val_3 = strlen($content)<1000?1:0;

  if($return_val_0 == 1 && $return_val_1 == 1 && $return_val_2 == 1 && $return_val_3 == 1){
    sendEmail($mysqli, $source, $target, $title, $content);
    echo json_encode(array("response"=>"1"));
  } else if($return_val_0 == 0){
    echo json_encode(array("response"=>"User with username ".$source." does not exist."));
  } else if($return_val_1 == 0){
    echo json_encode(array("response"=>"User with username ".$target." does not exist."));
  } else if($return_val_2 == 0){
    echo json_encode(array("response"=>"Title is too long! Needs to be under 100 characters."));
  } else if($return_val_3 == 0){
    echo json_encode(array("response"=>"Content is too long! Needs to be under 1000 characters."));
  }



?>
