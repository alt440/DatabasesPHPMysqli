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
  <script src="../js/jquery-3.4.1.min.js"></script>
</head>
<body>
  <h2>Modify User Information</h2>
  <table id="userInformation">
    <tr>
      <td>Current Email: <?php echo $userInfo[3];?></td>
    </tr><tr>
      <td>Change Email to: <input type="text" id="changeEmailTxt" placeholder="New email..."><input type="button" value="Change Email" id="changeEmail" onclick="changeEmail('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td>Password cannot be seen for security reasons</td>
    </tr><tr>
      <td>Change Password to: <input type="text" id="changePasswordTxt" placeholder="New password..."><input type="text" id="oldPassword" placeholder="Old password..."><input type="button" value="Change Password" id="changePassword" onclick="changePassword('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td>Current Name: <?php echo $userInfo[4];?></td>
    </tr><tr>
      <td>Change Name to: <input type="text" id="changeNameTxt" placeholder="New name..."><input type="button" value="Change Name" id="changeName" onclick="changeName('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td>Bank Name cannot be seen for security reasons</td>
    </tr><tr>
      <td>Change Bank Name to: <input type="text" id="changeBankNameTxt" placeholder="New bank name..."><input type="button" value="Change Bank Name" id="changeBankName" onclick="changeBankName('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td>Credit card number cannot be seen for security reasons</td>
    </tr><tr>
      <td>Change Credit Card Number to: <input type="text" id="changeCreditCardNbTxt" placeholder="New credit card number..."><input type="button" value="Change Credit Card Number" id="changeCreditCardNb" onclick="changeCreditCardNb('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td>Account number cannot be seen for security reasons</td>
    </tr><tr>
      <td>Change Account Number to: <input type="text" id="changeAccountNbTxt" placeholder="New account number..."><input type="button" value="Change Account Number" id="changeAccountNb" onclick="changeAccountNb('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td>Current Address: <?php echo $userInfo[10];?></td>
    </tr><tr>
      <td>Change Address to: <input type="text" id="changeAddressTxt" placeholder="New address..."><input type="button" value="Change Address" id="changeAddress" onclick="changeAddress('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td>Current Phone Number: <?php echo $userInfo[11];?></td>
    </tr><tr>
      <td>Change Phone Number to: <input type="text" id="changePhoneNumberTxt" placeholder="New phone number..."><input type="button" value="Change Phone Number" id="changePhoneNumber" onclick="changePhoneNumber('<?php echo $userInfo[1];?>')"></td>
    </tr><tr>
      <td>Delete User Account: <input type="button" value="Delete User Account" id="deleteAllUser" onclick="deleteUser('<?php echo $userInfo[1];?>')"></td>
    </tr>
  </table>
  <input type="button" value="Return to home page" id="returnToHomePage" onclick="returnToHomePage()">

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
</body>
</html>
