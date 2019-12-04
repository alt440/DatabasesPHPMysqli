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
  $currentEvent = 0;

  $content = 0;
?>

<!--author: Charles-Antoine Guité 40063098
            Alexandre Therrien 40057134
-->
<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" href="../css/groupConversations.css">
  <script src="../js/jquery-3.4.1.min.js"></script>
</head>
<body>
<div class="header">
  <h1>Group Conversations</h1>
</div>

<div class="row">
  <div class="menu" style="background-color:#aaa;">
     <ul>
       <?php
        $i=0;
        while($row = mysqli_fetch_row($groupNames)){
          if($i == 0){
            //to get conversation, get group ID
            $content = getContentGroup($mysqli, $row[1]);
            $currentGroup = $row[0];
            $currentEvent = $row[2];
            $i+=1;
          }
          ?>
          <li><label><?php echo $row[1];?></label>
          <?php
          $latest_post = getLatestPostGroup($mysqli, $row[0]);
          //echo $latest_post[1];
            ?>
            <p><?php echo $latest_post[0];?></p>
        </li>
        <?php
        }
       ?>

    </ul>
  </div>
  <div class="convos" id="convos" style="background-color:#bbb;">
    <div id="table-scroll" class="membersPopup">
    <div style="list-style-type:none;">
      <?php
      if(!is_bool($content) && mysqli_num_rows($content)!=0){
        while($row = mysqli_fetch_row($content)){

          if($userID == $row[1]){
          ?>
            <div class="textPerso whiteBackground"><?php echo $row[0];?></div><br><br>
          <?php
          } else {
          ?>
            <div class="text whiteBackground"><?php echo $row[0];?></div><br><br>
          <?php
          }

        }
      }
      ?>
    </div>
  </div>
  	<div id="footer">
      <div id="optionsBar">
      	  <button id="OptionsButton" type="button">Options</button>
		  <button id="MembersButton" type="button">Members</button>
      </div>
        <textarea id = "TextArea"
                  rows = "3"
                  cols = "80"></textarea>

       <div>
      	  <input id="SendButton" type="button" value="Send" onclick="sendMessage('<?php echo $currentEvent?>','<?php echo $currentGroup;?>','<?php echo $username?>')">
		      <input id="FilesButton" type="button" value="Files">
      </div>
  	</div>
  </div>
</div>

<script>

  function sendMessage(eventID, groupID, username){
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
    })
  }

</script>

</body>
</html>
