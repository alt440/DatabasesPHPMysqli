<!--
  authors: Alexandre Therrien, Daniel Vigny-Pau
-->

<?php
  session_start();

  //redirect to login screen if not logged in
  if(!isset($_SESSION['username']) || $_SESSION['username']==''){
    header('Location:index.html');
  }

  require "../database_layer_get.php";
  require "../database_layer_use_cases.php";
  // Connection to the database
  /*$mysqli = new mysqli("urc353.encs.concordia.ca", "urc353_2", "AqtjPG");
  $mysqli->select_db("urc353_2");*/
  //log in to the database
  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");
  //get user info
  $userInfo = getUser($mysqli, $_SESSION['username']);
  $username = $_SESSION['username'];
  //set session values to return to proper home page
  if(isset($_SESSION['searchUser'])){
    $_SESSION['searchUser']='';
  }
?>

<html>
<head>
  <meta charset="utf-8">
  <title>Edit user information - Share, Contribute & Comment System</title>
  <link rel="stylesheet" type="text/css" href="css/editInfo.css"/>
  <script src="../js/jquery-3.4.1.min.js"></script>
</head>
<body>
  <div class="bigBox">
  <h2>Edit user information</h2>
  <table id="userInformation">
    <tr>
      <td class="currentInfo"><b>Current email: </b><?php echo $userInfo[3];?></td>
    </tr><tr>
      <td class="sub">Change email</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeEmailTxt" placeholder="New email..."><input type="button"  class="newButton" value="CHANGE EMAIL" id="changeEmail" onclick="changeEmail('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo"><b>Password:</b> hidden for your privacy</td>
    </tr><tr>
      <td class="sub">Change password</td>
    </tr><tr>
      <td><input type="password" class="newText" id="changePasswordTxt" placeholder="New password..."><input type="password" class="newText" id="oldPassword" placeholder="Old password..."><input type="button" class="newButton" value="CHANGE PASSWORD" id="changePassword" onclick="changePassword('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo"><b>Current name:</b> <?php echo $userInfo[4];?></td>
    </tr><tr>
      <td class="sub">Change name</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeNameTxt" placeholder="New name..."><input type="button" class="newButton" value="CHANGE NAME" id="changeName" onclick="changeName('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo"><b>Bank name: </b>hidden for your privacy</td>
    </tr><tr>
      <td class="sub">Change nank name</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeBankNameTxt" placeholder="New bank name..."><input type="button" class="newButton" value="CHANGE BANK NAME" id="changeBankName" onclick="changeBankName('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo"><b>Credit card number: </b>hidden for your privacy</td>
    </tr><tr>
      <td class="sub">Change credit card number</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeCreditCardNbTxt" placeholder="New credit card number..."><input type="button" class="newButton" value="CHANGE CARD NUMBER" id="changeCreditCardNb" onclick="changeCreditCardNb('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo"><b>Account number: </b>hidden for your privacy</td>
    </tr><tr>
      <td class="sub">Change account number</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeAccountNbTxt" placeholder="New account number..."><input type="button" class="newButton" value="CHANGE ACCOUNT NUMBER" id="changeAccountNb" onclick="changeAccountNb('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo"><b>Current address: </b><?php echo $userInfo[10];?></td>
    </tr><tr>
      <td class="sub">Change address</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeAddressTxt" placeholder="New address..."><input type="button" class="newButton" value="CHANGE ADDRESS" id="changeAddress" onclick="changeAddress('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo"><b>Current phone number: </b><?php echo $userInfo[11];?></td>
    </tr><tr>
      <td class="sub">Change phone number</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changePhoneNumberTxt" placeholder="New phone number..."><input type="button" class="newButton"  value="CHANGE PHONE" id="changePhoneNumber" onclick="changePhoneNumber('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo"><b>Delete user account</b></td>
    </tr><tr>
      <td><input type="button" class="deleteButton" value="DELETE USER ACCOUNT" id="deleteAllUser" onclick="deleteUser('<?php echo $userInfo[1];?>')"></td>
    </tr>
  </table>
  <input type="button" class="returnButton" value="RETURN TO HOMEPAGE" id="returnToHomePage" onclick="returnToHomePage()">

  <script>
    function deleteUser(username){
      if(window.confirm("Are you sure you want to delete your account with username "+username+"? You will be logged out after this change.")){
        $.ajax({
          type: "POST",
          url: "requests/deleteUser.php",
          data: {
            'json': JSON.stringify({"username":username})
          },
          success: function(response){
            window.location.href = "index.html";
          }
        })
      }
    }
    function changeEmail(username){
      var new_email = {
        "email":document.getElementById("changeEmailTxt").value,
        "username":username
      };
      $.ajax({
        type: "POST",
        url: "requests/changeEmail.php",
        data: {
          'json': JSON.stringify(new_email)
        },
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
    function changePassword(username){
      var new_password = {
        "new_password":document.getElementById("changePasswordTxt").value,
        "old_password":document.getElementById("oldPassword").value,
        "username":username
      };
      $.ajax({
        type: "POST",
        url: "requests/changePassword.php",
        data: {
          json: JSON.stringify(new_password)
        },
        success: function (response){
          response= $.parseJSON(response);
          if(response['response']==1){
            window.alert("Successfully changed password");
            location.reload();
          } else{
            window.alert("Change unsuccessful");
          }
        }
      });
    }
    function changeName(username){
      var new_name = {
        "name":document.getElementById("changeNameTxt").value,
        "username":username
      };
      $.ajax({
        type: "POST",
        url: "requests/changeName.php",
        data: {
          'json': JSON.stringify(new_name)
        },
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
    function changeBankName(username){
      var new_bankname = {
        "bankname":document.getElementById("changeBankNameTxt").value,
        "username":username
      };
      $.ajax({
        type: "POST",
        url: "requests/changeBankName.php",
        data: {
          'json': JSON.stringify(new_bankname)
        },
        success: function (response){
          window.alert("Success!");
          location.reload();
        }
      });
    }
    function changeCreditCardNb(username){
      var new_creditcardnb = {
        "creditcardnb":document.getElementById("changeCreditCardNbTxt").value,
        "username":username
      };
      $.ajax({
        type: "POST",
        url: "requests/changeCreditCardNb.php",
        data: {
          'json': JSON.stringify(new_creditcardnb)
        },
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
    function changeAccountNb(username){
      var new_accountnb = {
        "accountnb":document.getElementById("changeAccountNbTxt").value,
        "username":username
      };
      $.ajax({
        type: "POST",
        url: "requests/changeAccountNb.php",
        data: {
          'json': JSON.stringify(new_accountnb)
        },
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
    function changeAddress(username){
      var new_address = {
        "address":document.getElementById("changeAddressTxt").value,
        "username":username
      };
      $.ajax({
        type: "POST",
        url: "requests/changeAddress.php",
        data: {
          'json': JSON.stringify(new_address)
        },
        success: function (response){
          window.alert("Success!");
          location.reload();
        }
      });
    }
    function changePhoneNumber(username){
      var new_phonenumber = {
        "phonenumber":document.getElementById("changePhoneNumberTxt").value,
        "username":username
      };
      $.ajax({
        type: "POST",
        url: "requests/changePhoneNumber.php",
        data: {
          'json': JSON.stringify(new_phonenumber)
        },
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
    function returnToHomePage(){
      window.location.href="homePage.php";
    }
  </script>
  </div>
</body>
</html>
