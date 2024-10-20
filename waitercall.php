<?php
session_start();
include "config/dbconnection.php";
if(isset($_POST['waiter'])){
    if(isset($_SESSION["CustomerName"])){

        $tableNumber = $_SESSION['table_number'];
        date_default_timezone_set('Asia/Kolkata');
        $dateTime = date('Y-m-d h-i-s');
        $customerName = $_SESSION["CustomerName"];

        // SQL query for insertion
        $sql = "INSERT INTO `waiter` (`TableNumber`, `DateTime`, `CustomerName`) 
                VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("sss", $tableNumber, $dateTime, $customerName);

        // Execute the statement
        $result = $stmt->execute();

        // Check if the insertion was successful
        if ($result) {
            echo "<script>console.log('Existing User Data inserted successfully!')</script>";
        } else {
            echo "<script>console.log('Error: " . $stmt->error . "')</script>";
        }

        // Close the statement
        $stmt->close();

    }else{

        $tableNumber = $_POST['waiter'];
        date_default_timezone_set('Asia/Kolkata');
        $dateTime = date('Y-m-d h-i-s');

        // SQL query for insertion
        $sql = "INSERT INTO `waiter` (`TableNumber`, `DateTime`) 
                VALUES (?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bind_param("ss", $tableNumber, $dateTime);

        // Execute the statement
        $result = $stmt->execute();

        // Check if the insertion was successful
        if ($result) {
            echo "<script>console.log('New User Data inserted successfully!')</script>";
        } else {
            echo "<script>console.log('Error: " . $stmt->error . "')</script>";
        }
        // close the statemnet
        $stmt->close();
    }
    // close the connection
    $conn->close();

}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waiter Call 🤵</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Bell For waiter */
        #bell-container {
            cursor: pointer;
            font-size: 30px;
            animation: bellAnimation 0.5s ease-in-out 1ms infinite;
        }

        @keyframes bellAnimation {

            0%,
            100% {
                transform: translateX(0) rotate(-21deg);
            }

            50% {
                transform: translateX(10px) rotate(21deg);
            }
        }
    </style>
</head>

<body style="background-color: #2b2a33; color: #fbfbfe;">

    <div class="">
        <div class="d-flex justify-content-center mt-5 pt-5">
            <h1>Ringing Bell For Waiter</h1>
        </div>
        
        <div class="d-flex justify-content-center mt-5 pt-5 pe-4
        ">
            <img src="assets/images/bell.png" class="pb-10px" id="bell-container" style=" rotate: -21deg;"
             height="200px" width="auto">
        </div>

        <br>
        <div class="h4 text-center w-100">
            The Waiter Will Arrive Soon.
        </div>

        <div class="d-flex justify-content-center mt-5">
            <button class="btn btn-lg btn-danger" onclick="menu()">< Back To Menu</button>
        
        </div>
    </div>

    <script>
        function menu() {
            window.location.href = "menu.php";
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>