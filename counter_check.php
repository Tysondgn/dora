<?php
session_start();
include 'config/dbconnection.php';
?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Counter Table Checking Live</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>

    <header class="fixed-top">
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #D2001A;">
            <div class="container-fluid">
                <a href="#" class="navbar-brand">
                    <!-- <img src="/examples/images/logo.svg" height="28" alt="Student's Corner"> -->
                    <div class="fw-bold fst-italic">Student's Corner Counter</div>
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


    <section id="counter-check" style="margin-top: 80px;">

        <?php         // Check if the session variables are set
        if (isset($_SESSION['user-counter'])) {
            ?>
            <?php
            // Perform the INNER JOIN
            $sql = "SELECT * FROM countercheck ORDER BY CounterID DESC";

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
                            <div>Counter ID :
                                <?php echo $row['CounterID']; ?>
                            </div>
                            <div>Name :
                                <?php
                                $decodedCustomerNames = json_decode(html_entity_decode($row['CustomerName']));

                                if (is_array($decodedCustomerNames)) {
                                    echo implode(', ', array_map('htmlspecialchars', $decodedCustomerNames));
                                } else {
                                    echo htmlspecialchars($row['CustomerName']);
                                }
                                ?>
                            </div>
                            <div>Table Number :
                                <?php echo $row['TableNumber']; ?>
                            </div>
                            <div>Payable Amount :
                                &#8377;
                                <?php echo $row['GrandTotal']; ?>
                            </div>
                            <div>Table Status :
                                <?php echo $row['TableStatus']; ?>
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
                                    }}
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php
                }

                // Close the table
                echo "</table>";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }

            // Close the connection
            $result->free_result();
            mysqli_close($conn);
            ?>
        <?php } else {
            // Handle the case where session variables are not set
            echo "Not Authorized.";
            echo "Session variables not set.";
        } ?>
    </section>

    <script>
        function checkForChanges() {
            // Perform an AJAX request
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                if (this.readyState == 4 && this.status == 200) {
                    // Update the content on the page with the response from the PHP script
                    document.getElementById("counter-check").innerHTML = this.responseText;
                }
            };

            // Specify the PHP script that checks for changes
            xmlhttp.open("GET", "counter_check.php", true);
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