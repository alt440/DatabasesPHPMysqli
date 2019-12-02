<?php

  /*
  This file is used to set the different parameters of the user. Should be used
  for GUI on p.4 of ProjectCOMP353.odt
  */
  require "../database_layer.php";
  require "../database_layer_use_cases.php";
  //this displays our data.
  require "../database_layer_get.php";

  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

  //select database
  $mysqli->select_db("comp353_final_project");

  //create new user
  $return_val=addUser($mysqli, 'testGetSet','testGetSet','test@test.com','testbl testrr','1977-12-31',0);
  echo $return_val."<br>";

  //now modify all of his/her params
  echo 'Values as the user was set: '."<br>";
  $array_vals = getUser($mysqli, 'testGetSet');
  $params = array("UID","Username","Password","Email","Name","Date of Birth","Privilege Level", "Bank Name","Account Number","Credit Card Number","Address","Phone Number");

  for($i=0;$i<count($params);$i++){
    echo $params[$i].': '.$array_vals[$i]."<br>";
  }
  echo "<br>";

  //now set all the possible values (the rest cannot be changed)
  updateUserEmail($mysqli, 'testGetSet','testme@t.com');
  updateUser_Name($mysqli, 'testGetSet','OrangeBorange');
  updateUserAddress($mysqli, 'testGetSet', 'Rue de la Police');
  updateUserBankName($mysqli, 'testGetSet', 'National Bank');
  updateUserPassword($mysqli, 'testGetSet', 'testGetSet','test');
  updateUserPhoneNumber($mysqli, 'testGetSet', '911');
  updateUserAccountNumber($mysqli, 'testGetSet', 12333322222);
  updateUserCreditCardNumber($mysqli, 'testGetSet', 22222333330);

  //now print the new values
  echo 'Values after having modified the user: '."<br>";
  $array_vals = getUser($mysqli, 'testGetSet');
  for($i=0;$i<count($params);$i++){
    echo $params[$i].': '.$array_vals[$i]."<br>";
  }

?>
