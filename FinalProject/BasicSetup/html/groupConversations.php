<?php

  session_start();

  require "../database_layer_get.php";
  require "../database_layer.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $username = $_SESSION['username'];
  $userID = getUser($mysqli, $username)[0];
  $groupNames = getGroupsOfUser($mysqli, $username);
  $currentGroup = 0;
  $currentGroupName = 0;
  $currentEvent = 0;

  $content = 0;

  $groupMembers = 0;
  $pendingGroupMembers = 0;
  $groupAdmin = 0;

  //set session values to return to proper home page
  if(isset($_SESSION['searchUser'])){
    $_SESSION['searchUser']='';
  }
?>
<!--author: Charles-Antoine Guité 40063098,
            Alexandre Therrien 40057134,
            Daniel Vigny-Pau 40034769
-->
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="../css/groupConversations.css">
  <script src="../js/jquery-3.4.1.min.js"></script>
</head>
<body>

<div class="bigBox">
<div class="header">
  <h1>Group conversations</h1>
</div>
<div class="row">
  <div class="menu">
     <ul>
       <?php
        $start = 0;
        if(isset($_SESSION['Group']) && $_SESSION['Group']!=''){
          $start = $_SESSION['Group'];
        }
          $i=0;
        while($row = mysqli_fetch_row($groupNames)){
          if($i == $start){
            //to get conversation, get group ID
            $content = getContentGroup($mysqli, $row[1]);
            $currentGroup = $row[0];
            $currentGroupName = $row[1];
            $currentEvent = $row[2];
            $groupMembers = getGroupMembers($mysqli, $row[1]);
            $pendingGroupMembers = getGroupPendingUsers($mysqli, $row[1]);
            $groupAdmin = getGroupAdmin($mysqli, $row[1])[1];
          }
          ?>
          <li class="groupInfo" id="someGroupInfo<?php echo $i;?>"><label><b><?php echo $row[1];?></b></label>
          <?php
          $latest_post = getLatestPostGroup($mysqli, $row[0]);
          //echo $latest_post[1];
            ?>
            <p><?php echo $latest_post[0];?></p>
        </li>
        <?php
          $i+=1;
        }
       ?>

    </ul>
  </div>
  <div class="convos" id="convos">
    <div id="table-scroll" class="membersPopup">
    <div style="list-style-type:none;">
      <?php
      if(!is_bool($content)){
      if(mysqli_num_rows($content)!=0){
        while($row = mysqli_fetch_row($content)){

          if($userID == $row[1]){
          ?>
            <div class="textPerso whiteBackground"><b>You: </b><?php echo $row[0];?> </div><br><br>
            <div class="space"></div>
          <?php
          } else {
          ?>
            <div class="text whiteBackground"><b><?php echo getUsername($mysqli,$row[1]);?> :</b> <?php echo $row[0];?></div><br><br>
            <div class="space"></div>
          <?php
          }

        }
      }
    }
      ?>
    </div>
  </div>
  	<div id="footer">
      <div id="optionsBar">
        <?php if(strcmp($groupAdmin,$username)==0){?>
      	  <button id="OptionsButton" type="button" class="newShortButton" onclick="seeOptions()">OPTIONS</button>
		      <button id="MembersButton" type="button" class="newShortButton" onclick="seeMembers('<?php echo $currentGroup?>')">MEMBERS</button>
        <?php }?>
      </div>
        <textarea id = "TextArea"
                  rows = "3"
                  cols = "80"></textarea>

       <div>
      	  <input id="SendButton"  type="button" class="newShortButton" value="SEND" onclick="sendMessageConvo('<?php echo $currentEvent?>','<?php echo $currentGroup;?>','<?php echo $username?>')">
		      <input id="FilesButton" type="button" class="newShortButton"  value="FILES">
      </div>
  	</div>
  </div>
</div>

