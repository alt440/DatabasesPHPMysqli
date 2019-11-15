<?php

        $mysqli = new mysqli("urc353.encs.concordia.ca", "urc353_2", "AqtjPG");

	$mysqli->select_db("urc353_2");

        // The Participants creates an event which is set to pending
        // Create the person and the participant.
        $mysqli->query("INSERT INTO PERSON VALUES(10, 'Francesco', 'FrancescoDavido', 'passwordfranceso', 'francoisdavid4000@gmail.com' ,'1996-07-01')");
        $mysqli->query("INSERT INTO PARTICIPANTS VALUES(10, 12345678910)");
        
        // Create an Event and set the status as pending, the foreign key to the ID of the participant. 
        $mysqli->query("INSERT INTO EVENT VALUES(3, 'Christmas', 'Pending', '2019-12-25', '2020-01-01', 10)");
        
        
        // Then, the system admin can change the status of the event. And create a new instance of EventManager with the reference of the user.
        $mysqli->query("UPDATE EVENT SET Status='Confirmed' WHERE EventId=3");
        $mysqli->query("UPDATE EVENT SET Status='Archived', Date='2018-12-12', ExpirationDate='2019-10-18' WHERE EventId=1");
        $mysqli->query("INSERT INTO EVENTMANAGER VALUES(10, 3)");
            
        //  Select the event to display it . 
        $result = $mysqli->query("SELECT * FROM EVENT");
        
        // Create a table.
        echo '<table border="1" width="50%">';
        // Create the headers.  
        echo '<tr>   <th>ID</th>    <th>Title</th>   <th>Status</th>   <th>Date</th>   <th>Expiring Date</th>  <th>Manager ID</th>   </tr>';
       
        
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