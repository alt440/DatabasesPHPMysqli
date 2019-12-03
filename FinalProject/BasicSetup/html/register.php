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


    // Register user
    if (true) {
        // receive all input values from the form
        $username = mysqli_real_escape_string($mysqli, filter_input(INPUT_POST, 'username'));
        $email = mysqli_real_escape_string($mysqli, $_POST['email']);
        $name = mysqli_real_escape_string($mysqli, $_POST['name']);
        $dob = mysqli_real_escape_string($mysqli, $_POST['DOB']);
        $password1 = mysqli_real_escape_string($mysqli, $_POST['password1']);
        $password2 = mysqli_real_escape_string($mysqli, $_POST['password2']);


        // by adding (array_push()) corresponding error unto $errors array
        if (empty($username)) { array_push($errors, "Username is required"); }
        if (empty($email)) { array_push($errors, "Email is required"); }
        if (empty($password1)) { array_push($errors, "Password is required"); }
        if ($password_1 != $password_2) {
              array_push($errors, "The two passwords do not match");
         }
        // echo print_r($errors) ;

         // Query the db to check if the username was already created or if the email is already in use.
         $user_check_query = "SELECT * FROM User_ WHERE Username='$username' OR Email='$email' LIMIT 1";
         $result = $mysqli->query($user_check_query);
         $user = mysqli_fetch_row($result);

        // Check if the user already exist.  If so, push to the array of errors that will be displayed.
        if ($user) {
            if ($user['username'] === $username) {
              array_push($errors, "Username already exists");
            }
            if ($user['email'] === $email) {
              array_push($errors, "email already exists");
            }
        }


        // Then, register the user as a participant if no errors were brought up.
        if (count($errors) == 0) {

            // HASHING DOES NOT WORK RIGHT NOW.
            //$password = md5($password1);//encrypt the password before saving in the database
            $query = "INSERT INTO User_ (Name, Username, Password, Email, DateOfBirth, PrivilegeLevel)
                              VALUES('$name', '$username', '$password1', '$email', '$dob', 0)";
            if($mysqli->query($query)){
                //echo "Account created sucessfully!";
                $_SESSION['username'] = $username;
                $_SESSION['success'] = "You are now logged in";
                $_SESSION['name'] = $name;
            header('location: homePage.php');
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
            }

        } else {
             echo print_r($errors) ;
        }
    }

?>
