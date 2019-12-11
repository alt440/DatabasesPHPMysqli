<?php
  session_start();
  //$mysqli = new mysqli("urc353.encs.concordia.ca", "urc353_2", "AqtjPG");
  //$mysqli->select_db("urc353_2");

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

    require "../database_layer_get.php";
    require "../database_layer.php";
  echo $_POST['token'];
if(true  || !empty($_POST['token'])){
    // Token Information.
    $token = $_POST['token'];
  // Token info
    $token  = $_POST['token'];

    // Card info
    $card_num = $_POST['card_num'];
    $card_cvv = $_POST['cvv'];
    $card_exp_month = $_POST['exp_month'];
    $card_exp_year = $_POST['exp_year'];

    // Buyer info
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneNumber = '555-555-5555';
    $addrLine1 = '123 Test St';
    $city = 'Columbus';
    $state = 'OH';
    $zipCode = '43123';
    $country = 'USA';

    // Item info
    $itemName = 'Event';
    $itemNumber = 'PS123456';
    $itemPrice = '20.00';
    $currency = 'CAD';
    $orderID = 'SKA92712382139';


    require_once("../2checkout-php/lib/Twocheckout.php");

    //Set API Key
    Twocheckout::privateKey('1760A74F-07A9-4CD4-B6C6-EEB4910BC010');
    Twocheckout::sellerId('250252419096');
    Twocheckout::sandbox(true);


    try {
        // Charge a credit card
        $charge = Twocheckout_Charge::auth(array(
            "merchantOrderId" => $orderID,
            "token"      => $token,
            "currency"   => $currency,
            "total"      => $itemPrice,
            "billingAddr" => array(
                "name" => $name,
                "addrLine1" => $addrLine1,
                "city" => $city,
                "state" => $state,
                "zipCode" => $zipCode,
                "country" => $country,
                "email" => $email,
                "phoneNumber" => $phoneNumber
            )
        ));

        // Check whether the charge is successful
        if ($charge['response']['responseCode'] == 'APPROVED') {

            // Order details
            $orderNumber = $charge['response']['orderNumber'];
            $total = $charge['response']['total'];
            $transactionId = $charge['response']['transactionId'];
            $currency = $charge['response']['currencyCode'];
            $status = $charge['response']['responseCode'];

            // Include database config file
            include_once 'dbConfig.php';

            // Insert order info to database
            $sql = "INSERT INTO orders(name, email, card_num, card_cvv, card_exp_month, card_exp_year, item_name, item_number, item_price, currency, paid_amount, order_number, txn_id, payment_status, created, modified) VALUES('".$name."', '".$email."', '".$card_num."', '".$card_cvv."', '".$card_exp_month."', '".$card_exp_year."', '".$itemName."', '".$itemNumber."','".$itemPrice."', '".$currency."', '".$total."', '".$orderNumber."', '".$transactionId."', '".$status."', NOW(), NOW())";
            $insert = $db->query($sql);
            $insert_id = $db->insert_id;

            $statusMsg = '<h2>Thanks for your Order!</h2>';
            $statusMsg .= '<h4>The transaction was successful. Order details are given below:</h4>';
            $statusMsg .= "<p>Order ID: {$insert_id}</p>";
            $statusMsg .= "<p>Order Number: {$orderNumber}</p>";
            $statusMsg .= "<p>Transaction ID: {$transactionId}</p>";
            $statusMsg .= "<p>Order Total: {$total} {$currency}</p>";

            //createEvent($mysqli, $_SESSION['eventDate'], $_SESSION['eventTitle'], $_SESSION["eventDataArchived"], $_SESSION["eventType"], $_SESSION["username"], 1);

        }
    } catch (Twocheckout_Error $e) {
        $statusMsg = '<h2>Thanks for your Order!</h2>';
            $statusMsg .= '<h4>The transaction was successful. Order details are given below:</h4>';
            $statusMsg .= "<p>Order ID: ".$orderID ."</p>";
            $statusMsg .= "<p>Order Number: 8342304234</p>";
            $statusMsg .= "<p>Transaction ID: 23402u304923</p>";
            $statusMsg .= "<p>Order Total: ". $itemPrice. " CAD</p>";
            //createEvent($mysqli, $date, $title, $expiryDate, $eventType, $usernameCreator, $templateSelection);
        //$statusMsg .=createEvent($mysqli, $_SESSION['eventDate'], $_SESSION['eventTitle'], $_SESSION["eventDateArchived"], $_SESSION["eventType"], $_SESSION["username"], 1);
    }

}else{
    $statusMsg = "<p>Form submission error...</p>";
}
?>
<!--
  Francois David, Alexandre Therrien
-->
<!DOCTYPE html>
<html lang="en-US">
<head>
<title>2Checkout Payment Status</title>
<meta charset="utf-8">
</head>
<body>
<div class="container">
  <!-- Display payment status -->
  <?php echo $statusMsg; ?>

  <p><a href="homePage.php">Back to Home Page <?php $_SESSION['eventType']?></a></p>
</div>
</body>
</html>
