<?php 
    session_start();
    
    // Connection to the database
    $mysqli = new mysqli("urc353.encs.concordia.ca", "urc353_2", "AqtjPG");
    $mysqli->select_db("urc353_2");
?> 
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->

<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <link rel="stylesheet" type="text/css" href="css/css-file5.css"/>
    </head>

    <body>
        <h1>Welcome <?php echo $_SESSION["username"];?>,</h1>
        <form action='login.php' action='POST'>
            <input type='submit' name='logOut_User' id='logOutMainButton' value='Log Out'/>
        </form>
         <?php 
         // Query the database to display the user info. 
         $username = $_SESSION["username"];
         $result = $mysqli->query("SELECT * FROM PERSON WHERE username='$username' ");
         $pid = $userobject[0];
         $userobject = mysqli_fetch_row($result);
         
         ?> 
        <table class="zui-table" border='1'> 
            <tr><td colspan="2"><h2>User Data In the System</h2> </td></tr>
            <tr><td><b>SCC User ID: </b></td><td> <?php echo $userobject[0];?></td> </tr>
            <tr><td><b>Name: </b></td><td> <?php echo $userobject[1];?></td> </tr>
            <tr><td><b>Username: </b></td><td> <?php echo $userobject[2];?></td> </tr>
            <tr><td><b>Email: </b></td><td> <?php echo $userobject[4];?></td> </tr>
            <tr><td><b>Date Of Birth: </b></td><td> <?php echo $userobject[5];?></td> </tr>
            <tr><td colspan="2"><a href="editInfo.html">Edit information </a> </td></tr>
        </table>
        
        <h1>Email Notifications</h1> 
        
        <?php 
         // Query the database to display the email notifications. 
         
         ?> 
        <table border='1'>
            
        </table>
        
        
          <h1>Upcoming Events</h1> 
           <table border='1' class="zui-table">
               <tr><th><b>Event ID</b></th><th><b>Title</b></th><th><b>Status</b></th><th><b>Manager ID</b></th><th><b>Date</b></th><th><b>Expiration Date</b></th></tr>
        <?php 
         // Query the database to display the upcomming events. 
            $result = $mysqli->query("SELECT * FROM EVENT e LEFT JOIN PARTICIPATES_IN parI USING(EventID) WHERE parI.EventID = e.EventId AND parI.pid='$userobject[0]'");
             
            if ($result->num_rows > 0) {
                    while ($rowEvent = mysqli_fetch_row($result)) {
                        ?> 
               <tr><td><?php echo $rowEvent[0]; ?></td>
                  <td> <?php echo $rowEvent[1]; ?> </td>
                  <td> <?php echo $rowEvent[2]; ?> </td>
                  <td> <?php echo $rowEvent[5]; ?> </td>
                  <td> <?php echo $rowEvent[3]; ?> </td>
                  <td> <?php echo $rowEvent[4]; ?> </td>
               </tr>
        <?php
                            }
                } else {?>
               <tr><td colspan='6'>You presently have no upcoming events.</td></tr>
                         <?php
                }
         ?> 
       
            
        </table>
          
          
           <h1>Your Groups</h1> 
           <table border='1' class="zui-table">
               <tr><th><b>Group ID</b></th><th><b>Title</b></th><th><b>Main Event ID </b></th></tr>
        <?php 
         // Query the database to display the upcomming events. 
            $result = $mysqli->query("SELECT * FROM _groups e LEFT JOIN PARTICIPATES_IN parI USING(EventID) WHERE parI.EventID = e.EventId AND parI.pid='$userobject[0]'");
             
            if ($result->num_rows > 0) {
                    while ($rowEvent = mysqli_fetch_row($result)) {
                        ?> 
               <tr><td><?php echo $rowEvent[0]; ?></td>
                  <td> <?php echo $rowEvent[1]; ?> </td>
                  <td> <?php echo $rowEvent[2]; ?> </td>
                  <td> <?php echo $rowEvent[5]; ?> </td>
                  <td> <?php echo $rowEvent[3]; ?> </td>
                  <td> <?php echo $rowEvent[4]; ?> </td>
               </tr>
        <?php
                            }
                } else {?>
               <tr><td colspan='6'>You presently are not a member in any group.</td></tr>
                         <?php
                }
         ?> 
       
            
        </table>
         
    </body>
</html>
