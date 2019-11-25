<?php
        $mysqli = new mysqli("urc353.encs.concordia.ca", "urc353_2", "AqtjPG");
	$mysqli->select_db("urc353_2");
        
        // Creating person & making them a participant
        $mysqli->query("INSERT INTO PERSON VALUES(11, 'Daniel', 'DanielVP', 'abc123', 'danielvp@email.com' ,'1996-12-18')");
        $mysqli->query("INSERT INTO PARTICIPANTS VALUES(11, 1111111111)");
        
        // creating a person & making them into a manager
        $mysqli->query("INSERT INTO PERSON VALUES (12, 'Managerino', 'ManagerLogin', 'password', 'manager@manager.com', '1942-12-12')");
        $mysqli->query("INSERT INTO EVENTMANAGER VALUES(12)");
        
        // Create an Event and set the status as pending, the foreign key to the ID of the participant. 
        $mysqli->query("INSERT INTO EVENT VALUES(7, 'Wedding Szn', 'Pending', '2020-06-29', '2020-06-30', 12)");
        
        // Event Manager manages the event
        $mysqli->query("INSERT INTO MANAGED_BY VALUES(7, 12)");
        
        // Event Manager marks the new Event as Confirmed
        $mysqli->query("UPDATE EVENT SET Status='Confirmed' WHERE EventId=7");
        
        // Adding Event to Event_ table   
        $mysqli->query("INSERT INTO Event_ values (7, 'Confirmed', '2020-06-29', 'Wedding Szn', '2020-06-30')");

        // Post that content in an Event on Content Table
        $mysqli->query("INSERT INTO Content VALUES(4, 1, 2019-11-25, null, 'cant wait bros!!!', 7, null)");

        // Event Manager makes that Content's permission 'VIEW AND COMMENT' 
        $mysqli->query("UPDATE CONTENT SET PermissionType='2' WHERE CID=3");

        
        // replying to your own content with a Comment
        $mysqli->query("INSERT INTO Comment VALUES(3, 4, 17, null, 'same here!')");

        // adding to Post_Comment
        $mysqli->query("INSERT INTO Post_Comment VALUES(3,11)");

        //  Select the Content to display
        $result = $mysqli->query("SELECT * FROM Post_Comment");
        
        $header = "Post Comment table";

        echo '<h3>'.$header.'</h1>';

        // Create a table.
        echo '<table border="1" width="50%">';
        // Create the headers.  
        echo '<tr>   <th>CoID</th>    <th>PID</th>    </tr>';       
        
        
        if ($result->num_rows > 0) {
                    while ($rowEvent = mysqli_fetch_row($result)) {
                            //printing the table row with the data of each entry in the table 'people'
                            echo '<tr>   <td>'.$rowEvent[0].'</td>    <td>'.$rowEvent[1].'</td> </tr>';
        
                    }
        } else {
               
                 echo '<tr><td>'.$empty.'</td></tr>';
            
        }
	echo '</table>';
?>