<?php
  session_start();
  //reset group
  $_SESSION['Group']='';
  $eventTitle = $_SESSION['Event'];
  $username = $_SESSION['username'];

  if(isset($_SESSION['Event']) && $_SESSION['Event']!=''){
    require "../database_layer_get.php";
    require "../database_layer_use_cases.php";

    $mysqli = new mysqli("localhost", "root", "");
    $mysqli->select_db("comp353_final_project");

    //ask DB for the event information
    $result = getGroupsInEvent($mysqli, $eventTitle);
    $eventInfo = getEvent($mysqli, $eventTitle);

    //is Event archived?
    $archived = isEventArchived($mysqli, $eventTitle);
    //is user member of event?
    $isMember = isUserMemberOfEvent($mysqli, $username, $eventTitle);
    //and if so, what is his requestStatus? to know if he/she is the event manager
    $isEventManager = strcmp(getUserMemberOfEvent($mysqli, $username, $eventTitle)[1],'admin')==0? 1:0;
    $pendingUsers = 0;
    if($isEventManager==1){
      $pendingUsers = getEventPendingUsers($mysqli, $eventTitle);
    }
    //get event admin info
    $eventAdmin = getEventAdmin($mysqli, $eventTitle);
    //get all the members of the event
    $eventMembers = getEventMembers($mysqli, $eventTitle);

    //to identify the location of the content published by the user
    $replyPostNb = 1;
    //to identify which group button has been clicked
    $groupButton = 1;
    //to identify which username was selected
    $userNb = 1;
  }
?>

