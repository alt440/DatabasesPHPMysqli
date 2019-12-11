<?php
  //Alexandre Therrien
  session_start();
  require "../../database_layer_get.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  //verify if the event exists, and if so, reload the page of events with the new
  //information
  $eventTitle = $_REQUEST['searchEvent'];

  if(isset($_SESSION['searchUser'])){
    $_SESSION['searchUser']='';
  }

  $event = getEvent($mysqli, $eventTitle);
  if($event == 0){
    $_SESSION['Event']='';
  }
  else{
    $_SESSION['Event']=$event[3];
  }

  //header('Location: ../eventPage.php');
  exit();

?>
