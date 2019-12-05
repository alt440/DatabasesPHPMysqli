<!--
  authors:  Alexandre Therrien 40057134,
            Daniel Vigny-Pau 40034769
-->

<?php
  session_start();
  require "../database_layer_get.php";

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

<html>
<head>
  <meta charset="utf-8">
  <title>Edit group/event details - Share, Contribute & Comment System</title>
  <link rel="stylesheet" type="text/css" href="css/editInfo.css"/>
  <script src="../js/jquery-3.4.1.min.js"></script>
</head>
<body>
  <div class="smallBox">
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
            <td><input type="button" class="newSmallButton" value="DELETE EVENT" id="dropEvent<?php echo $row[0];?>" onclick="dropEvent('<?php echo $username?>', this)"></td>
          </tr>
          <?php
        } else{
        ?>
        <tr>
          <td>Event <b><?php echo $row[1];?></b>: </td>
          <td><input type="button" class="newSmallButton" value="LEAVE EVENT" id="dropEvent<?php echo $row[0];?>" onclick="dropEventMembership('<?php echo $username?>', this)"></td>
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
              <td><input type="button" class="newSmallButton" value="DELETE GROUP" id="dropGroup<?php echo $row[0];?>" onclick="dropGroup('<?php echo $username?>', this)"></td>
            </tr>
            <?php
          } else{
          ?>
          <tr>
            <td>Group <b><?php echo $row[1]?></b>: </td>
            <td><input type="button" class="newSmallButton" value="LEAVE GROUP" id="dropGroup<?php echo $row[0];?>" onclick="dropGroupMembership('<?php echo $username?>', this)"></td>
          </tr>
          <?php
          }
        }
      }?>
    </table>

    <br/><br/>
    <p class="subtitle">ADD AN EVENT</p>
    <table class="centeredRow" id="addEventTable">
    <tr>
      <td><p class="sub">Event Name</p></td>
    </tr>  
    <tr>
        <td><input type="text" class="newText" id="addEventTxt" placeholder="Event Name...">
   
        <form>
            <p class="sub">Event Type</p>
            <select id="eventTypesSelection">
              <?php
                $eventTypes = getEventTypes($mysqli);

                while($row = mysqli_fetch_row($eventTypes)){
                  ?>
                  <option value="<?php echo $row[0]?>"><?php echo $row[0]?></option>
                  <?php
                }
                ?>
            </select>
          </form>
          <p class="sub">Event Template</p>
          <input type="text" class="newText" id="addEventTemplate" placeholder="Template Selection (1 or 2)">
          <br><input type="button" class="centeredButton" value="ADD AN EVENT" id="addEvent" onclick="addEvent('<?php echo $username;?>')"></td>
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
          location.reload();
        }
      });
    }
    </script>
    </div>
</body>
</html>
