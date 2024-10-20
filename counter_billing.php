<?php
include 'config/dbconnection.php';
session_start();
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['PaymentID']) {
    $PaymentID = $_POST['PaymentID'];
    echo "<script>console.log('" . $PaymentID . "')</script>";
    $PaymentStatus = $_POST['PaymentStatus'];
    echo "<script>console.log('" . $PaymentStatus . "')</script>";
    $PaymentMethod = $_POST['PaymentMethod'];
    echo "<script>console.log('" . $PaymentMethod . "')</script>";


    // Update the existing entry
    $updateQuery = $conn->prepare("UPDATE onlinepayments SET PaymentStatus = ?, PaymentMethod = ? WHERE PaymentID = ?");
    $updateQuery->bind_param("sss", $PaymentStatus, $PaymentMethod, $PaymentID);

    if ($updateQuery->execute()) {
        echo "Data updated successfully in the OnlinePayments table.";
        // header("Location: generatebill.php");
    } else {
        // Log the error instead of displaying it to the user
        error_log("Error updating data: " . $updateQuery->error);
        echo "An error occurred while updating data in the OnlinePayments table. Please try again.";
    }

    // Close the queries
    $updateQuery->close();
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Counter Billing Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <header class="fixed-top">
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #D2001A;">
            <div class="container-fluid">
                <a href="#" class="navbar-brand">
                    <!-- <img src="/examples/images/logo.svg" height="28" alt="Student's Corner"> -->
                    <div class="fw-bold fst-italic">Student's Corner Billing Counter</div>
                </a>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse" style="background-color: #D2001A;">
                    <div class="navbar-nav">
                        <a href="counter_billing.php" class="nav-item nav-link active">Billing Section 📄</a>
                        <a href="counter_check.php" class="nav-item nav-link active">Counter Check 💻</a>
                        <a href="counter_table_status.php" class="nav-item nav-link active">Tables Status 💹</a>
                        <form action="waitercall.php" method="POST">
                            <input type="text" name="waiter" Value="Counter" hidden>
                            <button type="submit" class="btn btn-danger fw-bold w-100 rounded-pill "
                                style="height: 40px;" data-bs-toggle="modal" data-bs-target="#callwaiter">Call Waiter 🔔
                            </button>
                        </form>
                        <!-- <a href="adminpanel.php" class="nav-item nav-link active">Previous Orders 💹</a> -->
                        <a href="logout.php" class="nav-item nav-link active">Logout 🚫</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <section id="counter-billing" style="margin-top: 90px;">
        <?php
        // Check if the session variables are set
        if (isset ($_SESSION['user-counter'])) {
            // Perform the INNER JOIN
            $sql = "SELECT customerprofile.CustomerID, customerprofile.CustomerName, customerprofile.CustomerPhone, onlinepayments.PaymentAmount, onlinepayments.TableNumber, onlinepayments.MegaCart, onlinepayments.PaymentID, onlinepayments.PaymentStatus, onlinepayments.PaymentMethod
            FROM customerprofile
            INNER JOIN onlinepayments ON customerprofile.CustomerID = onlinepayments.CustomerID
            ORDER BY onlinepayments.PaymentID DESC";

            $result = mysqli_query($conn, $sql);

            if ($result) {
                if ($result->num_rows == 0) {
                    echo "No data found.";
                }
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="w-90 border border-2 rounded-5 m-3 pb-2 shadow ">
                        <h3 class="text-center pt-2">Student's Corner</h3>
                        <div class="text-center fs-6" style=" border-bottom: 1px dashed grey;">Address: 24G4+MM9, <br> SH 2, Bodri,
                            Chhattisgarh 495220</div>

                        <div class="m-3">
                            <div>Payment ID :
                                P626
                                <?php echo $row['PaymentID']; ?>
                            </div>
                            <div>Name :
                                <?php echo $row['CustomerName']; ?>
                            </div>
                            <div>Table Number :
                                <?php echo $row['TableNumber']; ?>
                            </div>
                            <div>Payable Amount :
                                &#8377;
                                <?php echo $row['PaymentAmount']; ?>
                            </div>
                            <div>Payment Status :
                                <?php echo $row['PaymentStatus']; ?>
                            </div>
                            <!-- <div>Transaction Type : SALE</div> -->
                            <!-- <div>Aurthrization : Approved</div> -->
                        </div>
                        <div class="table-responsive">
                            <table class="table" border="1" style="background-color: #FFF6E9;">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Plate Size</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Parse the JSON string and generate table rows
                                    $jsonData = json_decode(html_entity_decode($row['MegaCart']), true);
                                    foreach ($jsonData as $item) {
                                        foreach ($item[key($item)]['Itemsize'] as $size => $details) {
                                            $itemName = $item[key($item)]['ItemName'];
                                            $itemPrice = $details['ItemPrice'];
                                            $itemCount = $details['Itemcount'];
                                            $total = $itemCount * $itemPrice;

                                            echo '<tr>';
                                            echo '<td>' . $itemName . '</td>';
                                            echo '<td>' . $size . '</td>';
                                            echo '<td>&#8377;' . $itemPrice . '</td>';
                                            echo '<td>X ' . $itemCount . '</td>';
                                            echo '<td>&#8377;' . $total . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center fw-bold fs-3">
                            <div class="row">
                                <?php if ($row['PaymentStatus'] == "Pending") { ?>
                                    <div class="col pe-0">
                                        <form action="" method="POST">
                                            <input type="text" name="PaymentID" value="<?php echo $row['PaymentID']; ?>" hidden />
                                            <input type="text" name="PaymentStatus" value="Completed" hidden />
                                            <input type="text" name="PaymentMethod" value="Online" hidden />
                                            <button type="submit" name="PaymentDone" class="btn btn-primary fs-5 w-75">Pay Online
                                                🌐</button>
                                        </form>
                                    </div>
                                    <div class="col ps-0">
                                        <form action="" method="POST">
                                            <input type="text" name="PaymentID" value="<?php echo $row['PaymentID']; ?>" hidden />
                                            <input type="text" name="PaymentStatus" value="Completed" hidden />
                                            <input type="text" name="PaymentMethod" value="Offline" hidden />
                                            <button type="submit" name="PaymentDone" class="btn btn-primary fs-5 w-75">Pay Offline
                                                💵</button>
                                        </form>
                                    </div>
                                <?php } elseif ($row['PaymentStatus'] == "Completed" && $row['PaymentMethod'] == 'Online') { ?>
                                    <div class="col">
                                        <button class="btn btn-lg btn-primary fs-5 w-75" disabled>Payment Done Online 🌐</button>
                                    </div>
                                <?php } elseif ($row['PaymentStatus'] == "Completed" && $row['PaymentMethod'] == 'Offline') { ?>
                                    <div class="col">
                                        <button class="btn btn-lg btn-success fs-5 w-75" disabled>Payment Done In Cash 💵</button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php
                }

                // Close the table
                echo "</table>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            // Handle the case where session variables are not set
            echo "Not Authorized.";
            echo "Session variables not set.";
        }

        // Close the connection
        mysqli_close($conn);
        ?>
    </section>

    <script>
        function checkForChanges() {
            // Perform an AJAX request
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // Update the content on the page with the response from the PHP script
                    document.getElementById("counter-billing").innerHTML = this.responseText;
                }
            };

            // Specify the PHP script that checks for changes
            xmlhttp.open("GET", "counter_billing.php", true);
            xmlhttp.send();
        }

        // Call the function every 5 seconds (adjust the interval as needed)
        setInterval(checkForChanges, 10000);
    </script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>