<!-- this is the members popup -->
<div id="seeMembersPopup" class="membersPopup">
<input type="button" class="newShortButton" value="EXIT" onclick="closeMemberPopup()">
<br/><br/>
  <label id="titleSeeMembersPopup"><b>Members Of Group</b></label>
  <div id="table-scroll" class="membersPopup">
    <div id="tableSeeMembersDivPopup" class="membersPopup">
      <!-- <div id="headers">
        <div id="headerUsername">Username</div>
        <div id="headerStatus">Status</div>
        <div id="headerEmpty"></div>
      </div> -->
    <?php
      if(is_bool($groupMembers)){
        ?>
        <div id="rowMembers">Members of this group were not found.</div>
        <?php
      } else{
        while($row = mysqli_fetch_row($groupMembers)){
          ?>
          <div class="rowsMembers">
            <div class="innerTableMembersUsername" id="username<?php echo $row[0];?>"><?php echo getUsername($mysqli, $row[0]);?></div>
            <div class="innerTableMembersStatus" id="status<?php echo $row[0];?>"><?php echo $row[1];?></div>
            <div class="innerTableMembersButtons" id="buttons<?php echo $row[0];?>"><input type="button" class="newShortButton" value="SEND MESSAGE" id="sendMessage<?php echo $row[0];?>" onclick="sendMessage(this,'<?php echo $username;?>','<?php echo $eventTitle;?>')"><input type="button" class="newShortButton" value="VIEW HOMEPAGE" id="viewHomePage<?php echo $row[0];?>" onclick="showHome(this)"></div>
          </div>
          <br/>
          <?php
        }
      }
    ?>
  </div>
  </div>
<br/>
  <!-- div to add members / remove members -->
  <div id="requestAddNewMemberGroup" class="membersPopup">
    <label id="titleRequestAddNewMember"><b>Add New User</b></label><br>
    <label id="addNewMember1">SCC ID: </label><input type="text" placeholder="Enter User's ID " id="addUserID"><br>
    <label id="addNewMember2">Email: </label><input type="text" placeholder="Enter User's Email Address" id="addUserEmail"><br>
    <label id="addNewMember3">Date of Birth: </label><input type="text" placeholder="Enter User's Date of Birth" id="addUserDOB"><br>
    <label id="addNewMember4">Name: </label><input type="text" placeholder="Enter User's Name" id="addUserName"><br>
    <input type="button" class="newShortButton" value="SUBMIT" id="submitAddUser" onclick="sendRequestToJoinGroup('<?php echo $currentEvent;?>', '<?php echo $currentGroup;?>')">
  </div>

  <br/>

  <div id="requestRemoveMemberGroup" class="membersPopup">
    <label id="titleRequestRemoveMember"><b>Remove Member</b></label><br>
    <label id="removeNewMember1">SCC ID: </label><input type="text" placeholder="Enter User's ID " id="removeUserID"><br>
    <label id="removeNewMember2">Email: </label><input type="text" placeholder="Enter User's Email Address" id="removeUserEmail"><br>
    <label id="removeNewMember3">Date of Birth: </label><input type="text" placeholder="Enter User's Date of Birth" id="removeUserDOB"><br>
    <label id="removeNewMember4">Name: </label><input type="text" placeholder="Enter User's Name" id="removeUserName"><br>
    <input type="button" class="newShortButton" value="SUBMIT" id="submitRemoveUser" onclick="removeUserFromGroup('<?php echo $currentGroup;?>')">
  </div>
</div>

<div id="groupOptions">
<input type="button" class="newShortButton" value="EXIT" onclick="closeOptionsPopup()">
      <br/><br/>
  <label id="changeGroupName"><b>Change Group Name</b></label><br>
  <label id="newNameGroup">New name: </label><input type="text" id="newNameGroupText" placeholder="Enter new group name"><input type="button" value="SUBMIT" class="newShortButton" id="confirmNameGroupChange" onclick="changeGroupName('<?php echo $currentGroup;?>')"><br>
  <!--<label id="requestGroupUpgrade">Make Group Public</label><br>
  <input type="button" value="Make Group Public" id="makeGroupPublic" onclick="makeGroupPublic('<?php //echo $currentGroup;?>')">-->
  <label id="showPendingUsers">Pending Users</label><br>
  <div id="table-scroll-pending-users">
    <div id="tableSeePendingDivPopup">
      <div id="headersPending">
        <div id="headerUsernamePending">Username</div>
      </div>
      <?php
        if(!is_bool($pendingGroupMembers) && mysqli_num_rows($pendingGroupMembers)!=0){
          while($row=mysqli_fetch_row($pendingGroupMembers)){
            ?>
            <div class="rowPending">
              <div class="innerTablePendingUsername" id="username<?php echo $row[0]?>"><?php echo getUsername($mysqli, $row[0]);?></div>
              <div class="innerTablePendingButton" id="divButtonPending<?php echo $row[0]?>"><input type="button" id="buttonPending<?php echo $row[0]?>" class="newShortButton" value="ACCEPT AS MEMBER" onclick="makeBecomeMember(this,'<?php echo $currentGroup;?>')"></div>
            </div>
            <?php
          }
        }
      ?>
    </div>
  </div>
