<?php
	//connect with the database
	$mysqli = new mysqli("localhost", "root", "");

	//select database
	$mysqli->select_db("comp353_final_project");

	//remove all from the tables
	$mysqli->query("DELETE * FROM Participant");
	$mysqli->query("DELETE * FROM Group_");
	$mysqli->query("DELETE * FROM Content");
	$mysqli->query("DELETE * FROM Is_Member_Event");
	$mysqli->query("DELETE * FROM Is_Member_Group");
	$mysqli->query("DELETE * FROM Event_");
	$mysqli->query("DELETE * FROM Post");

	//now populate tables. First, add 'participants'
	$mysqli->query("INSERT INTO Participant (PID, Username, Password, Email, Name) values (1, 'aaa', 'aaa', 'aaa@a.com', 'aaa');");
	$mysqli->query("INSERT INTO Participant (PID, Username, Password, Email, Name) values (2, 'bbb', 'bbb', 'bbb@b.com', 'bbb');");
	$mysqli->query("INSERT INTO Participant (PID, Username, Password, Email, Name) values (3, 'ccc', 'ccc', 'ccc@c.com', 'ccc');");

	//create an event which includes these 3 members
	$mysqli->query("INSERT INTO Event_ (EventID, Status, Date, Title, ExpiryDate) values (1, 'ACTIVE', '2010-10-9', 'My First Event', '2030-10-9');");

	//add the 3 members to the event
	//hasSeenLastMessage indicates whether the information should be marked as an unread message
	$mysqli->query("INSERT INTO Is_Member_Event (EventID, PID, requestStatus, hasSeenLastMessage) values (1, 1, 'Member', 1);");
	$mysqli->query("INSERT INTO Is_Member_Event (EventID, PID, requestStatus, hasSeenLastMessage) values (1, 2, 'Member', 1);");
	$mysqli->query("INSERT INTO Is_Member_Event (EventID, PID, requestStatus, hasSeenLastMessage) values (1, 3, 'Member', 1);");

	//create a group which includes these 3 members
	$mysqli->query("INSERT INTO Group_ (GroupID, MainEventID) values (1,1);");

	//add the 3 members to the group
	//hasSeenLastMessage indicates whether the information should be marked as an unread message
	$mysqli->query("INSERT INTO Is_Member_Group (GroupID, PID, requestStatus, hasSeenLastMessage) values (1, 1, 'Member', 1);");
	$mysqli->query("INSERT INTO Is_Member_Group (GroupID, PID, requestStatus, hasSeenLastMessage) values (1, 2, 'Member', 1);");
	$mysqli->query("INSERT INTO Is_Member_Group (GroupID, PID, requestStatus, hasSeenLastMessage) values (1, 3, 'Member', 1);");

	//add some content from the 3 members of the group
	//PermissionType -> Default = 0, Can add comments to post = 1, Can add links and images = 2.
	//EventID is mandatory foreign key. This is because all content must belong in an event. 
	//The content can belong in a specific Group or not (Content on event page), but it is always
	//under an event.
	$mysqli->query("INSERT INTO Content (CID, PermissionType, TimeStamp, replyImage, replyString, EventID, GroupID) values (1, 0, 1, null, 'Hi guys! How yall doin?', 1, 1);");
	$mysqli->query("INSERT INTO Content (CID, PermissionType, TimeStamp, replyImage, replyString, EventID, GroupID) values (2, 0, 2, null, 'I am doing fine what aboot yall?', 1, 1);");
	$mysqli->query("INSERT INTO Content (CID, PermissionType, TimeStamp, replyImage, replyString, EventID, GroupID) values (3, 0, 3, null, 'Shitty day, shitty night', 1, 1);");
	$mysqli->query("INSERT INTO Content (CID, PermissionType, TimeStamp, replyImage, replyString, EventID, GroupID) values (4, 0, 7, null, 'Howdy guys from Houston Texas', 1, 1);");

	//adding specific event posts
	$mysqli->query("INSERT INTO Content (CID, PermissionType, TimeStamp, replyImage, replyString, EventID, GroupID) values (5, 1, 9, null, 'The date of the event has changed, make sure you are aware.', 1, null);");
	$mysqli->query("INSERT INTO Content (CID, PermissionType, TimeStamp, replyImage, replyString, EventID, GroupID) values (6, 0, 5, null, 'This is my new group, hope you enjoy :)', 1, null);");

	//adding event sub-posts from permission set to 1. (Look at Content with ID 5)
	$mysqli->query("INSERT INTO Comment (CoID, CID, TimeStamp, replyImage, replyString) values (1, 5, 11, null, 'Duly noted!');");
	$mysqli->query("INSERT INTO Comment (CoID, CID, TimeStamp, replyImage, replyString) values (2, 5, 16, null, 'Thanx bruh!');");

	//attribute the content to the members of the group
	//bbb said Hi guys How yall doin
	$mysqli->query("INSERT INTO Post (CID, PID) values (1, 2);"); 
	//aaa said I am doing fine what aboot yall
	$mysqli->query("INSERT INTO Post (CID, PID) values (2, 1);");
	//bbb said Shitty day, shitty night
	$mysqli->query("INSERT INTO Post (CID, PID) values (3, 2);");
	//ccc said Howdy guys from Houston Texas
	$mysqli->query("INSERT INTO Post (CID, PID) values (4, 3);");

	//for event related posts...
	//ccc said This is my new group, hope you enjoy
	$mysqli->query("INSERT INTO Post (CID, PID) values (5, 3);");
	//ccc said The date of the event has changed
	$mysqli->query("INSERT INTO Post (CID, PID) values (6, 3);");

	//for the comments given for the first event post
	$mysqli->query("INSERT INTO Post_Comment (CoID, PID) values (1, 1);");
	$mysqli->query("INSERT INTO Post_Comment (CoID, PID) values (2, 2);");

	//close the connection.
	$mysqli->close();
?>
