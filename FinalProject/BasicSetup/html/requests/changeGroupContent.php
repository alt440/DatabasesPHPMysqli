<?php

  session_start();

  $json = $_POST['json'];
  $decoded_json = json_decode($json);

  $i = $decoded_json->contentnb;

  $_SESSION['Group']=$i;

?>
