/*author: Charles-Antoine Guité 40063098*/

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}
.header {
  background-color: #0000cc;
  color: #ffffff;
  padding: 15px;
}
/* Create two equal columns that floats next to each other */
.menu {
  float: left;
  width: 20%;
  padding: 10px;
  height: 600px;
}
.convos {
  float: left;
  width: 80%;
  padding: 10px;
  height: 600px;
  overflow-y: scroll
}

}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
.menu ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

.menu li {
  padding: 8px;
  margin-bottom: 7px;
  background-color: #99b5e5;
  color: #ffffff;
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}

.menu li:hover {
  background-color: #0099cc;
}
.convos ul {
  list-style-type: none;
  margin: 0;
  padding: 0;
}

.convos li {
  margin-bottom: 7px;
  background-color: #FEFDFD;
  color: #ffffff;
  box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
}

.convos li:hover {
 
}
.Group {
    display: block
}
img {
  width: 100%;
  float: left;
  width:40px;
  height:40px; 
  color: #010000;
}
.text {
  padding: 10px;
  color: #010000;
 }
.picPerso {
  width: 100%;
  float: right;
  width:40px;
  height:40px; 
}
.textPerso {
  float: right;
  color: #010000;
}
#footer {
    position: fixed;
    bottom: 0;
    width: 75%;
    height: 20%;
}
#footer {
    background: #0000cc;
    line-height: 2;
    text-align: center;
    color: #042E64;
    text-shadow: 0 1px 0 #84BAFF;
    box-shadow: 0 0 15px #00214B
}
#optionsBar {
 background: #0086FF;
 height: 25px;
 width: 100%;
}
#MembersButton {
float: right;
}
#OptionsButton {
float: right;
}
#SendButton{
float: right;
}
#FilesButton{
float: right;
}


#TextArea {
height: 60%;
width: 90%;
}
</style>
</head>
<body>

<div class="header">
  <h1>Group Conversations</h1>
</div>

<div class="row">
//List of groups
  <div class="menu" style="background-color:#aaa;">
<ul>
<?php
        $result = getGroupsOfUser($mysqli, $username);
          if ($result->num_rows > 0) {
               while ($rowEvent = mysqli_fetch_row($result)) {
                        ?>
			<li>
				<a Class="Group" href="UpdateConvos.php">$rowEvent[1]</a> /* should be group name*/
				/* Idk if we need a UpdateConvos script, but basically if you click on a group it should reload 
				the page and update the $groupName */
			</li>
				}
  </div>
  //List of comments
  <div class="convos" id="convos" style="background-color:#bbb;">

    <ul style="list-style-type:none;">
	<?php
        $result = getContentGroup($mysqli, $groupName);
          if ($result->num_rows > 0) {
               while ($rowEvent = mysqli_fetch_row($result)) {
                        ?>
      <li>
          <img class="profilePicture"src="" alt=getUsername($mysqli, $rowEvent[1])> /* Should be the UID */
          <p class="text">Here is a text message blablabla</p>
        </li>
        /*<li>
           <img class="picPerso" src="" alt="Prof. pic."> 
           <p class="textPerso">Here is a text message blablabla</p>
            <br><br>
        </li>*/
		}
    </ul>
	//Footer with message box
  	<div id="footer">
      <div id="optionsBar">
      	  <button id="OptionsButton" type="button">Options</button> /*Open options window*/      
		  <button id="MembersButton" type="button">Members</button> /*Open Member window */      
      </div>
        <textarea id = "TextArea"
                  rows = "3"
                  cols = "80"></textarea>
     
       <div>
      	  <button id="SendButton" type="button">Send</button> /* call addContent()*/ 		  
		  <button id="FilesButton" type="button">Files</button> /* Open file explore and update $replyImage*/       
      </div>
  	</div>
  </div>
</div>

</body>
</html>
