<?php
session_start();
// Check if the 'table' parameter is present in the URL
if (isset($_GET['table']) && $_GET['table'] != null) {
  $tableNumber = $_GET['table'];
  // Start a session for the table
  $_SESSION['table_number'] = $tableNumber;
  // Set the default time zone to Indian Standard Time (IST)
  date_default_timezone_set('Asia/Kolkata');
  // Get the current date and time in the Indian time zone
  $currentDateTime = date('Y-m-d H:i:s');
  // Store the current date and time in a session variable
  $_SESSION['indianDateTime'] = $currentDateTime;
  echo "<script>localStorage.removeItem('giveinfo');</script>";

  // Check if SessionStartTime also checks for name number exists in localStorage and pass it as a parameter in the URL
  echo "<script>
    if (localStorage.getItem('SessionStartTime') === null) {
      localStorage.setItem('SessionStartTime', '" . $currentDateTime . "');
      window.location.href = 'menu.php';
    }else{
      if(localStorage.getItem('SessionStartTime') !== null && localStorage.getItem('SessionStartTime') !== null){
        window.location.href = 'menu.php?SessionStartTime=' + localStorage.getItem('SessionStartTime') + '&username=' + localStorage.getItem('username') + '&usernumber=' + localStorage.getItem('usernumber');
      }else{
        window.location.href = 'menu.php?SessionStartTime=' + localStorage.getItem('SessionStartTime');
      }
    // Redirect to menu.php with SessionStartTime as a parameter
    }
  </script>";
  // echo '<script>window.location.href = "menu.php";</script>';
} else {
  echo "<script>alert('No Table Found')</script>";
}
?>