<?PHP
session_start();
include "config/dbconnection.php";
?>

<?PHP
// Updating Order Status at Database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['O_ID'])) {
    $orderId = $_POST["O_ID"];
    $newOrderStatus = $_POST["O_Status"];
    $sql = "UPDATE customerorders SET OrderStatus = '$newOrderStatus' WHERE OrderID = '$orderId'";

    // Execute the SQL statement
    if (mysqli_query($conn, $sql)) {
        // Update successful
        echo "Order status updated successfully";
    } else {
        // Update failed
        echo "Error updating order status: " . mysqli_error($conn);
    }
}
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


        <section id="main-section-kitchen">
            <header>
                <div class="fixed-top">
                    <div class="d-flex justify-content-between align-items-center p-2"
                        style="height: 70px; background: #f2f2f2;">
                        <form action="logout.php" method="POST">
                            <button type="submit" class="btn btn-danger" id="logoutBtn">Logout 🚪</button>
                        </form>
                        <div class="fw-bold fs-5 text-center">Student's Corner Canceled Orders</div>
                        <form action="">
                            <button type="submit" class="btn btn-success" id="refreshBtn"> Refresh ♻️</button>
                        </form>
                    </div>
                    <!-- Add this input field to your HTML form -->
                    <form method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="input-group mb-3">
                            <input type="date" class="form-control" id="filterDate" name="filterDate">
                            <button type="submit" class="btn btn-outline-primary">Apply Filter</button>
                        </div>
                    </form>
                </div>

            </header>
            <div class="row m-3" style="margin-top: 120px !important;">
                <?php
                // If no filter is provided, fetch today's data
                // Check if a date filter is provided
                if (isset($_GET['filterDate']) && !empty($_GET['filterDate'])) {
                    $filterDate = $_GET['filterDate'];
                    $sql = "SELECT * FROM customerorders WHERE DATE(OrderTime) = '$filterDate' AND OrderStatus = 'Rejected' ORDER BY OrderID DESC";
                } else {
                    date_default_timezone_set("Asia/Kolkata");
                    $today = date("Y-m-d");
                    $sql = "SELECT * FROM customerorders WHERE DATE(OrderTime) = '$today' AND OrderStatus = 'Rejected' ORDER BY OrderID DESC";
                }
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
                                                       <!-- <th>Price</th> -->
                                                         <th>Quantity</th>
                                                     </tr>';
                                        // Check if $cartdata is not empty
                                        if (!empty($cartdata)) {
                                            foreach ($cartdata as $itemId => $item) {
                                                $name = $item['ItemName'];
                                                $price = $item['ItemPrice'];
                                                $count = $item['Itemcount'];

                                                echo "<tr>
                                                            <td>$name</td>
                                                          <!--  <td>$price</td> -->
                                                            <td>$count</td>
                                                        </tr>";
                                            }
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
                                <div class="row text-center pb-2 m-1" id="button-result">
                                    <div class="col-6 p-1">
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                            <input type="text" name="O_ID" value="<?PHP echo $row['OrderID']; ?>" hidden>
                                            <input type="text" name="O_Status" value="Rejected" hidden>
                                            <button type="submit" class="btn btn-lg btn-danger w-100">Reject</button>
                                        </form>
                                    </div>
                                    <div class="col-6 p-1">
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                            <input type="text" name="O_ID" value="<?PHP echo $row['OrderID']; ?>" hidden>
                                            <input type="text" name="O_Status" value="InProgress" hidden>
                                            <button type="submit" class="btn btn-lg btn-success w-100">Accept</button>
                                        </form>
                                    </div>
                                </div>
                            <?PHP } elseif ($row['OrderStatus'] == 'InProgress') { ?>
                                <div class="row text-center pb-2 m-1" id="button-result">
                                    <div class="col p-1">
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                            <input type="text" name="O_ID" value="<?PHP echo $row['OrderID']; ?>" hidden>
                                            <input type="text" name="O_Status" value="Rejected" hidden>
                                            <button type="submit" class="btn btn-lg btn-danger w-100">Reject</button>
                                        </form>
                                    </div>
                                    <div class="col p-1">
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
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
                                <div class="col p-1">
                                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                        <input type="text" name="O_ID" value="<?PHP echo $row['OrderID']; ?>" hidden>
                                        <input type="text" name="O_Status" value="Completed" hidden>
                                        <button type="submit" class="btn btn-lg btn-success w-100">Set To Preparing</button>
                                    </form>
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

        <footer class="fixed-bottom">
            <div class="w-100 p-2 text-center">
                <a href="kitchen.php" class="bg-light w-100 btn btn-outline-success rounded-pill fw-bold fs-5">Back to Order Section</a>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    </body>

    </html>

<?PHP } else {
    echo "Not Authorized!";
} ?>