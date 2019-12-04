<!-- 
  authors: Alexandre Therrien, Daniel Vigny-Pau
-->

<?php

  session_start();
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
  <title>Modify User Information</title>
  <link rel="stylesheet" type="text/css" href="css/editInfo.css"/>
  <script src="../js/jquery-3.4.1.min.js"></script>
</head>
<body>
  <div class="bigBox">
  <h2>Modify User Information</h2>
  <table id="userInformation">
    <tr>
      <td class="currentInfo">Current Email: <?php echo $userInfo[3];?></td>
    </tr><tr>
      <td class="sub">Change Email</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeEmailTxt" placeholder="New email..."><input type="button"  class="newButton" value="Change Email" id="changeEmail" onclick="changeEmail('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo">Password cannot be seen for security reasons</td>
    </tr><tr>
      <td class="sub">Change Password</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changePasswordTxt" placeholder="New password..."><input type="text" class="newText" id="oldPassword" placeholder="Old password..."><input type="button" class="newButton" value="Change Password" id="changePassword" onclick="changePassword('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo">Current Name: <?php echo $userInfo[4];?></td>
    </tr><tr>
      <td class="sub">Change Name</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeNameTxt" placeholder="New name..."><input type="button" class="newButton" value="Change Name" id="changeName" onclick="changeName('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo">Bank Name cannot be seen for security reasons</td>
    </tr><tr>
      <td class="sub">Change Bank Name</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeBankNameTxt" placeholder="New bank name..."><input type="button" class="newButton" value="Change Bank Name" id="changeBankName" onclick="changeBankName('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo">Credit card number cannot be seen for security reasons</td>
    </tr><tr>
      <td class="sub">Change Credit Card Number</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeCreditCardNbTxt" placeholder="New credit card number..."><input type="button" class="newButton" value="Change Credit Card Number" id="changeCreditCardNb" onclick="changeCreditCardNb('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo">Account number cannot be seen for security reasons</td>
    </tr><tr>
      <td class="sub">Change Account Number</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeAccountNbTxt" placeholder="New account number..."><input type="button" class="newButton" value="Change Account Number" id="changeAccountNb" onclick="changeAccountNb('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo">Current Address: <?php echo $userInfo[10];?></td>
    </tr><tr>
      <td class="sub">Change Address</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changeAddressTxt" placeholder="New address..."><input type="button" class="newButton" value="Change Address" id="changeAddress" onclick="changeAddress('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo">Current Phone Number: <?php echo $userInfo[11];?></td>
    </tr><tr>
      <td class="sub">Change Phone Number</td>
    </tr><tr>
      <td><input type="text" class="newText" id="changePhoneNumberTxt" placeholder="New phone number..."><input type="button" class="newButton"  value="Change Phone Number" id="changePhoneNumber" onclick="changePhoneNumber('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td class="currentInfo">Delete User Account</td>
    </tr><tr>
      <td><input type="button" class="newButton" value="Delete User Account" id="deleteAllUser" onclick="deleteUser('<?php echo $userInfo[1];?>')"></td>
    </tr>
  </table>
  <input type="button" class="returnButton" value="Return to home page" id="returnToHomePage" onclick="returnToHomePage()">

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
          location.reload();
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
          console.log(response);
          response= $.parseJSON(response);
          if(response['response']==1){
            window.alert("Successfully changed password");
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
          location.reload();
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
          location.reload();
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
          location.reload();
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
          location.reload();
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
