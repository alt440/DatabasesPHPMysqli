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

	$results3 = $mysqli->query("SELECT joined1.Name, replyString, Content.CID FROM Content INNER JOIN (SELECT Post.PID, Content.CID FROM Content INNER JOIN Post ON Content.CID=Post.CID WHERE GroupID IS NULL AND EventID=1) as joined on joined.CID=Content.CID INNER JOIN (SELECT Participant.Name, Post.PID FROM Participant INNER JOIN Post ON Participant.PID=Post.PID GROUP BY Post.PID) as joined1 on joined1.PID=joined.PID ORDER BY Content.TimeStamp ASC;"); 

	//getting the comments (already in the order of publication)
	$results4 = $mysqli->query("SELECT joined1.Name, replyString, Comment.CID FROM Comment INNER JOIN (SELECT Post_Comment.PID, Comment.CoID, Comment.CID FROM Comment INNER JOIN Post_Comment ON Comment.CoID=Post_Comment.CoID) as joined on joined.CoID=Comment.CoID INNER JOIN (SELECT Participant.Name, Post_Comment.PID FROM Participant INNER JOIN Post_Comment ON Participant.PID=Post_Comment.PID GROUP BY Post_Comment.PID) as joined1 on joined1.PID=joined.PID ORDER BY Comment.TimeStamp ASC;");

	echo $mysqli->error;
	
	//store the contents of the comments in an array
	$comments = array();
	while($row_In = mysqli_fetch_row($results4)){
		$comments[] = $row_In;
	}

	//displaying the comments where they belong
	while($row = mysqli_fetch_row($results3)){
		echo $row[0].' '.$row[1]."<br>";
		for($index = 0; $index < sizeof($comments);$index++){
			if($row[2] == $comments[$index][2]){
				echo 'comment ->    '.$comments[$index][0].' '.$comments[$index][1]."<br>";
			}
		} 
	}	

	//close the connection.
	$mysqli->close();
?>