</div>
<br/>
<br/>
<br/>
<input type="button" class="newSmallButton" value="BACK TO HOMEPAGE" onclick="returnToHomePage()">
<br/>
<br/>
<script>

  function closeMemberPopup(){
    document.getElementById('seeMembersPopup').style.display = "none";
    document.getElementById('tableSeeMembersPopup').style.display = "none";
  }

  function seeMembers(){
    document.getElementById('seeMembersPopup').style.display = "block";
    document.getElementById('tableSeeMembersPopup').style.display = "block";
  }

  function closeOptionsPopup(){
    document.getElementById('groupOptions').style.display = "none";
  }

  function seeOptions(){
    document.getElementById('groupOptions').style.display = "block";
  }

  function sendMessageConvo(eventID, groupID, username){
    var replyString = document.getElementById('TextArea').value;
    $.ajax({
      type: "POST",
      url: "requests/sendGroupContent.php",
      data: {
        'json': JSON.stringify({"username":username, "eventID":eventID, "groupID":groupID, "replyString":replyString})
      },
      success: function(response){
        //response = $.parseJSON(response);
        location.reload();
      }
    });
  }

  function sendMessage(element, username, eventTitle){
    //get UID
    var UID = (element.id).match(/\d+/)[0];
    $.ajax({
      type: "GET",
      url: "requests/createPrivateGroup.php?UID="+UID+"&username="+username+"&eventTitle="+eventTitle,
      success: function (response){
        window.location.href="groupConversations.php";
      }
    });
  }

  function showHome(element){
    //get UID
    var UID = (element.id).match(/\d+/)[0];
    $.ajax({
      type: "GET",
      url: "requests/changeHomePageDestination.php?UID="+UID,
      success: function (response){
        window.location.href="homePage.php";
      }
    });
  }

  function returnToHomePage(){
    window.location.href="homePage.php";
  }

  function sendRequestToJoinGroup(eventTitle, groupName){
    var UID = document.getElementById('addUserID').value;
    var DOB = document.getElementById('addUserDOB').value;
    var email = document.getElementById('addUserEmail').value;
    var name = document.getElementById('addUserName').value;

    $.ajax({
      type: "POST",
      url: "requests/sendRequestToJoinGroup.php",
      data: {
        'json': JSON.stringify({"UID":UID,"eventTitle":eventTitle, "groupName":groupName,"DOB":DOB,"email":email,"name":name})
      },
      success: function (response){
        response = $.parseJSON(response);
        if(response['response']!=='OK'){
          window.alert(response['response']);
        } else{
          location.reload();
        }
      }
    });
  }

  function changeGroupName(groupID){
    var newGroupName = document.getElementById('newNameGroupText').value;

    $.ajax({
      type: "POST",
      url: "requests/changeGroupName.php",
      data: {
        'json': JSON.stringify({"groupID":groupID, "groupName":newGroupName})
      },
      success: function (response){
        location.reload();
      }
    });
  }

  function removeUserFromGroup(groupID){
    var UID = document.getElementById('removeUserID').value;
    var DOB = document.getElementById('removeUserDOB').value;
    var email = document.getElementById('removeUserEmail').value;
    var name = document.getElementById('removeUserName').value;

    $.ajax({
      type: "POST",
      url: "requests/kickOutUserFromGroup.php",
      data: {
        'json': JSON.stringify({"groupID":groupID, "UID":UID, "DOB":DOB, "email":email, "name":name})
      },
      success: function (response){
        location.reload();
      }
    });
  }

  /*function makeGroupPublic(groupID){
    $.ajax({
      type: "POST",
      url: "requests/makeGroupPublic.php",
      data: {
        'json': JSON.stringify({"groupID":groupID})
      },
      success: function (response){
        location.reload();
      }
    });
  }*/

  function makeBecomeMember(element, groupID){
    var UID = (element.id).match(/\d+/)[0];
    $.ajax({
      type: "POST",
      url: "requests/addMemberToGroup.php",
      data: {
        'json': JSON.stringify({"UID":UID,"groupID":groupID})
      },
      success: function(response){
        response = $.parseJSON(response);
        location.reload();
        //location.reload();
      }
    });
  }

  $(document).ready(function(){
    $(".groupInfo").click(function(){
      //on click, do following
      var contentnb = $(this).attr('id').match(/\d+/)[0];
      $.ajax({
        type: "POST",
        url: "requests/changeGroupContent.php",
        data: {
          'json': JSON.stringify({"contentnb":contentnb})
        },
        success: function(response){
          //response = $.parseJSON(response);
          location.reload();
        }
      });
    });
  });

</script>
</div>
</body>
</html>
