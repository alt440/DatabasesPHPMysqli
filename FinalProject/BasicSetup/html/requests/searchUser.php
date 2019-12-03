<?php

  session_start();
  require "../../database_layer_get.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //verify if the event exists, and if so, reload the page of events with the new
  //information
  $username = $_REQUEST['searchUser'];

  if(isset($_SESSION['Event'])){
    $_SESSION['Event']='';
  }

  $user = getUser($mysqli, $username);
  if($user == 0){
    $_SESSION['searchUser']='';
  }
  else{
    $_SESSION['searchUser']=$user[1];
  }

  //header('Location: ../eventPage.php');
  exit();

?>
