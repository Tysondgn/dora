<?PHP
session_start();
include "config/dbconnection.php";
?>

<?PHP
// Updating Order Status at Database
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['O_ID'])) {
//     $orderId = $_POST["O_ID"];
//     $newOrderStatus = $_POST["O_Status"];
//     $sql = "UPDATE customerorders SET OrderStatus = '$newOrderStatus' WHERE OrderID = '$orderId'";

//     // Execute the SQL statement
//     if (mysqli_query($conn, $sql)) {
//         // Update successful
//         echo "Order status updated successfully";
//     } else {
//         // Update failed
//         echo "Error updating order status: " . mysqli_error($conn);
//     }
// }
?>
<!-- if $_SESSION['user-kitchen'] from adminpanel-->
<?PHP if (isset($_SESSION['user-kitchen'])) { ?>

    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Kitchen Dashboard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <!-- Add the following code inside your <head> section -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    </head>

    <body>

        <header>
            <div class="fixed-top">
                <div class="d-flex justify-content-between align-items-center p-2"
                    style="height: 70px; background: #f2f2f2;">
                    <form action="logout.php" method="POST">
                        <button type="submit" class="btn btn-danger" id="logoutBtn">Logout 🚪</button>
                    </form>
                    <div class="fw-bold fs-5 text-center">Student's Corner Kitchen</div>
                    <form action="">
                        <button type="submit" class="btn btn-success" id="refreshBtn"> Refresh ♻️</button>
                    </form>
                </div>
                <div class="row text-center">
                    <div class="col p-2"> <a href="kitchen_old.php"
                            class="w-75 d-inline-flex focus-ring focus-ring-success py-1 px-2 text-decoration-none border rounded-2 shadow">Order
                            History</a> </div>
                    <div class="col p-2"> <a href="kitchen_rejected.php"
                            class="w-75 d-inline-flex focus-ring focus-ring-success py-1 px-2 text-decoration-none border rounded-2 shadow">Rejected
                            Orders</a> </div>
                </div>
            </div>

        </header>

        <section id="main-section-kitchen">
            <div class="row m-3" style="margin-top: 120px !important;">
                <?php
                // If no filter is provided, fetch today's data
                date_default_timezone_set("Asia/Kolkata");
                $today = date("Y-m-d");
                $sql = "SELECT * FROM customerorders WHERE DATE(OrderTime) = '$today' OR OrderStatus = 'Placed' OR OrderStatus = 'InProgress' ORDER BY OrderID DESC";
                $ret = mysqli_query($conn, $sql);
                $cnt = 1;
                $row = mysqli_num_rows($ret);
                if ($row > 0) {
                    while ($row = mysqli_fetch_array($ret)) {
                        ?>
                        <!--Fetch the Records -->
                        <div class="col border border-light-subtle rounded-4 shadow shadow-4 mb-4 m-4">
                            <tr>
                                <td>
                                    <?php echo $cnt; ?>
                                </td>
                                <td>
                                    <?php
                                    $customerid = $row['CustomerID'];
                                    $unamefetch = mysqli_query($conn, "SELECT * FROM customerprofile WHERE CustomerID ='$customerid'");
                                    $uname = mysqli_fetch_array($unamefetch);
                                    echo 'Order By :' . $uname['CustomerName'];
                                    ?>
                                </td>
                                <ul>
                                    <li>
                                        Order Status :
                                        <?php echo $row['OrderStatus']; ?>
                                    </li>
                                    <li>
                                        Order Time :
                                        <?php echo $row['OrderTime']; ?>
                                    </li>
                                    <li>
                                        Table Number :
                                        <span class="fw-bold">
                                            <?php echo $row['TableNumber']; ?>
                                        </span>
                                    </li>
                                    <li>
                                        Order Status :
                                        <span class="fw-bold">
                                            <?php echo $row['OrderStatus'];
                                            ?>
                                        </span>
                                    </li>
                                </ul>
                                <td>
                                    <?php
                                    $jsonString = trim($row['json_cart']);
                                    // echo "Raw JSON Data: $jsonString";
                        
                                    // Decode the JSON string only once
                                    $cartdata = json_decode($jsonString, true);

                                    // Check if the decoding was successful
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($cartdata)) {
                                        // Output a table row for each item
                                        echo '<table border="1" class="table table-striped text-center">
                                                     <tr>
                                                         <th>Name</th>
                                                         <th>Plate Size</th>
                                                       <!-- <th>Price</th> -->
                                                         <th>Quantity</th>
                                                     </tr>';
                                        // Check if $cartdata is not empty
                                        if (!empty($cartdata)) {
                                            foreach ($cartdata as $itemId => $item) {
                                                foreach ($item['Itemsize'] as $size => $details) {
                                                $name = $item['ItemName'];
                                                $price = $details['ItemPrice'];
                                                $count = $details['Itemcount'];

                                                echo "<tr>
                                                            <td>$name</td>
                                                            <td>$size</td>
                                                          <!--  <td>$price</td> -->
                                                            <td>$count</td>
                                                        </tr>";
                                            }}
                                            echo '</table>';
                                        } else {
                                            echo "No items found in JSON<br>";
                                        }
                                    } else {
                                        // Handle the case when decoding fails
                                        echo "Error decoding JSON: " . json_last_error_msg();
                                    }
                                    ?>
                                </td>
                            </tr>
                            <?PHP if ($row['OrderStatus'] == 'Placed') { ?>
                                <script>
                                    var audio = new Audio('assets/sound/simple-notification.mp3');
                                    audio.play();
                                </script>
                                <div class="row text-center pb-2 m-1" id="button-result">
                                    <div class="col-6 p-1">
                                        <form action="kitchen-main.php" method="POST">
                                            <input type="text" name="O_ID" value="<?PHP echo $row['OrderID']; ?>" hidden>
                                            <input type="text" name="O_Status" value="Rejected" hidden>
                                            <button type="submit" class="btn btn-lg btn-danger w-100">Reject</button>
                                        </form>
                                    </div>
                                    <div class="col-6 p-1">
                                        <form action="kitchen-main.php" method="POST">
                                            <input type="text" name="O_ID" value="<?PHP echo $row['OrderID']; ?>" hidden>
                                            <input type="text" name="O_Status" value="InProgress" hidden>
                                            <button type="submit" class="btn btn-lg btn-success w-100">Accept</button>
                                        </form>
                                    </div>
                                </div>
                            <?PHP } elseif ($row['OrderStatus'] == 'InProgress') { ?>
                                <div class="row text-center pb-2 m-1" id="button-result">
                                    <div class="col p-1">
                                        <form action="kitchen-main.php" method="POST">
                                            <input type="text" name="O_ID" value="<?PHP echo $row['OrderID']; ?>" hidden>
                                            <input type="text" name="O_Status" value="Rejected" hidden>
                                            <button type="submit" class="btn btn-lg btn-danger w-100">Reject</button>
                                        </form>
                                    </div>
                                    <div class="col p-1">
                                        <form action="kitchen-main.php" method="POST">
                                            <input type="text" name="O_ID" value="<?PHP echo $row['OrderID']; ?>" hidden>
                                            <input type="text" name="O_Status" value="Completed" hidden>
                                            <button type="submit" class="btn btn-lg btn-success w-100">Preparing</button>
                                        </form>
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
                                        <button type="submit" class="btn btn-lg btn-success w-100" disabled>Order Completed</button>
                                    </div>
                                </div>
                            <?PHP } ?>
                        </div>
                        <br>
                        <?php
                        $cnt = $cnt + 1;
                    }
                } else {
                    ?>
                    <tr>
                        <th style="text-align:center; color:red;" colspan="6">No Record Found</th>
                    </tr>
                    <?php
                }
                ?>
            </div>
        </section>

        <!-- Add the following script at the end of your HTML body -->
        <!-- <script>
            var debounceTimer;

            function checkForChanges() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    $.ajax({
                        url: "kitchen.php",
                        method: "GET",
                        success: function (response) {
                            // Update the content on the page with the response from the PHP script
                            $("#main-section-kitchen").html(response);
                        }
                    });
                }, 1000); // Adjust the delay (in milliseconds) as needed
            }

            // Call the function every 20 seconds
            setInterval(checkForChanges, 20000);

        </script> -->

        <script>
            // Function to reload the page
            function reloadPage() {
                location.reload(true); // Passing true forces a reload from the server and not from the browser cache
            }

            // Schedule the page reload every 10 seconds
            setInterval(reloadPage, 10000); // 10000 milliseconds = 10 seconds
        </script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    </body>

    </html>

<?PHP } else {
    echo "Not Authorized!";
} ?>