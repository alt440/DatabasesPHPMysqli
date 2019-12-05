<?php

  require "../database_layer_get.php";
  require "../database_layer_use_cases.php";

  $mysqli = new mysqli("localhost", "root", "");
  $mysqli->select_db("comp353_final_project");

  $getRates = getRates($mysqli);

?>

<html>
<head>
  <meta charset="utf-8">
  <title>Controller Home</title>
  <script src="../js/jquery-3.4.1.min.js"></script>
</head>
<body>
  <div id="showRates">
    <h3>Current Rates</h3>
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
  </div>
  <div id="addNewRatesForm">
    <h3>Add Rates</h3>
    <label id="nbEventsTitle">Number of Events User Created: </label><input type="text" placeholder="Number of Events..." id="nbEventsTxt"><br>
    <label id="eventTypeTitle">Event Type: </label><select id="eventTypeSelection">
      <?php
        $eventTypes = getEventTypes($mysqli);

        while($row = mysqli_fetch_row($eventTypes)){
          ?>
          <option value="<?php echo $row[0]?>"><?php echo $row[0]?></option>
          <?php
        }
        ?>
    </select><br>
    <label id="storageGBTitle">Storage in GB Offered: </label><input type="text" placeholder="Storage in GB..." id="storageGBTxt"><br>
    <label id="bandwidthGBTitle">Bandwidth in GB Offered: </label><input type="text" placeholder="Bandwidth in GB..." id="bandwidthGBTxt"><br>
    <label id="priceTitle">Price: </label><input type="text" placeholder="Price in $..." id="priceTxt"><br>
    <label id="overflowBandwidthGBTitle">Price per GB for Overflowing Bandwidth: </label><input type="text" placeholder="Overflow Bandwidth Fee for each GB..." id="overflowBandwidthGBTxt"><br>
    <label id="overflowStorageGBTitle">Price per GB for Overflowing Storage: </label><input type="text" placeholder="Overflow Storage Fee for each GB..." id="overflowStorageGBTxt"><br>
  <input type="button" value="Set New Rates" id="addRates" onclick="addRates()">
  </div>
  <div id="deleteRatesForm">
    <h3>Delete Rates</h3>
    <label id="deleteRID">RID of the Rate:</label><input type="text" id="deleteRIDtxt" placeholder="RID to be deleted...">
    <input type="button" value="Delete Rate" id="deleteRIDbutton" onclick="deleteRates()">
  </div>

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
          if(response['response']!=='OK'){
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
          if(response['response']!=='OK'){
            window.alert(response['response']);
          } else{
            location.reload();
          }
        }
      });
    }

  </script>
</body>
</html>
