<?PHP include "config/dbconnection.php";
session_start();
?>
<?PHP
$orderid = [];
$cname = [];
$cnumber = [];
$megacart = [];
?>



<?php
// Fetch CustomerID from the customer profile
if (isset ($_SESSION['table_number'])) {
    $tablenumber = $_SESSION['table_number'];
    $sql = "SELECT TableFlag FROM tablestatus WHERE TableNumber = '$tablenumber'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $TableStatus = $row['TableFlag'];
}
if (isset ($_SESSION['table_number']) && isset ($_SESSION['indianDateTime']) && $TableStatus == 'Active') { ?>

    <!doctype html>
    <html lang="en">

    <!-- Bootstrap and CSS only -->

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Order Status</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- AOS -->
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        <style>
            .iframe-container {
                position: relative;
                overflow: hidden;
                padding-top: 56.25%;
                /* 16:9 aspect ratio (height/width) */
            }

            .iframe-container iframe {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }

            .table-responsive {
                max-height: 300px;
                /* Adjust the max height as needed */
            }
        </style>
    </head>

    <body>

        <header class="fixed-top d-flex justify-content-center w-100 fw-bold fs-5 mx-auto text-center" style="height: 40px;"
            data-aos="fade-down">
            <div class="w-75 rounded-5 rounded-top-0 bg-warning">
                Student's Corner
            </div>
        </header>

        <section id="main-section" class="mt-5" style="margin-bottom: 100px;">
            <div class="row m-3">
                <?php
                if (isset ($_SESSION['table_number']) && isset ($_SESSION['indianDateTime'])) {
                    $tableNumber = $_SESSION['table_number'];
                    $tableStatus = "Active";
                    // $customerid = $_SESSION['customerid'];
                    $currentDateTime = $_SESSION['indianDateTime'];
                    $ret = mysqli_query($conn, "SELECT * FROM customerorders WHERE TableNumber = '$tableNumber' AND TableStatus = '$tableStatus' AND OrderTime > '$currentDateTime' ORDER BY OrderTime DESC");
                    $cnt = 1;
                    $grandtotal = 0;
                    if ($ret) {
                        while ($row = mysqli_fetch_array($ret)) {
                            if ($row) {
                                // Collect OrderID values in the array
                                // array_push($orderid, $row['OrderID']);
                                if ($row['OrderStatus'] !== 'Rejected') {
                                    $orderid[] = $row['OrderID'];
                                }
                                ?>
                                <!--Fetch the Records -->
                                <div class="col border border-light-subtle rounded-4 shadow shadow-4 bg-light p-3 m-2">
                                    <tr>
                                        <td>
                                            <?php echo $cnt; ?>
                                        </td>
                                        <td>
                                            <?php
                                            $customerid = $row['CustomerID'];
                                            $unamefetch = mysqli_query($conn, "SELECT * FROM customerprofile WHERE CustomerID ='$customerid'");
                                            $uname = mysqli_fetch_array($unamefetch);
                                            echo 'Order By : ' . $uname['CustomerName'];
                                            $customerName = $uname['CustomerName'];
                                            if (!in_array($customerName, $cname)) {
                                                $cname[] = $customerName;
                                                $cnumber[] = $uname['CustomerPhone'];
                                            }
                                            ?>
                                        </td>
                                        <ul>
                                            <li>Order Status:
                                                <?php echo $row['OrderStatus']; ?>
                                            </li>
                                            <!-- <li>Order Time: -->
                                            <?php
                                            // echo $row['OrderTime']; 
                                            ?>
                                            <!-- </li> -->
                                            <li>Total Bill:
                                                <?php echo $row['TotalAmount']; ?> Rs
                                            </li>
                                            <li>
                                                Table Number:
                                                <span class="fw-bold">
                                                    <?php echo $row['TableNumber']; ?>
                                                </span>
                                            </li>
                                        </ul>
                                        <td>
                                            <?php
                                            $jsonString = trim($row['json_cart']);
                                            // Decode the JSON string only once
                                            $cartdata = json_decode($jsonString, true);

                                            if (json_last_error() === JSON_ERROR_NONE && is_array($cartdata)) {
                                                if ($row['OrderStatus'] !== 'Rejected') {
                                                    $megacart[] = $cartdata;
                                                }
                                                // Output a table row for each item
                                                echo '<div class="table-responsive">
                                    <table border="1" class="table text-center">
                                    <thead>
                                    <tr>
                                <!--    <th>Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Amount</th> -->
                                    </tr>
                                    </thead>';
                                                if (!empty ($cartdata)) {
                                                    foreach ($cartdata as $itemId => $item) {
                                                        foreach ($item['Itemsize'] as $size => $details) {
                                                            $name = $item['ItemName'];
                                                            $price = $details['ItemPrice'];
                                                            $count = $details['Itemcount'];
                                                            $amount = $details['Itemcount'] * $details['ItemPrice'];                                                           
                                                            echo "<tbody>
                                                    <tr>
                                                        <td>$name</td>
                                                        <td>$price</td>
                                                        <td>X $count</td>
                                                        <td>$amount</td>
                                                    </tr>";
                                                        }
                                                        if ($row['OrderStatus'] !== 'Rejected') {
                                                            $grandtotal = $grandtotal + $row['TotalAmount'];
                                                        }
                                                    }
                                                    echo "<tr class='table fw-bold'>
                                                <td>Total</td>
                                                <td>Amount</td>
                                                <td>:</td>
                                                <td>" . $row['TotalAmount'] . "</td>
                                              </tr>
                                            </tbody>";
                                                    echo '</table></div>';
                                                } else {
                                                    echo "No items found in JSON<br>";
                                                }
                                            } else {
                                                echo "Error decoding JSON: " . json_last_error_msg();
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <?PHP if ($row['OrderStatus'] == 'Placed') { ?>
                                        <div class="row text-center pb-2 m-1" id="button-result">
                                            <div class="col p-1">
                                                <div class="w-100 fw-bold fs-5" disabled>Order Delivered to
                                                    Chef</div>
                                            </div>
                                        </div>
                                    <?PHP } elseif ($row['OrderStatus'] == 'InProgress') { ?>
                                        <div class="row text-center pb-2 m-1" id="button-result">
                                            <div class="col p-1">
                                                <button type="submit" class=" bg-warning fw-bold w-100 mb-4" disabled>Order Being
                                                    Prepared</button>

                                                <img src="assets/images/orderbeingprepeared.gif" class="pb-5"
                                                    style="object-fit: contain; width: 80%; height: 100%;" alt="Order Being Prepared">

                                            </div>
                                        </div>
                                    <?PHP } elseif ($row['OrderStatus'] == 'Rejected') { ?>
                                        <div class="row text-center pb-2 m-1" id="button-result">
                                            <div class="col p-1">
                                                <button type="submit" class="btn btn-lg btn-danger w-100" disabled>Order Rejected</button>
                                            </div>
                                        </div>

                                    <?PHP } elseif ($row['OrderStatus'] == 'Completed') { ?>
                                        <div class="row text-center pb-2 m-1" id="button-result">
                                            <div class="col p-1">
                                                <button type="submit" class="btn btn-lg btn-success w-100" disabled>Order Served</button>
                                                <!-- <h2 class="p-5"> You can Pay the Bill at Counter</h2> -->
                                            </div>
                                        </div>
                                    <?PHP } ?>
                                </div>
                                <?php
                                $cnt = $cnt + 1;
                            } else {
                                // echo '<script>window.location.href = "browserclose.html";</script>';
                                echo "<h1>Please Scan the Table QR Menu Again</h1>";
                            }
                        }
                    } else {
                        ?>
                        <tr>
                            <th style="text-align:center; color:red;" colspan="6">No Record Found</th>
                        </tr>
                        <?php
                    }

                } else {
                    header("Location: browserclose.php");
                }
                $ret->free_result();
                ?>
            </div>
            <!-- Grand Total Bill -->
            <div class="fixed-bottom mb-4">
                <div class="w-100 p-2 bg-transparent">
                    <div class="w-100 h2 mt-2 text-center bg-light">
                        <div class="row text-center fs-bold fs-5">
                            <div class="col">Grand Total :</div>
                            <div class="col">Rs
                                <?php echo $grandtotal; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <form action="paymententry.php" method="POST">
                                <input type="text" name="orderid" value="<?php $O_id = htmlspecialchars(json_encode($orderid));
                                echo $O_id; ?>" hidden>
                                <input type="text" name="grandtotal" value="<?php echo $grandtotal; ?>" hidden>
                                <input type="text" name="megacart" value="<?PHP $megacart = htmlspecialchars(json_encode($megacart));
                                echo $megacart; ?>" hidden>
                                <button type="submit" name="generatebill" id="btn-pay"
                                    class="btn btn-lg btn-success w-100 shadow shadow-4 fs-6">
                                    📜 Pay 📜
                                </button>
                            </form>
                        </div>
                        <div class="col">
                            <button class="btn btn-lg btn-danger w-100 shadow shadow-4 fs-6" onclick="menu()">🍕
                                Menu 🍝
                            </button>
                            <script>function menu() { window.location.href = 'menu.php'; }</script>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- countercheck Insert and Update code -->
        <?php
        if (isset ($_SESSION['CustomerName']) && isset ($_SESSION['CustomerName']) && isset ($_SESSION['table_number'])) {
            $customerName = htmlspecialchars(json_encode($cname));
            $customerNumber = htmlspecialchars(json_encode($cnumber));
            $tableNumber = $_SESSION['table_number'];
            $newMegaCart = $megacart;
            $newGrandTotal = $grandtotal;
            // Check if a row with the given CustomerName and TableNumber exists
            $selectQuery = "SELECT * FROM countercheck WHERE TableNumber = '$tableNumber' AND TableStatus = 'Active'";
            $result = mysqli_query($conn, $selectQuery);

            if ($result->num_rows > 0) {
                $updateQuery = "UPDATE countercheck SET MegaCart = '$newMegaCart', GrandTotal = '$newGrandTotal', CustomerName = '$customerName', CustomerPhone = '$customerNumber' WHERE TableNumber = '$tableNumber' AND TableStatus = 'Active'";
                if (mysqli_query($conn, $updateQuery)) {
                    // echo "Record updated successfully!";
                } else {
                    echo "Error updating record: " . mysqli_error($conn);
                }
            } else {
                // No row exists, perform an INSERT query
                $insertQuery = "INSERT INTO countercheck (CustomerName, CustomerPhone, TableNumber, MegaCart, GrandTotal, TableStatus) 
                            VALUES (?, ?, ?, ?, ?, 'Active')";
                $stmt = mysqli_prepare($conn, $insertQuery);
                if ($stmt) {
                    // Bind parameters
                    mysqli_stmt_bind_param($stmt, "sssss", $customerName, $customerNumber, $tableNumber, $newMegaCart, $newGrandTotal);
                    // Execute the statement
                    if (mysqli_stmt_execute($stmt)) {
                        // Record inserted successfully
                        // echo "Record inserted successfully!";
                    } else {
                        // Handle execution error
                        echo "Error inserting record: " . mysqli_stmt_error($stmt);
                    }
                    // Close the statement
                    mysqli_stmt_close($stmt);
                } else {
                    // Handle statement preparation error
                    echo "Error preparing statement: " . mysqli_error($conn);
                }
            }
            // Free the result set
            mysqli_free_result($result);
        }
        ?>

        <!-- Section Update Script -->
        <script>
            function checkForChanges() {
                // Perform an AJAX request
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        // Update the content on the page with the response from the PHP script
                        document.getElementById("main-section").innerHTML = this.responseText;
                    }
                };

                // Specify the PHP script that checks for changes
                xmlhttp.open("GET", "order_confirmation.php", true);
                xmlhttp.send();
            }

            // Call the function every 5 seconds (adjust the interval as needed)
            setInterval(checkForChanges, 5000);
        </script>
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <script>
            AOS.init();
        </script>
        <!-- Bootstrap JS-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    </body>

    </html>

<?php } elseif (isset ($_SESSION['indianDateTime']) && $TableStatus == 'Available') {
    echo '<img src="assets\images\emptylist.png" width="100%" ></img>';
} else {
    header('Location: generatebill.php');
}
echo "<script>console.log('" . $_SESSION['table_number'] . "')</script>";
echo "<script>console.log('" . $_SESSION['indianDateTime'] . "')</script>";
?>