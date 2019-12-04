<?php
 
    // Include for the database layer functions to add.
    require "database_layer.php";

   // Connection to the database
    $mysqli = new mysqli("urc353.encs.concordia.ca", "urc353_2", "AqtjPG");
    $mysqli->select_db("urc353_2");
    $filename = 'CSVTables.csv';

// Populate the tables  according to the csv file. 
if ($handle = fopen("{$filename}","r")){
    // Allocator variable which will determine which table we will add an entry.
    $whatTable = "";
    while(  ($data  = fgetcsv($handle, 100, ",")) !== FALSE)
    {
        if ($data[0] == ""){// If the row is an empty row, change the current allocator variable.
            // $whatTable = "";
        }
        // If the delimiter in the csv is addUser, change the current allocator variable.
        else if($data[0] == "addUser"){
            $whatTable = "addUser";
            // Get the data of titles so that they are not entered as an entity on the next iteration.
            $data  = fgetcsv($handle, 100, ",");
        }
        // If the delimiter in the csv is createEvent, change the current allocator variable.
        else if ($data[0] == "createEvent"){
             $whatTable = "createEvent";
             // Get the data of titles so that they are not entered as an entity on the next iteration.
             $data  = fgetcsv($handle, 100, ",");
        }
         // If the delimiter in the csv is createGroup, change the current allocator variable.
        else if ($data[0] == "createGroup"){
             $whatTable = "createGroup";
             // Get the data of titles so that they are not entered as an entity on the next iteration.
             $data  = fgetcsv($handle, 100, ",");
        }
         // If the delimiter in the csv is addUserToGroup, change the current allocator variable.
         else if ($data[0] == "addUserToGroup"){
             $whatTable = "addUserToGroup";
             // Get the data of titles so that they are not entered as an entity on the next iteration.
             $data  = fgetcsv($handle, 100, ",");
        }
         // If the delimiter in the csv is addUserToEvent, change the current allocator variable.
         else if ($data[0] == "addUserToEvent"){
             $whatTable = "addUserToEvent";
             // Get the data of titles so that they are not entered as an entity on the next iteration.
             $data  = fgetcsv($handle, 100, ",");
        }
         // If the delimiter in the csv is addContent, change the current allocator variable.
         else if ($data[0] == "addContent"){
             $whatTable = "addContent";
             // Get the data of titles so that they are not entered as an entity on the next iteration.
             $data  = fgetcsv($handle, 100, ",");
        }
         // If the delimiter in the csv is sendEmail, change the current allocator variable.
         else if ($data[0] == "sendEmail"){
             $whatTable = "sendEmail";
             // Get the data of titles so that they are not entered as an entity on the next iteration.
             $data  = fgetcsv($handle, 100, ",");
        }
        else if ($whatTable == "addUser"){
            addUser($mysqli, $data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
        } 
        else if ($whatTable == "createEvent"){
             createEvent($mysqli, $data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
        }
        else if ($whatTable == "createGroup"){
            createGroup($mysqli,$data[0], $data[1], $data[2]) ;
        }
        else if ($whatTable == "addUserToGroup"){
           addUserToGroup($mysqli, $data[0], $data[1], $data[2]);
        }
         else if ($whatTable == "addUserToEvent"){
            addUserToEvent($mysqli, $data[0], $data[1]);
        }
         else if ($whatTable == "addContent"){
           // echo "<p>". $data[0]." ". $data[1]." ". $data[2]." ". $data[3]." ". $data[4]." ". $data[5]. "</p>";
            addContent($mysqli, $data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
        }
         else if ($whatTable == "sendEmail"){
            sendEmail($mysqli, $data[0], $data[1], $data[2], $data[3]);
        }
          echo "<p>".$whatTable. "</p>";
        
    }
    // Display  that the table were Populated.
    echo "<h1>Tables were Populated.</h1>"; 
    fclose($handle);
    
}else {
    // Display that there has been an error.
    echo "<h1>There was a problem, the file could not be opened!</h1>";
}
?> 
