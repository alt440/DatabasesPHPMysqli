<!-- 
    authors:  Alexendre Therrien 40057134,
              Daniel Vigny-Pau 40034769
 -->

<?php

  session_start();

  require "../database_layer_get.php";
  require "../database_layer_use_cases.php";
  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  if(!isset($_SESSION['email'])){
    $_SESSION['email']='';
  }
  $isWrite = 0;
  $emailInfo = 0;
  //get requested email
  $emailID = $_SESSION['email'];
  if($emailID==''){
    //write email
    $isWrite=1;
  } else{
    $emailInfo = getEmailInfo($mysqli, $_SESSION['email']);
  }

  $username = $_SESSION['username'];

?>

<html>
<head>
  <meta charset="utf-8">
  <title>Emails</title>
  <script src="../js/jquery-3.4.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/editInfo.css"/>
</head>
<body>
  <div class="smallbox">
  <?php if($isWrite == 0){?>
  <table class="centeredTable">
    <tr>
      <th>From:</th>
      <td><?php echo getUsername($mysqli, $emailInfo[2]);?></td>
    </tr>
    <tr>
      <th>To:</th>
      <td><?php echo getUsername($mysqli, $emailInfo[3]);?></td>
    </tr>
    <tr>
      <th>Title:</th>
      <td><?php echo $emailInfo[5];?></td>
    </tr>
    <tr>
      <th>Content</th>
      <td><?php echo $emailInfo[4];?></td>
    </tr>
  </table>
<?php } else{
  ?>
  <p class="subtitle">SEND AN EMAIL</p>
  <table class="centeredTable">
    <tr>
      <th>From:</th>
      <td><?php echo $username;?></td>
    </tr>
    <tr>
      <th>To:</th>
      <td><input type="text" class="newText" placeholder="User destination..." id="toUser"></td>
    </tr>
    <tr>
      <th>Title:</th>
      <td><input type="text" class="newText" placeholder="Title of email..." id="titleEmail"></td>
    </tr>
    <tr>
      <th>Content</th>
      <td><input type="text" class="newText" placeholder="Content email..." id="contentEmail"></td>
    </tr>
    <tr>
      <td colspan="2"><input type="button" class="centeredButton" value="SEND" onclick="sendEmail('<?php echo $username;?>')"></td>
    </tr>
  </table>
  <?php
}?>
  <input type="button" value="RETURN TO HOME PAGE" class="returnButton" onclick="returnToHomePage()">

  <script>
    function returnToHomePage(){
      window.location.href = "homePage.php";
    }

    function sendEmail(username){
      var targetUsername = document.getElementById('toUser').value;
      var titleEmail = document.getElementById('titleEmail').value;
      var contentEmail = document.getElementById('contentEmail').value;
      $.ajax({
        type: "GET",
        url: "requests/sendEmail.php?sourceUser="+username+"&targetUser="+targetUsername+"&titleEmail="+titleEmail+"&contentEmail="+contentEmail,
        success: function (response){
          window.alert("Email sent!");
          location.reload();
        }
      });
    }
  </script>
  </div>
</body>
</html>
