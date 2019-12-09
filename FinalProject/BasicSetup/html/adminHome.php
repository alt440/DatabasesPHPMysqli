<?php
  session_start();
  require "../database_layer_get.php";
  require "../database_layer_use_cases.php";

  //redirect to login screen if not logged in
  if(!isset($_SESSION['username']) || $_SESSION['username']==''){
    header('Location:index.html');
  }

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");
  $users = getAllUsers($mysqli);
  $events = getAllEvents($mysqli);
  $groups = getAllGroups($mysqli);
  $username = $_SESSION['username'];

  //confirm it is the admin that accesses this page
  $current_user = getUser($mysqli, $username);
  if($current_user[6] !=2){
    header('Location:homePage.php');
  }
?>
<!--
  authors:  Alexendre Therrien 40057134,
            Daniel Vigny-Pau 40034769
 -->
<html>
<head>
  <meta charset="utf-8">
  <title>Manage System</title>
  <link rel="stylesheet" type="text/css" href="css/editInfo.css"/>
  <script src="../js/jquery-3.4.1.min.js"></script>
</head>
<body>
  <div class="smallBox">
  <div id="showUsers">
    <p class="subtitle">SYSTEM USERS</p>
    <table class="homeTableSmall" border="1">
      <tr>
        <th>UID</th>
        <th>Username</th>
        <th>Event IDs</th>
        <th>Group IDs</th>
      </tr>
      <?php
      while($row= mysqli_fetch_row($users)){
        ?>
        <tr>
          <td><?php echo $row[0];?></td>
          <td><?php echo $row[1];?></td>
          <td>
          <?php
          $userEvents = getEventsOfUser($mysqli, $row[1]);
          while($row_In = mysqli_fetch_row($userEvents)){
            ?>
            <?php echo $row_In[0];?>,
            <?php
          }
          ?>
          </td>
          <td>
            <?php
            $userGroups = getGroupsOfUser($mysqli, $row[1]);
            while($row_In = mysqli_fetch_row($userGroups)){
              ?>
              <?php echo $row_In[0];?>,
              <?php
            }
            ?>
          </td>
        </tr>
        <?php
      }
      ?>
    </table>
    <br/>
  </div>
  <div id="showGroups">
    <p class="subtitle">SYSTEM GROUPS</p>
    <table class="homeTableSmall" border="1">
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
    <br/>
  </div>
  <div id="showEvents">
    <p class="subtitle">SYSTEM EVENTS</p>
    <table class="homeTableSmall" border="1">
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
    <br/>
  </div>
  <div class="centered">
  <div id="removeUserFromGroup">
    <label><p class="subtitle">REMOVE USER FROM GROUP</p></label>
    <label>Username: </label><input type="text" class="newText" placeholder="Username" id="UIDRemoveGroup"><br>
    <label>GroupID: </label><input type="text" class="newText" placeholder="Group ID" id="GroupIDRemoveGroup"><br>
    <input type="button" id="UIDRemoveGroupButton" value="REMOVE" class="centeredDeleteButton" onclick="dropGroupMembership()">
  </div>
  <!--Remove user from group-->
  <!--Remove user from event-->
  <br/><br/>
  <div id="removeUserFromEvent">
    <label><p class="subtitle">REMOVE USER FROM EVENT</p></label>
    <label>Username: </label><input type="text" class="newText" placeholder="Username" id="UIDRemoveEvent"><br>
    <label>EventID: </label><input type="text" class="newText" placeholder="Event ID" id="EventIDRemoveEvent"><br>
    <input type="button" id="UIDRemoveEventButton" class="centeredDeleteButton" value="REMOVE" onclick="dropEventMembership()">
  </div>
  <!--Approve event requests-->
  <br/><br/>
  <div id="approveEvent">
    <label><p class="subtitle">APPROVE EVENT</p></label>
    <label>Event ID: </label><input type="text" class="newText" placeholder="Event ID" id="EventRemoveEvent"><br>
    <input type="button" id="EventRemoveEventButton" value="APPROVE" class="centeredButton" onclick="setEventActive()">
  </div>
  <!--Publish content on event-->
  <br/><br/>
  <div id="sendContentEvent">
    <label><p class="subtitle">SEND CONTENT ON EVENT</p></label>
    <label>Event Title: </label><input type="text" class="newText" placeholder="Event Title" id="EventTitleSendContent"><br>
    <label>Content: </label><input type="text" class="newText" placeholder="Content to send" id="ContentSendContent"><br>
    <input type="button" id="SendContentEventButton" value="SEND" class="centeredButton" onclick="addContentToEvent('<?php echo $username;?>')">
  </div>
  <!--Publish content on group-->
  <br/><br/>
  <div id="sendContentGroup">
    <label><p class="subtitle">SEND CONTENT ON GROUP</p></label>
    <label>Group ID: </label><input type="text" class="newText" placeholder="Group ID" id="GroupIDSendContent"><br>
    <label>Content: </label><input type="text" class="newText" placeholder="Content" id="ContentSendContentGroup"><br>
    <input type="button" id="SendContentGroupButton" value="SEND" class="centeredButton" onclick="addContentToGroup('<?php echo $username;?>')">
  </div>
  <!--Set other user as event manager-->
  <br/><br/>
  <div id="setOtherUserAsManager">
    <label><p class="subtitle">SET OTHER USER AS MANAGER</p></label>
    <label>Event ID: </label><input type="text" class="newText" placeholder="Event ID" id="EventIDSetManager"><br>
    <label>User ID: </label><input type="text" class="newText" placeholder="User ID" id="UIDSetManager"><br>
    <input type="button" id="SetManagerButton" value="SET" class="centeredButton" onclick="setNewEventManager()">
  </div>
  <!--Remove group-->
  <br/><br/>
  <div id="removeGroup">
    <label><p class="subtitle">REMOVE A GROUP</p></label>
    <label>Group ID: </label><input type="text" class="newText" placeholder="Group ID" id="GroupIDRemoveTheGroup"><br>
    <input type="button" id="RemoveTheGroupButton" class="centeredDeleteButton" value="REMOVE" onclick="removeGroup()">
  </div>
  <!--Remove event-->
  <br/><br/>
  <div id="removeEvent">
    <label><p class="subtitle">REMOVE AN EVENT</p></label>
    <label>Event ID: </label><input type="text" class="newText" placeholder="Event ID" id="EventIDRemoveTheEvent"><br>
    <input type="button" id="RemoveTheEventButton" class="centeredDeleteButton" value="REMOVE" onclick="removeEvent()">
  </div>
  <!--Remove user-->
  <br/><br/>
  <div id="removeUser">
    <label><p class="subtitle">REMOVE A USER</p></label>
    <label>Username: </label><input type="text" class="newText" placeholder="Username" id="UsernameRemoveUser"><br>
    <input type="button" id="RemoveUserButton" class="centeredDeleteButton" value="REMOVE" onclick="removeUser()">
  </div>
  <!--Add user-->
  <br/><br/>
  <div id="addUser">
    <label><p class="subtitle">ADD USER</p></label>
    <label>Username: </label><input type="text" class="newText" placeholder="Username" id="UsernameAddUser"><br>
    <label>Password: </label><input type="password" class="newText" placeholder="Password" id="PasswordAddUser"><br>
    <label>Name: </label><input type="text" class="newText" placeholder="Name" id="NameAddUser"><br>
    <label>Email: </label><input type="text" class="newText" placeholder="Email" id="EmailAddUser"><br>
    <label>Date Of Birth: </label><input type="text" class="newText" placeholder="YYYY-MM-DD" id="DOBAddUser"><br>
    <label>Privilege Level: </label><input type="text" class="newText" placeholder="0 for user,1 for controller" id="PrivilegeLevelAddUser"><br>
    <input type="button" id="AddUserButton" class="centeredButton" value="SET" onclick="addUser()">
    </div>
    <br/>
  <input type="button" class="returnButton" value="RETURN TO HOME PAGE" onclick="returnToHomePage()">

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
        response= $.parseJSON(response);
        if(response['response']!=1){
          window.alert(response['response']);
        } else{
          window.alert("Success!");
          location.reload();
        }
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
        response= $.parseJSON(response);
        if(response['response']!=1){
          window.alert(response['response']);
        } else{
          window.alert("Success!");
          location.reload();
        }
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
        response= $.parseJSON(response);
        if(response['response']!=1){
          window.alert(response['response']);
        } else{
          window.alert("Success!");
          location.reload();
        }
      }
    });
  }
  function removeEvent(){
    var eventID= document.getElementById('EventIDRemoveTheEvent').value;
    $.ajax({
      type: "GET",
      url: "requests/deleteEvent.php?eventID="+eventID,
      success: function (response){
        response= $.parseJSON(response);
        if(response['response']!=1){
          window.alert(response['response']);
        } else{
          window.alert("Success!");
          location.reload();
        }
      }
    });
  }
  function removeGroup(){
    var groupID = document.getElementById('GroupIDRemoveTheGroup').value;
    $.ajax({
      type: "GET",
      url: "requests/deleteGroup.php?groupID="+groupID,
      success: function (response){
        response= $.parseJSON(response);
        if(response['response']!=1){
          window.alert(response['response']);
        } else{
          window.alert("Success!");
          location.reload();
        }
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
        response= $.parseJSON(response);
        if(response['response']!=1){
          window.alert(response['response']);
        } else{
          window.alert("Success!");
          location.reload();
        }
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
        response= $.parseJSON(response);
        if(response['response']!=1){
          window.alert(response['response']);
        } else{
          window.alert("Success!");
          location.reload();
        }
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
        response= $.parseJSON(response);
        if(response['response']!=1){
          window.alert(response['response']);
        } else{
          window.alert("Success!");
          location.reload();
        }
      }
    });
  }

  function removeUser(){
    var username = document.getElementById('UsernameRemoveUser').value;
    $.ajax({
      type:"POST",
      url: "requests/deleteUser.php",
      data: {
        'json': JSON.stringify({"username":username})
      },
      success: function(response){
        response= $.parseJSON(response);
        if(response['response']!=1){
          window.alert(response['response']);
        } else{
          window.alert("Success!");
          location.reload();
        }
      }
    })
  }

  function addUser(){
    var username = document.getElementById('UsernameAddUser').value;
    var password = document.getElementById('PasswordAddUser').value;
    var email = document.getElementById('EmailAddUser').value;
    var name = document.getElementById('NameAddUser').value;
    var dob = document.getElementById('DOBAddUser').value;
    var privilegeLevel = document.getElementById('PrivilegeLevelAddUser').value;

    $.ajax({
      type:"POST",
      url: "requests/addUser.php",
      data: {
        'json': JSON.stringify({"username":username, "password":password, "email":email, "name":name, "dateofbirth":dob, "privilegelevel":privilegeLevel})
      },
      success: function(response){
        response= $.parseJSON(response);
        if(response['response']!=1){
          window.alert(response['response']);
        } else{
          window.alert("Success!");
          location.reload();
        }
      }
    })
  }
  </script>
  </body>
</body>
</html>
