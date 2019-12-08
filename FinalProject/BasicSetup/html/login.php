<?php
    // author: Francois David 40046319
    session_start();

    // initializing variables
    $username = "";
    $email    = "";
    $errors = array();

    // Connection to the database.
    /*$mysqli = new mysqli("urc353.encs.concordia.ca", "urc353_2", "AqtjPG");
    $mysqli->select_db("urc353_2");*/
    $mysqli = new mysqli("localhost", "root", "");
  	$mysqli->select_db("comp353_final_project");

   // LOGIN USER
if (isset($_POST['login_user'])) {
  $username = mysqli_real_escape_string($mysqli, filter_input(INPUT_POST, 'username'));
  $password = mysqli_real_escape_string($mysqli, filter_input(INPUT_POST,'password'));

  if (empty($username)) {
  	array_push($errors, "Username is required");
  }
  if (empty($password)) {
  	array_push($errors, "Password is required");
  }

  if (count($errors) == 0) {
  	//$password = md5($password);
  	$query = "SELECT * FROM User_ WHERE Username='$username' AND Password='$password'";
  	$results = $mysqli->query($query);
  	if (mysqli_num_rows($results) == 1) {
  	  $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  header('location: homePage.php');
  	}else {
  		array_push($errors, "Wrong username/password combination");
  	}
  }
}
?>
