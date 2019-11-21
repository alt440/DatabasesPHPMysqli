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
        $mysqli->query("INSERT INTO EVENT VALUES(5, 'NYEParty', 'Pending', '2019-12-31', '2021-01-01', 12)");
        
        // Event Manager manages the event
        $mysqli->query("INSERT INTO MANAGED_BY VALUES(5, 12)");
        
        // Event Manager marks the new Event as Confirmed
        $mysqli->query("UPDATE EVENT SET Status='Confirmed' WHERE EventId=5");
        
        // Adding Event to Event_ table
        $mysqli->query("INSERT INTO Event_ values(5, 'Confirmed', '2019-12-31', 'NYEParty', '2021-01-01')");    
        
        // Participant makes new Content in Event that is initially only 'VIEW ONLY' permission
        // $mysqli->query("INSERT INTO CONTENT VALUES(02, 'View Only', 'Text', '2019-11-17', null, null)");
        
        // Post that content in an Event on Content Table
        $mysqli->query("INSERT INTO Content VALUES(3, 1, 2019-11-17, null, 'whats good homies again thrice', 5, null)");
        // $mysqli->query("INSERT INTO Content VALUES(02, 1, '2019-11-17', null, `what's good homie`, 5, null");
        
        // Event Manager makes that Content's permission 'VIEW AND COMMENT' 
        // $mysqli->query("UPDATE CONTENT SET PermissionType='View and comment' WHERE CID=3");
        $mysqli->query("UPDATE CONTENT SET PermissionType='2' WHERE CID=3");

        //  Select the Content to display
        $result = $mysqli->query("SELECT * FROM Content");
        
        // Create a table.
        echo '<table border="1" width="50%">';
        // Create the headers.  
        echo '<tr>   <th>CID</th>    <th>PermissionType</th>   <th>TimeStamp</th>   <th>replyImage</th>   <th>replyString</th>  <th>EventID</th>   <th>GroupID</th></tr>';       
        
        
        if ($result->num_rows > 0) {
                    while ($rowEvent = mysqli_fetch_row($result)) {
                            //printing the table row with the data of each entry in the table 'people'
                            echo '<tr>   <td>'.$rowEvent[0].'</td>    <td>'.$rowEvent[1].'</td>    <td>'.$rowEvent[2].'</td>    <td>'.$rowEvent[3].'</td>     <td>'.$rowEvent[4].'</td>     <td>'.$rowEvent[5].'</td>     </tr>';
        
                    }
        } else {
               
                 echo '<tr><td>'.$empty.'</td></tr>';
            
        }
	echo '</table>';
?>