<?php
	/* This file demonstrates how to extract all the Event's posts. Our query relies on the GroupID 
	   to hold the value NULL with the EventID that satisfies us. */

	//connect with the database
	$mysqli = new mysqli("localhost", "root", "");

	//select database
	$mysqli->select_db("comp353_final_project");

	//do some queries and grab the results to test if the results are as expected.
	//I am going to show the conversation between aaa, bbb, ccc in the right order.
	$results = $mysqli->query("SELECT Content.replyString, Post.PID FROM Content INNER JOIN Post ON Content.CID=Post.CID WHERE GroupID IS NULL AND EventID=1 ORDER BY Content.TimeStamp ASC");
	$results2 = $mysqli->query("SELECT Participant.Name, Post.PID FROM Participant INNER JOIN Post ON Participant.PID=Post.PID");

	$results3 = $mysqli->query("SELECT joined1.Name, replyString FROM Content INNER JOIN (SELECT Post.PID, Content.CID FROM Content INNER JOIN Post ON Content.CID=Post.CID WHERE GroupID IS NULL AND EventID=1) as joined on joined.CID=Content.CID INNER JOIN (SELECT Participant.Name, Post.PID FROM Participant INNER JOIN Post ON Participant.PID=Post.PID GROUP BY Post.PID) as joined1 on joined1.PID=joined.PID ORDER BY Content.TimeStamp ASC;"); 

	echo $mysqli->error;

	while($row = mysqli_fetch_row($results3)){
		echo $row[0].' '.$row[1]."<br>";
	}

	//close the connection.
	$mysqli->close();
?>
