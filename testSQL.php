<?php
	$mysqli = new mysqli("localhost", "root", "");
	$sql = "CREATE DATABASE comp353;";
	$mysqli->query($sql);
	$mysqli->select_db("comp353");
        $mysqli->query("CREATE TABLE people (id smallint unsigned not null auto_increment, lastname varchar(20) not null, firstname varchar(20) not null, middle_name varchar(20), userID int not null, password int not null, constraint pk_people primary key (id) );");

	$mysqli->query("CREATE TABLE event (id smallint unsigned not null auto_increment,
Event varchar(20), EventID int, start_date varchar(20),
end_date varchar(20), AdminUserID int, constraint pk_event primary key (id));");

	$mysqli->query("CREATE TABLE role_of_people_in_the_event (id smallint unsigned not null
auto_increment, userid int, EventID int, constraint pk_role primary key (id));");

	//open the file
	$fn = fopen("db19s-P1.csv", "r");
	//read line. useless line of +---+
	$result = fgets($fn);
	//read line. useless line of header of table
	$result = fgets($fn);
	$result = fgets($fn);

	//echo '<p>'.(substr('+--------------+', 0, 1) === '+').'</p>';
	//$i = 0;
	//read line. now we have some useful info
	while(substr($result, 0, 1) !== '+'){
		//first get the line, then separate with the pipes
		
		$results = explode("|",$result);
		//then enter the query for it.
		$mysqli->query("INSERT INTO people (id, lastname, firstname, middle_name, userID, password) VALUES (null, '".$results[1]."', '".$results[2]."', '".$results[3]."', ".$results[4].", ".$results[5].")"); 
		echo '<p>'.$results[0].' '.$results[1].' '.$results[2].' '.$results[3].'</p>';
		echo '<p>'.$results[4].' '.$results[5].'</p>';
		$result = fgets($fn);

	} 
	
	//pass through next lines -- the + and the column title lines
	$result = fgets($fn);
	$result = fgets($fn);
	
	while(substr($result, 0, 1) !== '+'){

		$results = explode("|", $result);
		if($mysqli->query("INSERT INTO event (id, Event, EventID, start_date, end_date, AdminUserID) VALUES (null, '".$results[1]."', ".$results[2].", '".$results[3]."', '".$results[4]."', ".$results[5].")") === FALSE){
			echo '<p>'.$mysqli->error.'</p>';
		}

		$result = fgets($fn);
	}

	//pass through the next lines -- the + and the column title lines
	$result = fgets($fn);
	$result = fgets($fn);

	while(substr($result, 0, 1) !== '+'){

		$results = explode("|", $result);
		if($mysqli->query("INSERT INTO role_of_people_in_the_event (id, userid, EventID) VALUES (null, ".$results[1].", ".$results[2].")") === FALSE){
			echo '<p>'.$mysqli->error.'</p>';
		}

		$result = fgets($fn);
	}
	
	$mysqli->close();

?>
