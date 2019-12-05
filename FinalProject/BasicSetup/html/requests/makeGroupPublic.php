<?php

  require "../../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $groupID = $_REQUEST['groupID'];

  setGroupPublic($mysqli, $groupID);

?>
