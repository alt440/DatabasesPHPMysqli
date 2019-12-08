 <?php
    session_start();
    require "../database_layer_get.php";
    require "../database_layer_use_cases.php";

    //reset email page
    $_SESSION['email']='';

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
<!--
  authors:  Francois David 40046319,
            Alexandre Therrien 40057134,
            Daniel Vigny-Pau 40034769
 -->
    <head>
        <title>Home - Share, Contribute & Comment System</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/editInfo.css"/>
         <script src="../js/searchBar.js"></script>
         <script src="../js/jquery-3.4.1.min.js"></script>
    </head>

    <body>
      <div class="bigBox">

      <table class="centeredRow" id="searchStuff">
        <tr>
          <!--First include a text field to search for user/event-->
          <td><input type="text" class="newText" id="searchBar" placeholder="Search for event or user"></td>
          <td><input type="button" class="newButton" id="searchEvent" value="SEARCH EVENT" onclick="searchEvent()"></td>
          <td><input type="button" class="newButton" id="searchUser" value="SEARCH USER" onclick="searchUser()"></td>
        </tr>
      </table>
      <br/>
      <div class="centered">
        <h2>Welcome <?php echo $_SESSION["username"];?></h2><br/>
        <input type="button" class="newButton" onclick="window.location.href = 'editUserInfo.php';" value="EDIT USER INFORMATION"/>
        <br/><br/>
        <input type="button" class="newButton" onclick="window.location.href = 'editUserMemberships.php';" value="EDIT GROUP/EVENT DETAILS"/>
        <br/><br/>
        <input type="button" class="newButton" onclick="window.location.href = 'emails.php';" value="SEND EMAIL"/>
        <br/><br/>
        <?php

         //from DB layer
         $result = getUser($mysqli, $username);
         $pid = $result[0];
         if($result[6] == 1){
         ?>
         <input type="button" class="newButton" onclick="window.location.href = 'controllerHome.php';" value="EDIT RATES"/>
         <br/>
         <?php
       } else if($result[6] == 2){
         ?>
         <input type="button" class="newButton" onclick="window.location.href = 'adminHome.php';" value="MANAGE SYSTEM"/>
         <br/>
         <?php
       }?>
         <br/>
         <p class="subtitle">USER DATA</p>
        <table class="homeTable" border="1">
            <!-- <tr><td colspan="2"><h3>USER DATA</h3> </td></tr> -->
            <tr><td><b>SCC User ID: </b></td><td> <?php echo $result[0];?></td> </tr>
            <tr><td><b>Name: </b></td><td> <?php echo $result[4];?></td> </tr>
            <tr><td><b>Username: </b></td><td> <?php echo $result[1];?></td> </tr>
            <tr><td><b>Email: </b></td><td> <?php echo $result[3];?></td> </tr>
            <tr><td><b>Date Of Birth: </b></td><td> <?php echo $result[5];?></td> </tr>
            <?php if($showEmails == 1){ ?>
            <?php }?>
        </table>
        <br/>
        <?php if($showEmails == 1){?>
        <p class="subtitle">EMAILS RECEIVED</p>
        <table class="homeTable" border='1'>
          <tr><th>Source User</th><th>Email Title</th><th>Content</th></tr>
        <?php
         // Query the database to display the email notifications.
         $emails = showEmailsReceived($mysqli, $_SESSION['username']);
         if(is_bool($emails) || mysqli_num_rows($emails) == 0){
           ?>
           <tr>
             <td colspan="3">You have no received emails</td>
           </tr>
           <?php
         } else{
           while($row = mysqli_fetch_row($emails)){
             ?>
             <tr id="email<?php echo $row[3];?>" class="emails">
               <td id="sourceUser<?php echo $row[3];?>"><?php echo getUsername($mysqli, $row[0]);?></td>
               <td id="emailTitle<?php echo $row[3];?>"><?php echo $row[1];?></td>
               <td id="contentEmail<?php echo $row[3];?>"><?php echo $row[2];?></td>
             </tr>
             <?php
           }
         }
         ?>
       </table>
      <br/>
       <p class="subtitle">EMAILS SENT</p>
       <table class="homeTable" border='1'>
         <tr><th>Target User</th><th>Email Title</th><th>Content</th></tr>
       <?php
        // Query the database to display the email notifications.
        $emails = showEmailsSent($mysqli, $_SESSION['username']);
        if(is_bool($emails) || mysqli_num_rows($emails) == 0){
          ?>
          <tr>
            <td colspan="3">You have no sent emails</td>
          </tr>
          <?php
        } else{
          $i=0;
          while($row = mysqli_fetch_row($emails)){
            ?>
            <tr id="emailsSent<?php echo $row[3];?>" class="emails">
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
        <!-- <table class="homeTable" border='1'>
        </table> -->
        <br/>

          <p class="subtitle">UPCOMING EVENTS</p>
           <table border='1' class="homeTable">
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
        <br/>

           <p class="subtitle">YOUR GROUPS</p>
           <table border='1' class="homeTable">
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
        <br/><br/>
        <form action='index.html' action='POST'>
            <input type='submit' class="deleteButton" name='logOut_User' id='logOutMainButton' value='LOG OUT'/>
        </form>
              </div>
            </div>


    <script>

      $(document).ready(function(){
        $(".emails").click(function(){
          var valID = $(this).attr('id').match(/\d+/)[0];
          $.ajax({
            type: "GET",
            url: "requests/setEmailID.php?email="+valID,
            success: function (response){
              window.location.href="emails.php";
            }
          });
        });
      })

    </script>

    </body>
</html>
