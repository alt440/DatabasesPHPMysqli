<?php
  session_start();
  //how would I pass the event name?
  $eventTitle = $_SESSION['Event'];
  $username = 'ppp';

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
    if($isMember){
      ?>
      <input type="button" id="seeMembers" value="See Members" onclick="seeMembers()">
      <?php
    } else if(!$archived){
      ?>
      <input type="button" id="joinEvent" value="Join Event" onclick="joinEvent('<?php echo $username;?>','<?php echo $eventTitle?>')">
      <?php
    }
  if($isMember){?>
  <h3 id="groupsTitle">Groups</h3>
  <table id="groupListing">
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
          <td class="table"><input type="button" id="seeContent<?php echo $groupButton?>" name="seeContent<?php echo $groupButton;?>" value="See Group Content" onclick="seeGroupContent()"></td>
        <?php } else{ ?>
       <tr class="table">
         <td class="table" id="groupName<?php echo $groupButton;?>"><?php echo $row[0]; ?></td>
         <td class="table"><input type="button" id="sendRequest<?php echo $groupButton;?>" name="sendRequest<?php echo $groupButton;?>" value="Send Request" onclick="sendRequest('<?php echo $username;?>', '<?php echo $eventTitle; ?>', this)"><input type="button" id="verifyOneTimeCode<?php echo $groupButton;?>" name="verifyOneTimeCode<?php echo $groupButton;?>" value="Verify One Time Code" onclick="verifyOneTimeCode(this)"></td>
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
if($isMember){
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
   if($row[4]!=0){
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
              <div class="innerTableMembersUsername" id="username<?php echo $userNb;?>"><?php echo getUsername($mysqli, $row[0]);?></div>
              <div class="innerTableMembersStatus" id="status<?php echo $userNb;?>"><?php echo $row[1];?></div>
              <div class="innerTableMembersButtons" id="buttons<?php echo $userNb;?>"><input type="button" value="Send Message" onclick="sendMessage(this)"><input type="button" value="View Home Page" onclick="showHome(this)"></div>
            </div>
            <?php
          }
        }
      ?>
      <!--</table>-->
    </div>
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

    function sendRequest(username, eventTitle, element){
      //get element of group
      var elementID = document.getElementById("groupName"+(element.id).match(/\d+/)[0]);
      //extract its value and send
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET","requests/sendRequestToJoinGroup.php?groupName="+elementID.innerHTML+"&username="+username+"&eventTitle="+eventTitle, true);
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

    function verifyOneTimeCode(element){

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

    }

    function sendMessage(element){

    }

    function showHome(element){

    }

  </script>
</body>
</html>
