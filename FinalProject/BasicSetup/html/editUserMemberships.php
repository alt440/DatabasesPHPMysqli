<?php
  session_start();
  require "../database_layer_get.php";

  //redirect to login screen if not logged in
  if(!isset($_SESSION['username']) || $_SESSION['username']==''){
    header('Location:index.html');
  }
  // Connection to the database
  /*$mysqli = new mysqli("urc353.encs.concordia.ca", "urc353_2", "AqtjPG");
  $mysqli->select_db("urc353_2");*/
  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $username = $_SESSION['username'];
  $groups = getGroupsOfUser($mysqli, $username);
  $events = getEventsOfUser($mysqli, $username);

  if(isset($_SESSION['searchUser'])){
    $_SESSION['searchUser']='';
  }
?>
<!--
  authors: Alexandre Therrien, Daniel Vigny-Pau
-->
<html>
<head>
  <meta charset="utf-8">
  <title>Edit group/event details - Share, Contribute & Comment System</title>
  <link rel="stylesheet" type="text/css" href="css/editInfo.css"/>
  <script src="../js/jquery-3.4.1.min.js"></script>
</head>
<body>
  <div class="bigBox">
  <h2>Edit group/event details</h2>
  <br/>
  <table class="centeredRow" id="userMemberships">
    <tr>
      <td colspan="2"><p class="subtitle">EVENTS</p></td>
    </tr>
    <?php
    if(is_bool($events) || mysqli_num_rows($events) == 0){
      ?>
      <tr>
        <td>The user belongs to no events.</td>
      </tr>
      <?php
    } else{
      while($row = mysqli_fetch_row($events)){
        if($row[2]=='admin'){
          ?>
          <tr>
            <td>Event <b><?php echo $row[1];?></b>: </td>
            <td><input type="button" class="newSmallDeleteButton" value="DELETE EVENT" id="dropEvent<?php echo $row[0];?>" onclick="dropEvent('<?php echo $username?>', this)"></td>
          </tr>
          <?php
        } else{
        ?>
        <tr>
          <td>Event <b><?php echo $row[1];?></b>: </td>
          <td><input type="button" class="newSmallDeleteButton" value="LEAVE EVENT" id="dropEvent<?php echo $row[0];?>" onclick="dropEventMembership('<?php echo $username?>', this)"></td>
        </tr>
        <?php
        }
      }
    }?>
    <tr>
      <td><br/></td>
    </tr>
    <tr>
      <td colspan="2"><p class="subtitle">GROUPS</p></td>
    </tr>
    <?php
      if(is_bool($groups) || mysqli_num_rows($groups) == 0){
        ?>
        <tr>
          <td>The user belongs to no groups</td>
        </tr>
        <?php
      } else{
        while($row = mysqli_fetch_row($groups)){
          if($row[3]=='admin'){
            ?>
            <tr>
              <td>Group <b><?php echo $row[1]?></b>: </td>
              <td><input type="button" class="newSmallDeleteButton" value="DELETE GROUP" id="dropGroup<?php echo $row[0];?>" onclick="dropGroup('<?php echo $username?>', this)"></td>
            </tr>
            <?php
          } else{
          ?>
          <tr>
            <td>Group <b><?php echo $row[1]?></b>: </td>
            <td><input type="button" class="newSmallDeleteButton" value="LEAVE GROUP" id="dropGroup<?php echo $row[0];?>" onclick="dropGroupMembership('<?php echo $username?>', this)"></td>
          </tr>
          <?php
          }
        }
      }?>
    </table>

    <br/><br/>
    <p class="subtitle">ADD AN EVENT</p>
    <p class="subtitle">CURRENT RATES</p>
    <table id="showRatesTable">
      <tr>
        <th>Rates ID</th>
        <th>Number Events</th>
        <th>Event Type</th>
        <th>Storage GB</th>
        <th>Bandwidth GB</th>
        <th>Price</th>
        <th>Bandwidth Overflow Fee</th>
        <th>Storage Overflow Fee</th>
      </tr>
      <?php
      $getRates = getRates($mysqli);
      if(!(is_bool($getRates)||mysqli_num_rows($getRates)==0)){
        while($row = mysqli_fetch_row($getRates)){
          ?>
          <tr>
            <?php
            for($i=0;$i<sizeof($row);$i++){
              ?>
              <td><?php echo $row[$i];?></td>
              <?php
            }
            ?>
          </tr>
          <?php
        }
      }
      ?>
    </table>
    <br/><br/>
    <table class="centeredRow" id="addEventTable">
      <tr>
        <td><p class="subtitle">EVENT INFORMATION</p></td>
      </tr>
    <tr>


        <form action="payment.php" method="POST">
          <td><p class="sub">Event Name</p></td>
    </tr>
    <tr>
        <td>
                <input type="text" name="eventTitle" class="newText" id="addEventTxt" placeholder="Event Name" required>
            <p class="sub">Event Type</p>
            <select id="eventTypesSelection" name="eventType" >
              <?php
                $eventTypes = getEventTypes($mysqli);

                while($row = mysqli_fetch_row($eventTypes)){
                  ?>
                  <option value="<?php echo $row[0]?>"><?php echo $row[0]?></option>
                  <?php
                }
                ?>
            </select>
            <p class="sub">Event Template</p>
                <input type="text" class="newText" name="eventTemplate" id="addEventTemplate" placeholder="Template Selection (1 or 2)" required>

            <p class="sub">Date fo the Event:</p>
            <input class="newText" name="dateOfEvent"  required type="date" >

            <p class="sub">Archived Date fo the Event:</p>
            <input class="newText" name="archivedDateOfEvent" type="date" required>

            <p class="sub">Rates Selection:</p>
            <input class="newText" name="rateID" type="text" placeholder="Rate ID Selection" required>

          <br><input type="submit" class="centeredButton" value="ADD AN EVENT" id="addEvent" ></td>


          </form>

      </tr>
    </table>

    <input type="button" value="RETURN TO HOMEPAGE" class="returnButton" id="returnToHomePage" onclick="returnToHomePage()">

    <script>
    function returnToHomePage(){
      window.location.href="homePage.php";
    }

    function dropGroupMembership(username, element){
      var groupID = (element.id).match(/\d+/)[0];

      $.ajax({
        type: "GET",
        url: "requests/dropGroupMembership.php?groupID="+groupID+"&username="+username,
        success: function (response){
          location.reload();
        }
      });
    }

    function dropEventMembership(username, element){
      //cannot drop event membership if admin of a group; the group will be deleted.
      var eventID = (element.id).match(/\d+/)[0];

      $.ajax({
        type: "GET",
        url: "requests/dropEventMembership.php?eventID="+eventID+"&username="+username,
        success: function (response){
          location.reload();
        }
      });
    }

    function dropEvent(username, element){
      var eventID= (element.id).match(/\d+/)[0];

      $.ajax({
        type: "GET",
        url: "requests/deleteEvent.php?eventID="+eventID+"&username="+username,
        success: function (response){
          location.reload();
        }
      });

    }

    function dropGroup(username, element){
      var groupID = (element.id).match(/\d+/)[0];

      $.ajax({
        type: "GET",
        url: "requests/deleteGroup.php?groupID="+groupID+"&username="+username,
        success: function (response){
          location.reload();
        }
      });
    }

    function addEvent(username){
      var templateSelected = document.getElementById('addEventTemplate').value;
      var eventTitle = document.getElementById('addEventTxt').value;
      var e = document.getElementById("eventTypesSelection");
      var strUser = e.options[e.selectedIndex].value;

      $.ajax({
        type: "GET",
        url: "requests/addEvent.php?username="+username+"&templateNb="+templateSelected+"&eventTitle="+eventTitle+"&eventType="+strUser,
        success: function (response){
          response= $.parseJSON(response);
          if(response['response']!=1){
            window.alert(response['response']);
          }
          else{
            window.alert("Success! Event was added. Now, administrator needs to approve the event.");
            location.reload();
          }

        }
      });
    }
    </script>
    </div>
</body>
</html>
