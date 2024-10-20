<?php
session_start();
if (isset($_SESSION['waiter'])) { //condition check for login
    include "config/dbconnection.php"; //Database Conn
    // Set flag as Atended in database
    if (isset($_POST['req'])) {
        $resp = $_POST['req'];
        $callid = $_POST['callid'];
        $sql = "UPDATE waiter SET flag = '$resp' WHERE CallID = '$callid'";
        mysqli_query($conn, $sql);
        header("Location: waiterresp.php");
    }
    ?>

    <?php
} else {
    echo "Unaurthorized User";
}
?>