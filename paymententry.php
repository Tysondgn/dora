<?php
session_start();
include "config/dbconnection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['customerid'])) {
    $customerid = $_SESSION['customerid'];
    $Orderid = $_POST['orderid'];
    $megacart = $_POST['megacart'];
    $PaymentAmount = $_POST['grandtotal'];
    $PaymentStatus = 'Pending';
    $TableNumber = $_SESSION['table_number'];
    $currentDateTime = date("Y-m-d H:i:s");

    // Entry does not exist, insert it
    $insertQuery = $conn->prepare("INSERT INTO onlinepayments (CustomerID, OrderID, PaymentAmount, PaymentStatus, TableNumber, MegaCart, TransactionDateTime) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertQuery->bind_param("isdssss", $customerid, $Orderid, $PaymentAmount, $PaymentStatus, $TableNumber, $megacart, $currentDateTime);

    if ($insertQuery->execute()) {
        echo "Data inserted successfully into OnlinePayments table.";
        header("Location: generatebill.php");

    } else {
        // Log the error instead of displaying it to the user
        error_log("Error inserting data: " . $insertQuery->error);
        echo "An error occurred while inserting data into OnlinePayments table. Please try again.";
    }
    // Close the queries
    $insertQuery->close();
}else{
    echo "This is Bill Page";
}

$TableNumber = $_SESSION['table_number'];
$TableStatus = 'Available';
// Update Table Status in Tables table
$updateQuery = $conn->prepare("UPDATE customerorders SET TableStatus = ? WHERE TableNumber = ?");
$updateQuery->bind_param("si", $TableStatus, $TableNumber);

if ($updateQuery->execute()) {
    // echo "Table status updated successfully on customerorder table.";
} else {
    // Log the error instead of displaying it to the user
    error_log("Error updating table status: " . $updateQuery->error);
    echo "An error occurred while updating table status. Please try again.";
}
$updateQuery = $conn->prepare("UPDATE tablestatus SET TableFlag = ? WHERE TableNumber = ?");
$updateQuery->bind_param("si", $TableStatus, $TableNumber);

if ($updateQuery->execute()) {
    // echo "Table status updated successfully on tablestatus table.";
} else {
    // Log the error instead of displaying it to the user
    error_log("Error updating table status: " . $updateQuery->error);
    echo "An error occurred while updating table status. Please try again.";
}
$updateQuery = $conn->prepare("UPDATE countercheck SET TableStatus = ? WHERE TableNumber = ?");
$updateQuery->bind_param("si", $TableStatus, $TableNumber);

if ($updateQuery->execute()) {
    // echo "Table status updated successfully on countercheck table.";
} else {
    // Log the error instead of displaying it to the user
    error_log("Error updating table status: " . $updateQuery->error);
    echo "An error occurred while updating table status. Please try again.";
}

// Close the queries
$updateQuery->close();


// Write session changes and close the session
// session_write_close();

?>