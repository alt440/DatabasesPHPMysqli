<!--
COMP 353 - F / FALL 2019
Dr. Desai

PROJECT 1

Mair ELBAZ, 40004558
Daniel VIGNY-PAU, 40034769
Francois DAVID, 40046319
Alexandre THERRIEN, 40057134
Charles-Antoine GUITE, 40063098
-->

<?php
	//connect with the database
	$mysqli = new mysqli("localhost", "root", "");
	//query to create a database with name comp353
	$sql = "CREATE DATABASE comp353;";
	//query the database with $sql command
	$mysqli->query($sql);
	//select database just created
	$mysqli->select_db("comp353");

	//dropping tables to avoid adding duplicate data and using clean tables everytime program runs
	$mysqli->query("DROP TABLE people");
	$mysqli->query("DROP TABLE event");
	$mysqli->query("DROP TABLE role_of_people_in_the_event");

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

	//printing a title
	$header = "Printing all data being added into the 'people' table";
	echo '<h4>'.$header.'</h4>';
	//checks if the substring from index 0-1 is a + (indicates end of table)
	while(substr($result, 0, 1) !== '+'){
		//separate the line of data using the pipe |
		$results = explode("|",$result);
		//then query database to add the data into the table. If we get an error, print it.
		if($mysqli->query("INSERT INTO people (id, lastname, firstname, middle_name, userID, password) VALUES (null, '".$results[1]."', '".$results[2]."', '".$results[3]."', ".$results[4].", ".$results[5].")")){
			echo '<p>'.$mysqli->error.'</p>';
		}
		//print the data being put in the table people.
		echo '<p>'.$results[0].' '.$results[1].' '.$results[2].' '.$results[3].' '.$results[4].' '.$results[5].'</p>';
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
	echo '</br>';

	$title1 = "Fetching all data";
	$empty = "No results";

	echo '<h2>'.$title1.'</h2>';

	$peopleHeader = "TABLE 1: People";
	echo '<h3>'.$peopleHeader.'</h3>';
	//printing the table of people, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>ID</th><th>Last name</th><th>First name</th><th>Middle name</th><th>User ID</th><th>Password</th></tr>';
	//selecting data from the table
	$queryPeople = "SELECT * FROM people ORDER BY id ASC";
	$resultPeople = $mysqli->query($queryPeople);
	//checking if the results from the query return any rows
	if ($resultPeople->num_rows > 0) {
		while ($rowPeople = $resultPeople->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'people'
			echo '<tr><td>'.$rowPeople['id'].'</td><td>'.$rowPeople['lastname'].'</td><td>'.$rowPeople['firstname'].'</td><td>'.$rowPeople['middle_name'].'</td><td>'.$rowPeople['userID'].'</td><td>'.$rowPeople['password'].'</td></tr>';
		}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	$eventHeader = "TABLE 2: Event";
	echo '<h3>'.$eventHeader.'</h3>';
	//printing the table of people, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>ID</th><th>Event</th><th>Event ID</th><th>Start date</th><th>End date</th><th>Admin user ID</th></tr>';
	//selecting data from the table
	$queryEvent = "SELECT * FROM event ORDER BY id ASC";
	$resultEvent = $mysqli->query($queryEvent);
	//checking if the results from the query return any rows
	if ($resultEvent->num_rows > 0) {
		while ($rowEvent = $resultEvent->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'people'
			echo '<tr><td>'.$rowEvent['id'].'</td><td>'.$rowEvent['Event'].'</td><td>'.$rowEvent['EventID'].'</td><td>'.$rowEvent['start_date'].'</td><td>'.$rowEvent['end_date'].'</td><td>'.$rowEvent['AdminUserID'].'</td></tr>';
		}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	$role_of_people_in_the_eventHeader = "TABLE 3: Role of people in the event";
	echo '<h3>'.$role_of_people_in_the_eventHeader.'</h3>';
	//printing the table of role of people in the event, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>ID</th><th>User ID</th><th>Event ID</th></tr>';
	//selecting data from the table
	$queryRole = "SELECT * FROM role_of_people_in_the_event ORDER BY id ASC";
	$resultRole = $mysqli->query($queryRole);
	//checking if the results from the query return any rows
	if ($resultRole->num_rows > 0) {
		while ($rowRole= $resultRole->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'Role'
			echo '<tr><td>'.$rowRole['id'].'</td><td>'.$rowRole['userid'].'</td><td>'.$rowRole['EventID'].'</td></tr>';}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	//EXAMPLES OF ACCESSING DATA IN THE TABLES

	//table with people, only displaying first name, last name, and middle name
	$peopleHeader = "TABLE 4: People - only displaying first name, last name, and middle name, sorted by last name reverse alphabetically";
	echo '<h3>'.$peopleHeader.'</h3>';
	//printing the table of people, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>Last name</th><th>First name</th><th>Middle name</th></tr>';
	//selecting data from the table
	$queryPeople = "SELECT firstname, lastname, middle_name FROM people ORDER BY lastname DESC";
	$resultPeople = $mysqli->query($queryPeople);
	//checking if the results from the query return any rows
	if ($resultPeople->num_rows > 0) {
		while ($rowPeople = $resultPeople->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'people'
			echo '<tr><td>'.$rowPeople['lastname'].'</td><td>'.$rowPeople['firstname'].'</td><td>'.$rowPeople['middle_name'].'</td></tr>';
		}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	//table with people with a middle name only
	$peopleHeader = "TABLE 5: People - only people with middle names, ordered by middle name alphabetically";
	echo '<h3>'.$peopleHeader.'</h3>';
	//printing the table of people, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>ID</th><th>Last name</th><th>First name</th><th>Middle name</th><th>User ID</th><th>Password</th></tr>';
	//selecting data from the table
	$queryPeople = "SELECT * FROM people WHERE middle_name != '' ORDER BY middle_name ASC";
	$resultPeople = $mysqli->query($queryPeople);
	//checking if the results from the query return any rows
	if ($resultPeople->num_rows > 0) {
		while ($rowPeople = $resultPeople->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'people'
			echo '<tr><td>'.$rowPeople['id'].'</td><td>'.$rowPeople['lastname'].'</td><td>'.$rowPeople['firstname'].'</td><td>'.$rowPeople['middle_name'].'</td><td>'.$rowPeople['userID'].'</td><td>'.$rowPeople['password'].'</td></tr>';
		}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	//table with people with a last name that starts with the letter 'L'
	$peopleHeader = "TABLE 6: People - only people where last name starts with 'L', ordered by last name alphabetically";
	echo '<h3>'.$peopleHeader.'</h3>';
	//printing the table of people, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>ID</th><th>Last name</th><th>First name</th><th>Middle name</th><th>User ID</th><th>Password</th></tr>';
	//selecting data from the table
	$queryPeople = "SELECT * FROM people WHERE lastname LIKE 'L%' ORDER BY lastname ASC";
	$resultPeople = $mysqli->query($queryPeople);
	//checking if the results from the query return any rows
	if ($resultPeople->num_rows > 0) {
		while ($rowPeople = $resultPeople->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'people'
			echo '<tr><td>'.$rowPeople['id'].'</td><td>'.$rowPeople['lastname'].'</td><td>'.$rowPeople['firstname'].'</td><td>'.$rowPeople['middle_name'].'</td><td>'.$rowPeople['userID'].'</td><td>'.$rowPeople['password'].'</td></tr>';
		}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	//table with people with a user ID between 1000000 and 2000000
	$peopleHeader = "TABLE 7: People - only people with User ID between 1000000 and 2000000, sorted by User ID";
	echo '<h3>'.$peopleHeader.'</h3>';
	//printing the table of people, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>ID</th><th>Last name</th><th>First name</th><th>Middle name</th><th>User ID</th><th>Password</th></tr>';
	//selecting data from the table
	$queryPeople = "SELECT * FROM people WHERE userID BETWEEN 1000000 AND 2000000 ORDER BY userID ASC";
	$resultPeople = $mysqli->query($queryPeople);
	//checking if the results from the query return any rows
	if ($resultPeople->num_rows > 0) {
		while ($rowPeople = $resultPeople->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'people'
			echo '<tr><td>'.$rowPeople['id'].'</td><td>'.$rowPeople['lastname'].'</td><td>'.$rowPeople['firstname'].'</td><td>'.$rowPeople['middle_name'].'</td><td>'.$rowPeople['userID'].'</td><td>'.$rowPeople['password'].'</td></tr>';
		}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	//table with events with no end date
	$eventHeader = "TABLE 8: Events with no end date";
	echo '<h3>'.$eventHeader.'</h3>';
	//printing the table of people, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>ID</th><th>Event</th><th>Event ID</th><th>Start date</th><th>End date</th><th>Admin user ID</th></tr>';
	//selecting data from the table
	$queryEvent = "SELECT * FROM event WHERE end_date = '' ORDER BY id ASC";
	$resultEvent = $mysqli->query($queryEvent);
	//checking if the results from the query return any rows
	if ($resultEvent->num_rows > 0) {
		while ($rowEvent = $resultEvent->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'people'
			echo '<tr><td>'.$rowEvent['id'].'</td><td>'.$rowEvent['Event'].'</td><td>'.$rowEvent['EventID'].'</td><td>'.$rowEvent['start_date'].'</td><td>'.$rowEvent['end_date'].'</td><td>'.$rowEvent['AdminUserID'].'</td></tr>';
		}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	//table with events starting in the year 2011
	$eventHeader = "TABLE 9: Events starting in the year 2011";
	echo '<h3>'.$eventHeader.'</h3>';
	//printing the table of people, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>ID</th><th>Event</th><th>Event ID</th><th>Start date</th><th>End date</th><th>Admin user ID</th></tr>';
	//selecting data from the table
	$queryEvent = "SELECT * FROM event WHERE start_date LIKE '2011%' ORDER BY id ASC";
	$resultEvent = $mysqli->query($queryEvent);
	//checking if the results from the query return any rows
	if ($resultEvent->num_rows > 0) {
		while ($rowEvent = $resultEvent->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'people'
			echo '<tr><td>'.$rowEvent['id'].'</td><td>'.$rowEvent['Event'].'</td><td>'.$rowEvent['EventID'].'</td><td>'.$rowEvent['start_date'].'</td><td>'.$rowEvent['end_date'].'</td><td>'.$rowEvent['AdminUserID'].'</td></tr>';
		}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	//table with events with users 3060862 and 7034113
	$role_of_people_in_the_eventHeader = "TABLE 10: Role of people in the event only with users 3060862 and 7034113";
	echo '<h3>'.$role_of_people_in_the_eventHeader.'</h3>';
	//printing the table of role of people in the event, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>ID</th><th>User ID</th><th>Event ID</th></tr>';
	//selecting data from the table
	$queryRole = "SELECT * FROM role_of_people_in_the_event WHERE userid = 3060862 OR userid = 7034113 ORDER BY id ASC";
	$resultRole = $mysqli->query($queryRole);
	//checking if the results from the query return any rows
	if ($resultRole->num_rows > 0) {
		while ($rowRole= $resultRole->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'Role'
			echo '<tr><td>'.$rowRole['id'].'</td><td>'.$rowRole['userid'].'</td><td>'.$rowRole['EventID'].'</td></tr>';}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	//table with events only with events with Event ID 4
	$role_of_people_in_the_eventHeader = "TABLE 11: Role of people in the event only with Event ID 4";
	echo '<h3>'.$role_of_people_in_the_eventHeader.'</h3>';
	//printing the table of role of people in the event, ordered by ID in ascending order
	echo '<table border="1" width="50%">';
	//printing the table header
	echo '<tr><th>ID</th><th>User ID</th><th>Event ID</th></tr>';
	//selecting data from the table
	$queryRole = "SELECT * FROM role_of_people_in_the_event WHERE EventID = 4 ORDER BY id ASC";
	$resultRole = $mysqli->query($queryRole);
	//checking if the results from the query return any rows
	if ($resultRole->num_rows > 0) {
		while ($rowRole= $resultRole->fetch_assoc()) {
			//printing the table row with the data of each entry in the table 'Role'
			echo '<tr><td>'.$rowRole['id'].'</td><td>'.$rowRole['userid'].'</td><td>'.$rowRole['EventID'].'</td></tr>';}
	} else {
		//print that there were no results if there are 0 resulting rows
		echo '<tr><td>'.$empty.'</td></tr>';
	}
	echo '</table>';
	echo '</br>';

	//close the connection.
	$mysqli->close();
?>
