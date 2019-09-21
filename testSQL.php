<?php
	//connect with the database
	$mysqli = new mysqli("localhost", "root", "");
	//query to create a database with name comp353
	$sql = "CREATE DATABASE comp353;";
	//query the database with $sql command
	$mysqli->query($sql);
	//select database just created
	$mysqli->select_db("comp353");
	//query the database with the command in (). Creates table 'people'
  $mysqli->query("CREATE TABLE people (id smallint unsigned not null auto_increment, lastname varchar(20) not null, firstname varchar(20) not null, middle_name varchar(20), userID int not null, password int not null, constraint pk_people primary key (id) );");

	//query the database with the command in (). Creates table 'event'
	$mysqli->query("CREATE TABLE event (id smallint unsigned not null auto_increment, Event varchar(20), EventID int, start_date varchar(20), end_date varchar(20), AdminUserID int, constraint pk_event primary key (id));");

	//query the database with the command in (). Creates table 'role_of_people_in_the_event'
	$mysqli->query("CREATE TABLE role_of_people_in_the_event (id smallint unsigned not null auto_increment, userid int, EventID int, constraint pk_role primary key (id));");

	//open the file
	$fn = fopen("db19s-P1.csv", "r");
	//read line. go over line of +---+
	$result = fgets($fn);
	//read line. go over line of headers of table
	$result = fgets($fn);
	//get to the first line of data to put in our database
	$result = fgets($fn);

	//checks if the substring from index 0-1 is a + (indicates end of table)
	while(substr($result, 0, 1) !== '+'){
		//separate the line of data using the pipe |
		$results = explode("|",$result);
		//then query database to add the data into the table. If we get an error, print it.
		if($mysqli->query("INSERT INTO people (id, lastname, firstname, middle_name, userID, password) VALUES (null, '".$results[1]."', '".$results[2]."', '".$results[3]."', ".$results[4].", ".$results[5].")")){
			echo '<p>'.$mysqli->error.'</p>';
		}
		//print the data being put in the table people.
		echo '<p>'.$results[0].' '.$results[1].' '.$results[2].' '.$results[3].'</p>';
		echo '<p>'.$results[4].' '.$results[5].'</p>';
		//go to the next line of data.
		$result = fgets($fn);

	}

	//pass through next lines -- the + and the column title lines
	$result = fgets($fn);
	$result = fgets($fn);

	//verify if the line starts with a +
	while(substr($result, 0, 1) !== '+'){
		//separate the line with |
		$results = explode("|", $result);
		//ensure there is no error from the query. Print the error otherwise.
		if($mysqli->query("INSERT INTO event (id, Event, EventID, start_date, end_date, AdminUserID) VALUES (null, '".$results[1]."', ".$results[2].", '".$results[3]."', '".$results[4]."', ".$results[5].")") === FALSE){
			echo '<p>'.$mysqli->error.'</p>';
		}
		//go to the next line
		$result = fgets($fn);
	}

	//pass through the next lines -- the + and the column title lines
	$result = fgets($fn);
	$result = fgets($fn);

	//ensure the line does not start with a +
	while(substr($result, 0, 1) !== '+'){
		//separate the line with |
		$results = explode("|", $result);
		//ensure no error in query. Print error otherwise.
		if($mysqli->query("INSERT INTO role_of_people_in_the_event (id, userid, EventID) VALUES (null, ".$results[1].", ".$results[2].")") === FALSE){
			echo '<p>'.$mysqli->error.'</p>';
		}

		//go to the next line.
		$result = fgets($fn);
	}

	//close the connection.
	$mysqli->close();

?>
