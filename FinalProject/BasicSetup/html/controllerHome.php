<?php
  session_start();
  require "../database_layer_get.php";
  require "../database_layer_use_cases.php";

  //redirect to login screen if not logged in
  if(!isset($_SESSION['username']) || $_SESSION['username']==''){
    header('Location:index.html');
  }

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");
  $getRates = getRates($mysqli);

  //confirm the controller is accessing the page
  $username = $_SESSION['username'];
  $current_user = getUser($mysqli, $username);
  if($current_user[6] != 1){
    header('Location:homePage.php');
  }
?>
<!--
  authors:  Alexandre Therrien 40057134,
            Daniel Vigny-Pau 40034769
 -->
<html>
<head>
  <meta charset="utf-8">
  <title>Controller Home</title>
  <script src="../js/jquery-3.4.1.min.js"></script>
  <link rel="stylesheet" type="text/css" href="css/editInfo.css"/>
</head>
<body>
  <div class="bigBox">
  <div id="showRates">
    <h2>Current Rates</h2>
    <br/>
    <table id="showRatesTable">
      <tr>
        <th>Rates ID</th>
        <th>Number Events</th>
        <th>Event Type</th>
        <th>Storage GB</th>
        <th>Bandwidth GB</th>
        <th>Price</th>
        <th>Bandwidth Overflow Fee</th>
        <th>Storage Overflow Fee</th>
      </tr>
      <?php
      if(!(is_bool($getRates)||mysqli_num_rows($getRates)==0)){
        while($row = mysqli_fetch_row($getRates)){
          ?>
          <tr>
            <?php
            for($i=0;$i<sizeof($row);$i++){
              ?>
              <td><?php echo $row[$i];?></td>
              <?php
            }
            ?>
          </tr>
          <?php
        }
      }
      ?>
    </table>
  </div></br></br>
  <div id="addNewRatesForm">
    <p class="subtitle">ADD RATES</p><br/>
    <label id="nbEventsTitle"><p class="subtitle">Number of Events User Created: </label><input type="text" class="newText" placeholder="Number of Events" id="nbEventsTxt"></p><br>
    <label id="eventTypeTitle"><p class="subtitle">Event Type: </label><select id="eventTypeSelection"></p>
      <?php
        $eventTypes = getEventTypes($mysqli);
        while($row = mysqli_fetch_row($eventTypes)){
          ?>
          <option value="<?php echo $row[0]?>"><?php echo $row[0]?></option>
          <?php
        }
        ?>
    </select><br/><br/>
    <label id="storageGBTitle"><p class="subtitle">Storage in GB Offered: </label><input type="text" class="newText" placeholder="Storage in GB" id="storageGBTxt"></p><br>
    <label id="bandwidthGBTitle"><p class="subtitle">Bandwidth in GB Offered: </label><input type="text" class="newText" placeholder="Bandwidth in GB" id="bandwidthGBTxt"></p><br>
    <label id="priceTitle"><p class="subtitle">Price: </label><input type="text" class="newText" placeholder="Price in $" id="priceTxt"></p> <br>
    <label id="overflowBandwidthGBTitle"><p class="subtitle">Price per GB for Overflowing Bandwidth: </label><input type="text" class="newText" placeholder="Overflow Bandwidth Fee for each GB" id="overflowBandwidthGBTxt"> </p><br>
    <label id="overflowStorageGBTitle"><p class="subtitle">Price per GB for Overflowing Storage: </label><input type="text" class="newText" placeholder="Overflow Storage Fee for each GB" id="overflowStorageGBTxt"> </p><br>
  <input type="button" class="centeredButton" value="SET NEW RATES" id="addRates" onclick="addRates()">
  </div>
  <div id="deleteRatesForm">
    <br/><br/>
    <p class="subtitle">DELETE RATES</p></br>
    <label id="deleteRID"><p class="subtitle">RID of the Rate: </label><input type="text" class="newText" id="deleteRIDtxt" placeholder="RID to be deleted..."></p>
    <input type="button" class="centeredButton" value="DELETE RATE" id="deleteRIDbutton" onclick="deleteRates()">
  </div>

  <input type="button" class="returnButton" value="RETURN TO HOMEPAGE" id="returnToHomePage" onclick="returnToHomePage()">

  <script>
    function addRates(){
      var nbEvents = document.getElementById('nbEventsTxt').value;
      var e = document.getElementById('eventTypeSelection');
      var eventType = e.options[e.selectedIndex].value;
      var storageGB = document.getElementById('storageGBTxt').value;
      var bandwidthGB = document.getElementById('bandwidthGBTxt').value;
      var price = document.getElementById('priceTxt').value;
      var overflowBandwidth = document.getElementById('overflowBandwidthGBTxt').value;
      var overflowStorage = document.getElementById('overflowStorageGBTxt').value;
      $.ajax({
        type: "POST",
        url: "requests/addRatesController.php",
        data: {
          'json': JSON.stringify({"nbEvents":nbEvents,"eventType":eventType,"storageGB":storageGB,"bandwidthGB":bandwidthGB,"price":price,"overflowBandwidth":overflowBandwidth,"overflowStorage":overflowStorage})
        },
        success: function (response){
          response = $.parseJSON(response);
          if(response['response']!=="1"){
            window.alert(response['response']);
          } else{
            location.reload();
          }
        }
      });
    }
    function deleteRates(){
      var RID = document.getElementById('deleteRIDtxt').value;
      $.ajax({
        type: "POST",
        url: "requests/deleteRatesController.php",
        data: {
          'json': JSON.stringify({"RID":RID})
        },
        success: function (response){
          response = $.parseJSON(response);
          if(response['response']!=="1"){
            window.alert(response['response']);
          } else{
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