<html>
<head>
  <meta charset="utf-8">
  <title>Event Page</title>
  <link rel="stylesheet" type="text/css" href="../css/eventPage.css">
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
  <?php
  if(isset($_SESSION['Event']) && $_SESSION['Event']!=''){
    //show some data indicating that the event is archived
    if($archived){
      ?>
      <p id="archivedInfo">Archived</p>
      <?php
    }

    //checking if status is 0. If so, event has been approved
    if($eventInfo[1] == 0){
  ?>
  <h1><?php echo $eventTitle; ?></h1>
  <table id="adminContent">
    <tr class="table">
      <td class="table" colspan="3">Event Manager Details</td>
    </tr>
    <tr class="table">
      <th class="table">Name</th>
      <th class="table">Email</th>
      <th class="table">Phone Number</th>
    </tr>
    <tr>
      <td class="table"><?php echo $eventAdmin[1]?></td>
      <td class="table"><?php echo $eventAdmin[0]?></td>
      <td class="table"><?php echo $eventAdmin[3]?></td>
    </tr>
  </table>
  <?php
    //view content/ members
    if($isEventManager && !$archived){
      ?>
      <input type="button" id="seePendingRequests" value="See Pending Requests" onclick="seePendingRequests('<?php echo $eventTitle;?>')">
      <input type="button" id="seeAddUser" value="Request a User to Join" onclick="seeAddUser()">
      <?php
    }
    if($isMember){
      ?>
      <input type="button" id="seeMembers" value="See Members" onclick="seeMembers()">
      <?php
    } else if(!$archived){
      ?>
      <input type="button" id="joinEvent" value="Join Event" onclick="joinEvent('<?php echo $username;?>','<?php echo $eventTitle?>')">
      <input type="text" id="oneTimeCodeEntryEvent" placeholder="Enter one time code here...">
      <input type="button" id="verifyOneTimeCodeEvent" value="Verify One Time Code" onclick="verifyOneTimeCodeEvent('<?php echo $username;?>','<?php echo $eventTitle?>')">
      <?php
    }
  if($isMember){?>
  <h3 id="groupsTitle">Groups</h3>
  <table id="groupListing">
    <tr>
      <th class="table">Group Name</th>
      <th class="table">Group Admin</th>
      <th class="table">Action</th>
    </tr>
  <?php
    if(is_bool($result) && $isMember){ ?>
      <tr class="table">
        <td class="table">There is no group for this event</td>
      </tr>
  <?php  }
  else if($isMember){
    while($row = mysqli_fetch_row($result)){
      //if he is already part of the group, ask to see content
      if(isMemberGroup($mysqli, $row[1], $username)){
        ?>
        <tr class="table">
          <td class="table" id="groupName<?php echo $groupButton;?>"><?php echo $row[0]; ?></td>
          <td class="table" id="groupAdmin<?php echo $groupButton;?>"><?php echo getUsername($mysqli,$row[2]);?></td>
          <td class="table"><input type="button" id="seeContent<?php echo $groupButton?>" name="seeContent<?php echo $groupButton;?>" value="See Group Content" onclick="seeGroupContent()"></td>
        </tr>
        <?php } else{ ?>
       <tr class="table">
         <td class="table" id="groupName<?php echo $groupButton;?>"><?php echo $row[0]; ?></td>
         <td class="table" id="groupAdmin<?php echo $groupButton;?>"><?php echo getUsername($mysqli,$row[2]);?></td>
         <td class="table"><input type="button" id="sendRequest<?php echo $groupButton;?>" name="sendRequest<?php echo $groupButton;?>" value="Send Request" onclick="sendRequest('<?php echo $username;?>', '<?php echo $eventTitle; ?>', this)">
           <br><input type="text" id="verifyOneTimeCodeText<?php echo $row[1]?>" placeholder="Enter one time code..."><input type="button" id="verifyOneTimeCode<?php echo $row[1];?>" value="Verify One Time Code" onclick="verifyOneTimeCode(this,'<?php echo $username;?>')"></td>
       </tr>
       <?php


    }
    $groupButton+=1;
  }

} //else if($isMember)
} //if($isMember)
} else{
  ?>
  <h2>Oops! It looks like this event does not exist yet...</h2>
  <?php
}

//see event content
if($isMember && $eventInfo[1] == 0){
  ?>
  <div id="wholeContent">
  <h3 id="contentTitle">Content</h3>
  <table id="contentTable">
    <tr class="table">
      <th class="table">Username</th>
      <th class="table">Content</th>
      <th class="table">Timestamp</th>
    </tr>
  <?php
  //get content event
  $allContent = getContentEvent($mysqli, $eventTitle);

  //if event manager, allow to post content (not only comments)
  if($isEventManager && !$archived){
    ?>
    <tr class="table">
      <td class="table" colspan="3">Reply: <input type="text" id="contentContent" placeholder="Your Reply..."><input type="button" id="contentSubmitContent" value="Submit" onclick="sendContent('<?php echo $username;?>','<?php echo $eventTitle;?>',this)">
      <select id="permissionTypeSelection">
        <option value="0">No comments allowed</option>
        <option value="1">Comments allowed</option>
        <option value="2">Link to content and comments allowed</option>
      </select>
      </td>
    </tr>
    <?php
  }

  if(is_bool($allContent) || mysqli_num_rows($allContent)==0){
    ?>
    <tr class="table">
      <td class="table" colspan="3">No content to show</td>
    </tr>
    <?php
  } else{

  while($row=mysqli_fetch_row($allContent)){
   ?>
   <tr class="table">
     <td class="table"><?php echo getUsername($mysqli, $row[2]);?></td>
     <td class="table"><?php echo $row[1];?></td>
     <td class="table"><?php echo convertTimeStampToDateStampHourMinute($row[3]);?></td>
   </tr>
   <?php
   $allComments = getCommentsContent($mysqli, $row[0]);
   if(!is_bool($allComments)){
     while($row_c = mysqli_fetch_row($allComments)){
       ?>
       <tr class="table">
         <td class="table">___________<?php echo getUsername($mysqli, $row_c[1])?></td>
         <td class="table"><?php echo $row_c[0]?></td>
         <td class="table"><?php echo convertTimeStampToDateStampHourMinute($row_c[2])?></td>
       </tr>
       <?php
     }
   }
   if($row[4]!=0 && !$archived){
     //add possibility to add content

     ?>
     <tr class="table">
       <td class="table" colspan="3">Reply: <input type="text" id="content<?php echo $row[0];?>" placeholder="Your Reply..."><input type="button" id="contentSubmit<?php echo $row[0];?>" value="Submit" onclick="sendComment('<?php echo $username;?>',this)"></td>
     </tr>
     <?php
   }
  }
}?></div><?php
//end of $isMember if condition
}
  ?>

  <!-- This below is the implementation to see the members popup-->
  <div id="seeMembersPopup" class="membersPopup">
    <label id="titleSeeMembersPopup" class="membersPopup">Members Of Event</label>
    <input type="button" value="Exit" onclick="closeMemberPopup()">
    <div id="table-scroll" class="membersPopup">
      <div id="tableSeeMembersDivPopup" class="membersPopup">
        <div id="headers">
          <div id="headerUsername">Username</div>
          <div id="headerStatus">Status</div>
          <div id="headerEmpty"></div>
        </div>
      <?php
        if(is_bool($eventMembers)){
          ?>
          <div id="rowMembers">Members of this event not found.</div>
          <?php
        } else{
          while($row = mysqli_fetch_row($eventMembers)){
            ?>
            <div class="rowsMembers">
              <div class="innerTableMembersUsername" id="username<?php echo $row[0];?>"><?php echo getUsername($mysqli, $row[0]);?></div>
              <div class="innerTableMembersStatus" id="status<?php echo $row[0];?>"><?php echo $row[1];?></div>
              <div class="innerTableMembersButtons" id="buttons<?php echo $row[0];?>"><input type="button" value="Send Message" id="sendMessage<?php echo $row[0];?>" onclick="sendMessage(this,'<?php echo $username;?>','<?php echo $eventTitle;?>')"><input type="button" value="View Home Page" id="viewHomePage<?php echo $row[0];?>" onclick="showHome(this)"></div>
            </div>
            <?php
          }
        }
      ?>
    </div>
    </div>
  </div>

  <!--This below is to see the pending users-->
  <div id="seePendingUsersPopup">
    <label id="titleSeePendingUsers">Pending Users</label>
    <input type="button" value="Exit" onclick="closePendingUsersPopup()">
    <div id="table-scroll-pending-users">
      <div id="tableSeePendingDivPopup">
        <div id="headersPending">
          <div id="headerUsernamePending">Username</div>
        </div>
        <?php
          if(!is_bool($pendingUsers) && mysqli_num_rows($pendingUsers)!=0){
            while($row=mysqli_fetch_row($pendingUsers)){
              ?>
              <div class="rowPending">
                <div class="innerTablePendingUsername" id="username<?php echo $row[0]?>"><?php echo getUsername($mysqli, $row[0]);?></div>
                <div class="innerTablePendingButton" id="divButtonPending<?php echo $row[0]?>"><input type="button" id="buttonPending<?php echo $row[0]?>" value="Accept as Member" onclick="makeBecomeMember(this,'<?php echo $eventTitle;?>')"></div>
              </div>
              <?php
            }
          }
        ?>
      </div>
    </div>
  </div>

  <div id="requestAddNewMember">
    <label id="titleRequestAddNewMember">Add New User</label>
    <input type="button" value="Exit" onclick="closeAddUserPopup()"><br>
    <label id="addNewMember1">SCC ID: </label><input type="text" placeholder="Enter User's ID " id="addUserID"><br>
    <label id="addNewMember2">Email: </label><input type="text" placeholder="Enter User's Email Address" id="addUserEmail"><br>
    <label id="addNewMember3">Date of Birth: </label><input type="text" placeholder="Enter User's Date of Birth" id="addUserDOB"><br>
    <label id="addNewMember4">Name: </label><input type="text" placeholder="Enter User's Name" id="addUserName"><br>
    <input type="button" value="Submit" id="submitAddUser" onclick="sendRequestToJoinEvent('<?php echo $eventTitle;?>')">
  </div>


  </div>

<?php } else{?>
  <h2>Oops! It looks like this event does not exist yet...</h2>
<?php }?>

  <script>
    function closeMemberPopup(){
      document.getElementById('seeMembersPopup').style.display = "none";
      document.getElementById('tableSeeMembersPopup').style.display = "none";
    }

    function seeMembers(){
      document.getElementById('seeMembersPopup').style.display = "block";
      document.getElementById('tableSeeMembersPopup').style.display = "block";
    }

    function seePendingRequests(){
      document.getElementById('seePendingUsersPopup').style.display = "block";
    }

    function closePendingUsersPopup(){
      document.getElementById('seePendingUsersPopup').style.display = "none";
    }

    function closeAddUserPopup(){
      document.getElementById('requestAddNewMember').style.display = "none";
    }

    function seeAddUser(){
      document.getElementById('requestAddNewMember').style.display = "block";
    }

    function sendRequestToJoinEvent(eventTitle){
      var UID = document.getElementById('addUserID').value;
      var DOB = document.getElementById('addUserDOB').value;
      var email = document.getElementById('addUserEmail').value;
      var name = document.getElementById('addUserName').value;

      $.ajax({
        type: "POST",
        url: "requests/sendRequestToJoinEvent.php",
        data: {
          'json': JSON.stringify({"UID":UID,"eventTitle":eventTitle,"DOB":DOB,"email":email,"name":name})
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

    function sendRequest(username, eventTitle, element){
      //get element of group
      var elementID = document.getElementById("groupName"+(element.id).match(/\d+/)[0]);
      //extract its value and send
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET","requests/joinGroup.php?groupName="+elementID.innerHTML+"&username="+username+"&eventTitle="+eventTitle, true);
      xmlhttp.send();
      //show a popup that tells you request sent!

      window.alert("Request Sent!");
    }

    //pass the parameters from the onclick method
    function joinEvent(username, eventTitle){

      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET","requests/joinEvent.php?username="+username+"&eventTitle="+eventTitle, true);
      xmlhttp.send();

      //tell the user
      window.alert("Your request to join the Event has been sent!");
      location.reload();
    }

    function verifyOneTimeCode(element, username){
      var groupID = (element.id).match(/\d+/)[0];
      var oneTimeCode = document.getElementById('verifyOneTimeCodeText'+groupID).value;
      $.ajax({
        type: "POST",
        url: "requests/verifyOneTimeCodeGroup.php",
        data: {
          'json': JSON.stringify({"username":username, "groupID":groupID, "oneTimeCode":oneTimeCode})
        },
        success: function(response){
          response = $.parseJSON(response);
          if(response['response']==='Hurray! You got in.'){
            location.reload();
          } else{
            window.alert(response['response']);
          }
        }
      })
    }

    function sendComment(username, element){
      //I have put in the ID of the element the CID that we will use for the comment
      //get CID
      var CID = (element.id).match(/\d+/)[0];
      //get replyString
      var elementString = document.getElementById("content"+CID).value;
      if(elementString === ''){
        return;
      }

      //create AJAX call
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET","requests/addComment.php?username="+username+"&CID="+CID+"&replyString="+elementString, true);
      xmlhttp.send();

      setTimeout(function(){ location.reload(); }, 1000);

    }

    function seeGroupContent(){
      window.location.href="groupConversations.php";
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

    function sendContent(username, eventTitle, element){
      var e = document.getElementById("permissionTypeSelection");
      var privilegeLevel = e.options[e.selectedIndex].value;
      var replyString = document.getElementById('contentContent').value;

      $.ajax({
        type: "GET",
        url: "requests/sendContent.php?username="+username+"&privilegeLevel="+privilegeLevel+"&replyString="+replyString+"&eventTitle="+eventTitle,
        success: function (response){
          location.reload();
        }
      });
    }

    function makeBecomeMember(element, eventTitle){
      var UID = (element.id).match(/\d+/)[0];
      $.ajax({
        type: "POST",
        url: "requests/addMemberToEvent.php",
        data: {
          'json': JSON.stringify({"UID":UID,"eventTitle":eventTitle})
        },
        success: function(response){
          response = $.parseJSON(response);
          location.reload();
          //location.reload();
        }
      });
    }

    function verifyOneTimeCodeEvent(username, eventTitle){
      var oneTimeCode = document.getElementById('oneTimeCodeEntryEvent').value;
      $.ajax({
        type: "POST",
        url: "requests/verifyOneTimeCodeEvent.php",
        data: {
          'json': JSON.stringify({"username":username, "eventTitle":eventTitle, "oneTimeCode":oneTimeCode})
        },
        success: function(response){
          response = $.parseJSON(response);
          if(response['response']==='Hurray! You got in.'){
            location.reload();
          } else{
            window.alert(response['response']);
          }
        }
      })
    }

  </script>
</body>
</html>
