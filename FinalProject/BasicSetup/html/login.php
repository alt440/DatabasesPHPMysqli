<?php
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

//take json input
$json = $_POST['json'];
$decoded_json = json_decode($json);
$password = $decoded_json->password;
$username = $decoded_json->username;

if (empty($username)) {
	echo json_encode(array("response"=>"Username is required"));
} else if (empty($password)) {
	echo json_encode(array("response"=>"Password is required"));
} else{

  $query = "SELECT * FROM User_ WHERE Username='$username'";
	$stmt = $mysqli->prepare("SELECT * FROM User_ WHERE Username=?");
	$stmt->bind_param('s',$username);
	$stmt->execute();

  $results = $stmt->get_result();
  if (mysqli_num_rows($results) == 1) {
    //compare with password
    $auth = password_verify($password, mysqli_fetch_row($results)[2]);
    if($auth == true){
      $_SESSION['username'] = $username;
  	  $_SESSION['success'] = "You are now logged in";
  	  echo json_encode(array("response"=>"1"));
    }
    else{
      echo json_encode(array("response"=>"Wrong credentials!"));
    }
  }else {
    echo json_encode(array("response"=>"Wrong credentials!"));
  }
}

?>
