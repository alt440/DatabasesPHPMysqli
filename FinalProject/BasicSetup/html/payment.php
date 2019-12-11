<?php
    session_start();

    require "../database_layer_get.php";
    require "../database_layer.php";
    require "../database_layer_use_cases.php";

    //$mysqli = new mysqli("urc353.encs.concordia.ca", "urc353_2", "AqtjPG");
    //$mysqli->select_db("urc353_2");
    $mysqli = new mysqli("localhost", "root", "");
    $mysqli->select_db("comp353_final_project");
    $eventType = $_REQUEST["eventType"];
    $eventTitle = $_REQUEST["eventTitle"];
    $eventDate = $_REQUEST["dateOfEvent"];
    $eventArchivedDate = $_REQUEST["archivedDateOfEvent"];
    $username = $_SESSION["username"];
    $eventTemplate = $_REQUEST["eventTemplate"];
    $rateID = $_REQUEST["rateID"];
    if(!isRate($mysqli, $rateID)){
      echo '<script>window.alert("Invalid Rate ID. Redirecting to previous page.");window.location.href="editUserMemberships.php";</script>';
    }

    //extract year of today, and year given by date to be archived
    $yearToday = intval(date("Y"));
    $yearArchived = intval(substr($eventArchivedDate, 0,4));
    $yearDifference = $yearArchived-$yearToday;
    //so that it does not cost nothing
    if($yearDifference == 0){
      $yearDifference = 1;
    }

    //get current timestamp and compare to what the user inputted
    $eventTime = convertDateStampToTimeStamp($eventDate);
    $archivedTime = convertDateStampToTimeStamp($eventArchivedDate);

    //comparing rates information with actual user information
    $rateInfo = getRate($mysqli, $rateID);
    //verifying user input. must have the right number of events done before,
    //and must be selecting the right event type for the rates.
    if(getNbEventsOfUser($mysqli, $username) < $rateInfo[1]){
      echo '<script>window.alert("Number of events is not enough to select this rate.");window.location.href="editUserMemberships.php";</script>';
    } else if(strcmp($eventType, $rateInfo[2]) != 0){
      echo '<script>window.alert("You have not selected a rate having your event type for your event.");window.location.href="editUserMemberships.php";</script>';
    } else if($yearDifference < 0){
      echo '<script>window.alert("The year the event is archived is before this time. Make sure it is in the future."); window.location.href="editUserMemberships.php";</script>';
    } else if($archivedTime-$eventTime < 0){
      echo '<script>window.alert("Verify the time of the event is before the event is archived.");window.location.href="editUserMemberships.php";</script>';
    } else if($eventTemplate<1 || $eventTemplate>2){
      echo '<script>window.alert("Verify you have entered a valid template number.");window.location.href="editUserMemberships.php";</script>';
    }

    echo '<script>window.alert("The payment system may throw an unauthorized error because of API keys which we do not have control over. The system should work, but in case it does not, the event will still be added even if transaction fails.");</script>';

    // For the processing of the payment.
    $_SESSION["eventDate"] = $eventDate;
    $_SESSION["eventDateArchived"] = $eventArchivedDate;
    $_SESSION["eventTitle"] = $eventTitle;
    $_SESSION["eventType"] = $eventType;

    ?>
<!--
  Francois David, Alexandre Therrien
-->
<html>
    <head>
        <title>Event Requests</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>-->

        <!-- 2Checkout JavaScript library -->
        <script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
        <script src="../js/jquery-3.4.1.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/paymentCss.css"/>
    </head>
    <body>
        <?php
        echo "<p>Disclaimer: By applying this rate, you will get ".$rateInfo[3]."GB of storage and ".$rateInfo[4]."GB of bandwidth. An extra payment of ".$rateInfo[6]."$ and ".$rateInfo[7]."$ applies every time bandwidth and storage goes over. The price per year for the event being active is ".$rateInfo[5]."$.</p>";
        if( $eventType == "non-profit"){
            echo "<h1> You do not have to proceed to checkout since this type of event is free. You can click here to go back to the homepage. The event will be processed by a system administrator sooon.</h1>";
            echo "<a href='homePage.php'>Home Page</a>";

            createEvent($mysqli, $eventDate, $eventTitle, $eventArchivedDate, $eventType, $username, $eventTemplate);
        } else {
             echo "<h1>The charge for the rate selected and this type of event (".$eventType .") is ".$rateInfo[5]."$. You can click here to finalize the event.</h1>";
               $_SESSION["eventType"] = $eventType;

               //adding the event in case something goes wrong...
            createEvent($mysqli, $eventDate, $eventTitle, $eventArchivedDate, $eventType, $username, $eventTemplate);
        }
        ?>
    <div class="body-text">Please enter the necessary information.</div>


     <div class="form-container">
    <div class="personal-information">
    <h5>Charge $<?php echo $rateInfo[5]*$yearDifference; ?> CAD with 2Checkout.</h5>

    <!-- credit card form -->
    <form id="paymentFrm" method="post" action="paymentSubmit.php">
        <div>
            <label>NAME</label>
            <input type="text" name="name" id="name" placeholder="Enter name" required autofocus>
        </div>
        <div>
            <label>EMAIL</label>
            <input type="email" name="email" id="email" placeholder="Enter email" required>
        </div>
        <div>
            <label>CARD NUMBER</label>
            <input type="text" name="card_num" id="card_num" placeholder="Enter card number" autocomplete="off" required>
        </div>
        <div>
            <label><span>EXPIRY DATE</span></label>
            <input type="number" name="exp_month" id="exp_month" placeholder="MM" required>
            <input type="number" name="exp_year" id="exp_year" placeholder="YY" required>
        </div>
        <div>
            <label>CVV</label>
            <input type="number" name="cvv" id="cvv" autocomplete="off" required>
        </div>

        <!-- hidden token input -->
        <input id="token" name="token" type="hidden" value="">

        <!-- submit button -->
        <input type="submit" class="btn btn-success" value="Submit Payment">
    </form>
</div>
		  </div>

		  <?php ?>

<script>
// Called when token created successfully.
var successCallback = function(data) {
  var myForm = document.getElementById('paymentFrm');

  // Set the token as the value for the token input
  myForm.token.value = data.response.token.token;

  // Submit the form
  myForm.submit();
};

// Called when token creation fails.
var errorCallback = function(data) {
  if (data.errorCode === 200) {
    tokenRequest();
  } else {
    alert(data.errorMsg);
  }
};

var tokenRequest = function() {
  // Setup token request arguments
  var args = {
    sellerId: "901389630", //"250252419096",
    publishableKey: "A4B8A470-61A2-470E-9DCB-013A033FD206", //"1760A74F-07A9-4CD4-B6C6-EEB4910BC010",
    ccNo: $("#card_num").val(),
    cvv: $("#cvv").val(),
    expMonth: $("#exp_month").val(),
    expYear: $("#exp_year").val()
  };

  // Make the token request
  TCO.requestToken(successCallback, errorCallback, args);
};

$(function() {
  // Pull in the public encryption key for our environment
  TCO.loadPubKey('sandbox');

  $("#paymentFrm").submit(function(e) {
    // Call our token request function
    tokenRequest();

    // Prevent form from submitting
    return false;
  });
});
</script>

	</body>


</html>
