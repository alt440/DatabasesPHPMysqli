<?php
  session_start();
  require "../database_layer_get.php";
  require "../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $users = getAllUsers($mysqli);
  $events = getAllEvents($mysqli);
  $groups = getAllGroups($mysqli);
  $username = $_SESSION['username'];
?>
<html>
<head>
  <meta charset="utf-8">
  <title>Manage System</title>
  <script src="../js/jquery-3.4.1.min.js"></script>
</head>
<body>
  <div id="showUsers">
    <h3>Users of the system</h3>
    <table>
      <tr>
        <th>UID</th>
        <th>Username</th>
      </tr>
      <?php
      while($row= mysqli_fetch_row($users)){
        ?>
        <tr>
          <td><?php echo $row[0];?></td>
          <td><?php echo $row[1];?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>
  <div id="showGroups">
    <h3>Groups of the system</h3>
    <table>
      <tr>
        <th>GroupID</th>
        <th>Group Name</th>
      </tr>
      <?php
      while($row = mysqli_fetch_row($groups)){
        ?>
        <tr>
          <td><?php echo $row[0];?></td>
          <td><?php echo $row[1];?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>
  <div id="showEvents">
    <h3>Events of the system</h3>
    <table>
      <tr>
        <th>EventID</th>
        <th>Event Title</th>
        <th>Is Pending</th>
      </tr>
      <?php
      while($row = mysqli_fetch_row($events)){
        ?>
        <tr>
          <td><?php echo $row[0];?></td>
          <td><?php echo $row[1];?></td>
          <td><?php echo $row[2];?></td>
        </tr>
        <?php
      }
      ?>
    </table>
  </div>
  <div id="removeUserFromGroup">
    <label>Remove user from group</label><br>
    <label>Username:</label><input type="text" placeholder="Username" id="UIDRemoveGroup"><br>
    <label>GroupID:</label><input type="text" placeholder="Group ID" id="GroupIDRemoveGroup"><br>
    <input type="button" id="UIDRemoveGroupButton" value="Submit" onclick="dropGroupMembership()">
  </div>
  <!--Remove user from group-->
  <!--Remove user from event-->
  <div id="removeUserFromEvent">
    <label>Remove user from event</label><br>
    <label>Username:</label><input type="text" placeholder="Username" id="UIDRemoveEvent"><br>
    <label>EventID:</label><input type="text" placeholder="Event ID" id="EventIDRemoveEvent"><br>
    <input type="button" id="UIDRemoveEventButton" value="Submit" onclick="dropEventMembership()">
  </div>
  <!--Approve event requests-->
  <div id="approveEvent">
    <label>Approve event</label><br>
    <label>Event ID:</label><input type="text" placeholder="Event ID..." id="EventRemoveEvent"><br>
    <input type="button" id="EventRemoveEventButton" value="Submit" onclick="setEventActive()">
  </div>
  <!--Publish content on event-->
  <div id="sendContentEvent">
    <label>Send Content on Event</label><br>
    <label>Event Title:</label><input type="text" placeholder="Event Title..." id="EventTitleSendContent"><br>
    <label>Content:</label><input type="text" placeholder="Content to send..." id="ContentSendContent"><br>
    <input type="button" id="SendContentEventButton" value="Submit" onclick="addContentToEvent('<?php echo $username;?>')">
  </div>
  <!--Publish content on group-->
  <div id="sendContentGroup">
    <label>Send Content on Group</label><br>
    <label>Group ID:</label><input type="text" placeholder="Group ID..." id="GroupIDSendContent"><br>
    <label>Content:</label><input type="text" placeholder="Content..." id="ContentSendContentGroup"><br>
    <input type="button" id="SendContentGroupButton" value="Submit" onclick="addContentToGroup('<?php echo $username;?>')">
  </div>
  <!--Set other user as event manager-->
  <div id="setOtherUserAsManager">
    <label>Set other User as Manager</label><br>
    <label>Event ID:</label><input type="text" placeholder="Event ID..." id="EventIDSetManager"><br>
    <label>User ID:</label><input type="text" placeholder="User ID..." id="UIDSetManager"><br>
    <input type="button" id="SetManagerButton" value="Submit" onclick="setNewEventManager()">
  </div>
  <!--Remove group-->
  <div id="removeGroup">
    <label>Remove a Group</label><br>
    <label>Group ID:</label><input type="text" placeholder="Group ID..." id="GroupIDRemoveTheGroup"><br>
    <input type="button" id="RemoveTheGroupButton" value="Submit" onclick="removeGroup()">
  </div>
  <!--Remove event-->
  <div id="removeEvent">
    <label>Remove an Event</label><br>
    <label>Event ID:</label><input type="text" placeholder="Event ID..." id="EventIDRemoveTheEvent"><br>
    <input type="button" id="RemoveTheEventButton" value="Submit" onclick="removeEvent()">
  </div>

  <input type="button" value="RETURN TO HOME PAGE" onclick="returnToHomePage()">

  <script>

  function returnToHomePage(){
    window.location.href="homePage.php";
  }

  function addContentToEvent(username){
    var replyString = document.getElementById('ContentSendContent').value;
    var eventTitle = document.getElementById('EventTitleSendContent').value;
    $.ajax({
      type: "GET",
      url: "requests/sendContent.php?username="+username+"&privilegeLevel=0&replyString="+replyString+"&eventTitle="+eventTitle,
      success: function (response){
        location.reload();
      }
    });
  }

  function addContentToGroup(username){
    var replyString = document.getElementById('ContentSendContentGroup').value;
    var groupID = document.getElementById('GroupIDSendContent').value;
    $.ajax({
      type: "POST",
      url: "requests/sendGroupContent.php",
      data: {
        'json': JSON.stringify({"username":username, "eventID":"missing", "groupID":groupID, "replyString":replyString})
      },
      success: function(response){
        //response = $.parseJSON(response);
        location.reload();
      }
    });
  }

  function setEventActive(){
    var eventID = document.getElementById('EventRemoveEvent').value;
    $.ajax({
      type: "POST",
      url: "requests/setEventActive.php",
      data: {
        'json': JSON.stringify({"eventID":eventID})
      },
      success: function(response){
        location.reload();
      }
    });
  }

  function removeEvent(){
    var eventID= document.getElementById('EventIDRemoveTheEvent').value;

    $.ajax({
      type: "GET",
      url: "requests/deleteEvent.php?eventID="+eventID,
      success: function (response){
        location.reload();
      }
    });
  }

  function removeGroup(){
    var groupID = document.getElementById('GroupIDRemoveTheGroup').value;

    $.ajax({
      type: "GET",
      url: "requests/deleteGroup.php?groupID="+groupID,
      success: function (response){
        location.reload();
      }
    });
  }

  function setNewEventManager(){
    var eventID = document.getElementById('EventIDSetManager').value;
    var UID = document.getElementById('UIDSetManager').value;
    $.ajax({
      type: "GET",
      url: "requests/setNewEventManager.php?eventID="+eventID+"&UID="+UID,
      success: function (response){
        location.reload();
      }
    });
  }

  function dropEventMembership(){
    //cannot drop event membership if admin of a group; the group will be deleted.
    var eventID = document.getElementById('EventIDRemoveEvent').value;
    var username = document.getElementById('UIDRemoveEvent').value;
    $.ajax({
      type: "GET",
      url: "requests/dropEventMembership.php?eventID="+eventID+"&username="+username,
      success: function (response){
        location.reload();
      }
    });
  }

  function dropGroupMembership(){
    var groupID = document.getElementById('GroupIDRemoveGroup').value;
    var username = document.getElementById('UIDRemoveGroup').value;

    $.ajax({
      type: "GET",
      url: "requests/dropGroupMembership.php?groupID="+groupID+"&username="+username,
      success: function (response){
        location.reload();
      }
    });
  }

  </script>
</body>
</html>
