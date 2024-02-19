<?php
session_start();

// Check if the 'table' parameter is present in the URL
if (isset($_GET['table'])) {
    $tableNumber = $_GET['table'];
    // Start a session for the table
    $_SESSION['table_number'] = $tableNumber;
    echo '<script>window.location.href = "menu.php";</script>';
}
else{
  echo"<script>alert('No Table Found')</script>";
}
?>