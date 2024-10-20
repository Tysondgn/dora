<?php
session_start();

// Unset the session variable
unset($_SESSION['user-kitchen']);
unset($_SESSION['user-counter']);
unset($_SESSION['user-admin']);
unset($_SESSION['user-customer']);
unset($_SESSION['waiter']);

// Optionally, destroy the entire session
// session_destroy();

// Return a response (you can customize this message)
echo "Logout successful!";
echo '<script>window.location.href = "adminpanel.php";</script>';
?>
