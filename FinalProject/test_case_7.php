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
        $mysqli->query("INSERT INTO EVENT VALUES (6, 'Birthday Bash', 'Pending', '2019-12-18', '2020-12-18', 12)");

        // Event Manager manages the event
        $mysqli->query("INSERT INTO MANAGED_BY VALUES(6, 12)");
        
        // Event Manager marks the new Event as Confirmed
        $mysqli->query("UPDATE EVENT SET Status='Confirmed' WHERE EventId=6");
        
        // Adding Event to Event_ table
        $mysqli->query("INSERT INTO Event_ VALUES(6, 'Confirmed', '2019-12-18', 'Birthday Bash', '2020-12-18')");    
        
        // Participant creates event group
        $mysqli->query("INSERT INTO Group_ VALUES(1, 6, 'VIP Birthday Guests')");

        // Adding participant to their own group
        $mysqli->query("INSERT INTO Is_Member_Group VALUES(1, 11, 'Approved', 0)");

        //  Select the Content to display
        $result = $mysqli->query("SELECT * FROM Group_");
        
        $header1="Groups";

        echo '<h3>'.$header1.'</h3>';

        // Create a table.
        echo '<table border="1" width="50%">';
        // Create the headers.  
        echo '<tr>   <th>Group ID</th>    <th>Main Event ID</th>   <th>Group Name</th></tr>';       
        
        
        if ($result->num_rows > 0) {
                    while ($rowEvent = mysqli_fetch_row($result)) {
                            //printing the table row with the data of each entry in the table 'people'
                            echo '<tr>   <td>'.$rowEvent[0].'</td>    <td>'.$rowEvent[1].'</td>    <td>'.$rowEvent[2].'</td> </tr>';
        
                    }
        } else {
               
                 echo '<tr><td>'.$empty.'</td></tr>';
            
        }
    echo '</table>';
    
    //  Select the Content to display
    $result = $mysqli->query("SELECT * FROM Is_Member_Group");
        
    $header2="Group Members";

    echo '<h3>'.$header2.'</h3>';
    // Create a table.
    echo '<table border="1" width="50%">';
    // Create the headers.  
    echo '<tr>   <th>Group ID</th>    <th>PID</th>   <th>Request Status</th>    <th>Has Seen Last Message </th></tr>';       
    
    
    if ($result->num_rows > 0) {
                while ($rowEvent = mysqli_fetch_row($result)) {
                        //printing the table row with the data of each entry in the table 'people'
                        echo '<tr>   <td>'.$rowEvent[0].'</td>    <td>'.$rowEvent[1].'</td>    <td>'.$rowEvent[2].'</td>  <td>'.$rowEvent[3].'</tr>';
    
                }
    } else {
           
             echo '<tr><td>'.$empty.'</td></tr>';
        
    }
echo '</table>';
?>