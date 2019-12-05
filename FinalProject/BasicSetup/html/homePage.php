<?php
    session_start();
    require "../database_layer_get.php";
    require "../database_layer_use_cases.php";

    // Connection to the database
    /*$mysqli = new mysqli("urc353.encs.concordia.ca", "urc353_2", "AqtjPG");
    $mysqli->select_db("urc353_2");*/
    //log in to the database
    $mysqli = new mysqli("localhost", "root", "");
  	$mysqli->select_db("comp353_final_project");

    // Query the database to display the user info.
    // This variable is set only when the username is the same as the search user value
    $showEmails = 1;
    $username = $_SESSION["username"];
    if(isset($_SESSION['searchUser']) && $_SESSION['searchUser']!=''){
      if(strcmp($username, $_SESSION['searchUser']) != 0){
        $username = $_SESSION['searchUser'];
        $showEmails = 0;
      }
    }
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
         <link rel="stylesheet" type="text/css" href="../css/css-file5.css"/>
         <link rel="stylesheet" type="text/css" href="../css/searchBar.css"/>
         <script src="../js/searchBar.js"></script>
         <script src="../js/jquery-3.4.1.min.js"></script>
    </head>

    <body>
      <table id="searchStuff">
        <tr>
          <!--First include a text field to search for user/event-->
          <td><input type="text" id="searchBar" placeholder="Enter event or user..."></td>
          <td><input type="button" id="searchEvent" value="Search Event" onclick="searchEvent()"></td>
          <td><input type="button" id="searchUser" value="Search User" onclick="searchUser()"></td>
        </tr>
      </table>
        <h1>Welcome <?php echo $_SESSION["username"];?>,</h1>
        <form action='login.php' action='POST'>
            <input type='submit' name='logOut_User' id='logOutMainButton' value='Log Out'/>
        </form>
         <?php
         //from DB layer
         $result = getUser($mysqli, $username);
         $pid = $result[0];


         ?>
        <table class="zui-table" border='1'>
            <tr><td colspan="2"><h2>User Data In the System</h2> </td></tr>
            <tr><td><b>SCC User ID: </b></td><td> <?php echo $result[0];?></td> </tr>
            <tr><td><b>Name: </b></td><td> <?php echo $result[4];?></td> </tr>
            <tr><td><b>Username: </b></td><td> <?php echo $result[1];?></td> </tr>
            <tr><td><b>Email: </b></td><td> <?php echo $result[3];?></td> </tr>
            <tr><td><b>Date Of Birth: </b></td><td> <?php echo $result[5];?></td> </tr>
            <?php if($showEmails == 1){ ?>
            <tr><td colspan="2"><a href="editUserInfo.php">Edit user information </a> </td></tr>
            <tr><td colspan="2"><a href="editUserMemberships.php">Edit group/event details</a></td></tr>
            <?php if($result[6] == 1){ ?>
            <tr><td colspan="2"><a href="controllerHome.php">Edit rates</a></td></tr>
            <?php }
            }?>
        </table>
        <?php if($showEmails == 1){?>
        <h1>Email Notifications</h1>
        <h3>Emails Received</h3>
        <table class="zui-table" border='1'>
          <tr><th>Source User</th><th>Email Title</th><th>Content</th></tr>
        <?php
         // Query the database to display the email notifications.
         $emails = showEmailsReceived($mysqli, $_SESSION['username']);
         if(is_bool($emails) || mysqli_num_rows($emails) == 0){
           ?>
           <tr>
             <td colspan="3">You currently have no emails</td>
           </tr>
           <?php
         } else{
           $i=0;
           while($row = mysqli_fetch_row($emails)){
             ?>
             <tr>
               <td id="sourceUser<?php echo $i;?>"><?php echo getUsername($mysqli, $row[0]);?></td>
               <td id="emailTitle<?php echo $i;?>"><?php echo $row[1];?></td>
               <td id="contentEmail<?php echo $i;?>"><?php echo $row[2];?></td>
             </tr>
             <?php
             $i+=1;
           }
         }
         ?>
       </table>

       <h3>Emails Sent</h3>
       <table class="zui-table" border='1'>
         <tr><th>Target User</th><th>Email Title</th><th>Content</th></tr>
       <?php
        // Query the database to display the email notifications.
        $emails = showEmailsSent($mysqli, $_SESSION['username']);
        if(is_bool($emails) || mysqli_num_rows($emails) == 0){
          ?>
          <tr>
            <td colspan="3">You currently have no emails</td>
          </tr>
          <?php
        } else{
          $i=0;
          while($row = mysqli_fetch_row($emails)){
            ?>
            <tr>
              <td id="targetUser<?php echo $i;?>"><?php echo getUsername($mysqli, $row[0]);?></td>
              <td id="emailTitle<?php echo $i;?>"><?php echo $row[1];?></td>
              <td id="contentEmail<?php echo $i;?>"><?php echo $row[2];?></td>
            </tr>
            <?php
            $i+=1;
          }
        }
        ?>
      </table>
    <?php }//closes showEmails if condition ?>
        <table border='1'>

        </table>


          <h1>Upcoming Events</h1>
           <table border='1' class="zui-table">
               <tr><th>Event ID</th><th>Title</th><th>Latest Post</th><th>Latest Post Timestamp</th></tr>
        <?php
         // Query the database to display the upcomming events.
            $result = getEventsOfUser($mysqli, $username);
            if(is_bool($result)){
              ?>
              <tr><td colspan='6'>You presently have no upcoming events.</td></tr>
              <?php
            }
            else if ($result->num_rows > 0) {
                    while ($rowEvent = mysqli_fetch_row($result)) {
                      $rowResult = getLatestPostEvent($mysqli, $rowEvent[0]);
                        ?>
               <tr><td><?php echo $rowEvent[0]; ?></td>
                  <td><?php echo $rowEvent[1]; ?> </td>
                  <?php if($rowResult!=0){?>
                  <td><?php echo $rowResult[0] ?></td>
                  <td><?php echo convertTimeStampToDateStampHourMinute($rowResult[1]); ?></td>

                <?php } else{?>
                  <td></td>
                  <td></td>
                <?php } ?>
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
               <tr><th>Group ID</th><th>Title</th><th>Main Event ID</th><th>Latest Post</th><th>Latest Post Timestamp</th></tr>
        <?php
         // Query the database to display the upcomming events.
            $result = getGroupsOfUser($mysqli, $username);

            if ($result->num_rows > 0) {
                    while ($rowEvent = mysqli_fetch_row($result)) {
                      $latestPost = getLatestPostGroup($mysqli, $rowEvent[0]);
                        ?>
               <tr><td><?php echo $rowEvent[0]; ?></td>
                  <td><?php echo $rowEvent[1]; ?> </td>
                  <td><?php echo $rowEvent[2]; ?></td>
                  <?php if($latestPost!=0){
                    ?>
                    <td><?php echo $latestPost[0]; ?></td>
                    <td><?php echo convertTimeStampToDateStampHourMinute($latestPost[1]); ?></td>
                    <?php
                  } else{
                    ?>
                    <td></td>
                    <td></td>
                    <?php
                  }?>
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
