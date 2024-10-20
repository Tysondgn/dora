<?php
include "config/dbconnection.php";
session_start();

// Uploading on 2 tables userprofile then customerorders
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['upload-total-price']) {

    $username = $_POST["UserName"]; // UserName
    $userphone = $_POST["UserPhone"]; // UserPhone

    // Check if the username and phone already exist in the customerprofile table
    $check_query = "SELECT * FROM customerprofile WHERE CustomerName = '$username' AND CustomerPhone = '$userphone'";
    $result = $conn->query($check_query);

    if ($result->num_rows > 0) {
        // Username or phone already exists
        echo "<script>console.log('Username or phone already exists in the database.')</script>";
        echo "<script>localStorage.setItem('username','" . $username . "');</script>";
        echo "<script>localStorage.setItem('usernumber','" . $userphone . "');</script>";
        
        // Fetch the result row
        $row = $result->fetch_assoc();
        // Set session variable
        $_SESSION["customerid"] = $row['CustomerID'];
        $_SESSION["CustomerName"] = $row['CustomerName'];
        $_SESSION["CustomerPhone"] = $row['CustomerPhone'];
    } else {
        // Username and phone don't exist, insert the data into the customerprofile table
        $insert_query = "INSERT INTO customerprofile (CustomerName, CustomerPhone) VALUES ('$username', '$userphone')";
        
        if ($conn->query($insert_query) === TRUE) {
            // Data inserted successfully, set user id as customerid
            $customerid = $conn->insert_id;
            echo "Data inserted successfully. User ID: $customerid";
            echo "<script>localStorage.setItem('username','" . $username . "');</script>";
            echo "<script>localStorage.setItem('usernumber','" . $userphone . "');</script>";
            // You can set the $customerid variable in your session for future use
            $_SESSION["customerid"] = $customerid;
            $_SESSION["CustomerName"] = $username;
            $_SESSION["CustomerPhone"] = $userphone;
        } else {
            echo "Error inserting data: " . $conn->error;
        }
    }

    // Free up the result set
    $result->free_result();

    
    // Checking and Updating TableStatus Active and Available-------------------------------------------------------------------------------------------------------
    $TableNumber = $_SESSION['table_number'];
    $check_query = "SELECT * FROM tablestatus WHERE TableNumber = '$TableNumber'";
    $result = $conn->query($check_query);
    $row = $result->fetch_assoc();
    if($row['TableFlag'] == 'Available'){
        $update_query = "UPDATE tablestatus SET TableFlag = 'Active' WHERE TableNumber = '$TableNumber'";
        $conn->query($update_query);
    }
    // elseif($row['TableFlag'] == 'Active'){

    // }


    // Free up the result set
    $result->free_result();


    // Set the default time zone to Indian Standard Time (IST)-------------------------------------------------------------------------------------------------------
    date_default_timezone_set('Asia/Kolkata');

    $CustomerID = $_SESSION["customerid"];
    $OrderStatus = "Placed";
    $currentDateTime = date('Y-m-d H:i:s');     // Get the current date and time in the Indian time zone
    $TotalAmount = $_POST['upload-total-price'];
    $TableNumber = $_SESSION['table_number'];
    $cartdata = $_POST['upload-json'];
    $dishdes = $_POST['dishdes'];
    $TableStatus = 'Active';
    // Prepare and execute the SQL query using prepared statements
    $query = $conn->prepare("INSERT INTO customerorders (CustomerID, OrderStatus, OrderTime, TotalAmount, TableNumber, json_cart, DishDescription, TableStatus) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind parameters
    $query->bind_param("issdssss", $CustomerID, $OrderStatus, $currentDateTime, $TotalAmount, $TableNumber, $cartdata, $dishdes, $TableStatus);

    // Execute the query
    if ($query->execute()) {
        // echo "Order placed successfully";
        echo "<script>localStorage.removeItem('giveinfo');</script>";
        echo '<script>window.location.href = "order_confirmation.php";</script>';
    } else {
        // Log the error instead of displaying it to the user
        error_log("Error: " . $query->error);
        echo "An error occurred while placing the order. Please try again.";
    }
    // Close the prepared statement and the database connection
    $query->close();
    $conn->close();
} else {
    // Redirect to menu.php
    header("Location: menu.php");
    exit(); // Ensure that no other code is executed after the redirect   
}
?>