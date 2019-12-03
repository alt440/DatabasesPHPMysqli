function searchEvent(){
  //find the search bar
  var element = document.getElementById("searchBar").value;
  //window.location.href = "../html/requests/searchEvent.php?searchEvent="+element;
  $.ajax({
    url: "../html/requests/searchEvent.php?searchEvent="+element,
    success: function(){
      window.location.href = "../html/eventPage.php";
      //location.reload();
    }
  });
}

function searchUser(){
  //find the search bar
  var element = document.getElementById("searchBar").value;
  //window.location.href = "../html/requests/searchEvent.php?searchEvent="+element;
  $.ajax({
    url: "../html/requests/searchUser.php?searchUser="+element,
    success: function(){
      window.location.href = "../html/homePage.php";
      //location.reload();
    }
  });
}
