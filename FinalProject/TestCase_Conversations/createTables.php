<?php
	//connect with the database
	$mysqli = new mysqli("localhost", "root", "");
	//query to create a database with name comp353
	$sql = "CREATE DATABASE comp353_final_project;";
	//query the database with $sql command
	//$mysqli->query($sql);
	//select database just created
	$mysqli->select_db("comp353_final_project");
	//dropping tables to avoid adding duplicate data and using clean tables everytime program runs
	$mysqli->query("DROP TABLE Group_");
	$mysqli->query("DROP TABLE Content");
	$mysqli->query("DROP TABLE Is_Member_Event");
	$mysqli->query("DROP TABLE Is_Member_Group");
	$mysqli->query("DROP TABLE Participant");
	$mysqli->query("DROP TABLE Event_");
	$mysqli->query("DROP TABLE Post");
	$mysqli->query("DROP TABLE Post_Comment");
	$mysqli->query("DROP TABLE Comment");
	//query the database with the command in (). 
	$mysqli->query("CREATE TABLE Event_ (EventID int unsigned not null auto_increment, Status varchar(20) not null, Date varchar(20) not null, Title varchar(20) not null, ExpiryDate varchar(20) not null, primary key (EventID));");
  	$mysqli->query("CREATE TABLE Group_ (GroupID int unsigned not null auto_increment, MainEventID int unsigned not null, GroupName varchar(20) not null, foreign key (MainEventID) references Event_(EventID) on delete cascade, primary key (GroupID));");
	//I added the EventID and GroupID in the Content table because it made the system so much easier ^^
	$mysqli->query("CREATE TABLE Content (CID int unsigned not null auto_increment, PermissionType smallint not null, TimeStamp int(11) not null, replyImage blob, replyString varchar(500), EventID int unsigned not null, GroupID int unsigned, foreign key (EventID) references Event_(EventID) on delete cascade, foreign key (GroupID) references Group_(GroupID) on delete cascade, primary key (CID));");
	//query the database with the command in (). 
	$mysqli->query("CREATE TABLE Participant (PID int unsigned not null auto_increment, Username varchar(20) not null, Password varchar(20) not null, Email varchar(30) not null, Name varchar(50) not null, primary key (PID));");
	//this table determines which participant belongs to which group. hasSeenLastMessage has been added
	//to know if the members have seen the last message. If not a member, set to false or 0.
	$mysqli->query("CREATE TABLE Is_Member_Group (GroupID int unsigned not null, PID int unsigned not null, requestStatus varchar(10), hasSeenLastMessage boolean not null, primary key (GroupID, PID), foreign key (GroupID) references Group_(GroupID) on delete cascade, foreign key (PID) references Participant(PID) on delete cascade);");

	//this table determines which participant belongs to which event. hasSeenLastMessage has been added
	//to know if the members have seen the last message. If not a member, set to false or 0.
	$mysqli->query("CREATE TABLE Is_Member_Event (EventID int unsigned not null, PID int unsigned not null, requestStatus varchar(10), hasSeenLastMessage boolean not null, primary key (EventID, PID), foreign key (EventID) references Event_(EventID) on delete cascade, foreign key (PID) references Participant(PID) on delete cascade);");

	//this table determines who posted a certain content. Since there is always only one person that
	//posts a certain content, the primary key is only CID, not (CID, PID).
	$mysqli->query("CREATE TABLE Post (CID int unsigned not null, PID int unsigned not null, primary key (CID), foreign key (CID) references Content(CID) on delete cascade, foreign key (PID) references Participant(PID) on delete cascade);");

	//This tables is used whenever comments are made on certain Event posts. The PermissionType of a
	//post must be set to another value than 0 so comments can be made.
	$mysqli->query("CREATE TABLE Comment (CoID int unsigned not null auto_increment, CID int unsigned not null, TimeStamp int(11) not null, replyImage blob, replyString varchar(500), foreign key (CID) references Content(CID) on delete cascade, primary key (CoID));");
	//This table determines who posted a certain comment. It required to be different than Post, because
	//I cannot put a NULL value in the composite key if the content posted is not a comment.
	$mysqli->query("CREATE TABLE Post_Comment (CoID int unsigned not null, PID int unsigned not null,
foreign key (CoID) references Comment(CoID) on delete cascade, foreign key (PID) references Participant(PID) on delete cascade, primary key (CoID));"); 
	
	//close the connection.
	$mysqli->close();
?>
