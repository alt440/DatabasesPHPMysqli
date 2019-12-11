<?php
  $salt = "arrrgus";
  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");

  //select database
  $mysqli->select_db("comp353_final_project");

  $password = 'aaa';
  $val_hashed = md5($password.$salt);
  var_dump($val_hashed);

  $username='aaa';
  $stmt = $mysqli->prepare("SELECT * FROM User_ WHERE Username=?");
	$stmt->bind_param('s',$username);
	$stmt->execute();
  $results = $stmt->get_result();
  $hash = mysqli_fetch_row($results)[2];
  echo $hash." ";
  echo strcmp($val_hashed, $hash)."<br>";
  //echo password_verify($password, $hash)."<br>";

?>
