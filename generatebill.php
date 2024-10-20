<?php
include 'config/dbconnection.php';
session_start();
unset($_SESSION['indianDateTime']);
unset($_SESSION['table_number']);
?>
<script>localStorage.removeItem('SessionStartTime');</script>
<!-- NoBackPHP -->
<?php
// Prevent caching to ensure the browser fetches a new page
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bill</title>
    <!-- NoBackScript -->
    <script>
        // Disable the back button
        history.pushState(null, null, document.URL);
        window.addEventListener('popstate', function () {
            history.pushState(null, null, document.URL);
        });
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <!-- <h1 class="text-center">Automated Generated Invoice</h1> -->

    <section id="generate-bill">
        <?php

        // Check if the session variables are set
        if (isset($_SESSION['CustomerName']) && isset($_SESSION['CustomerPhone'])) {
            // Assign session variables to variables
            $customerName = $_SESSION["CustomerName"];
            $customerNumber = $_SESSION["CustomerPhone"];
            // $tableNumber = $_SESSION['table_number'];
        
            // Escape the input values to prevent SQL injection
            $customerName = mysqli_real_escape_string($conn, $customerName);
            $customerNumber = mysqli_real_escape_string($conn, $customerNumber);
            // $tableNumber = mysqli_real_escape_string($conn, $tableNumber);
        
            // Perform the INNER JOIN
            $sql = "SELECT customerprofile.CustomerID, customerprofile.CustomerName, customerprofile.CustomerPhone, onlinepayments.PaymentAmount, onlinepayments.MegaCart, onlinepayments.PaymentID, onlinepayments.PaymentStatus, onlinepayments.TableNumber, onlinepayments.TransactionDateTime
            FROM customerprofile
            INNER JOIN onlinepayments ON customerprofile.CustomerID = onlinepayments.CustomerID
            WHERE customerprofile.CustomerName = '$customerName' 
            AND customerprofile.CustomerPhone = '$customerNumber'
            AND onlinepayments.PaymentStatus = 'Pending'
            ORDER BY onlinepayments.PaymentID DESC
            ";

            $result = mysqli_query($conn, $sql);

            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="w-90 border border-2 rounded-5 m-5 pb-2 shadow ">
                        <h3 class="text-center pt-2">Student's Corner</h3>
                        <div class="text-center fs-6" style=" border-bottom: 1px dashed grey;">Address: 24G4+MM9, <br> SH 2, Bodri,
                            Chhattisgarh 495220</div>

                        <div class="m-3">
                            <div>Payment ID :
                                P626
                                <?php echo $row['PaymentID']; ?>
                            </div>
                            <div>Date :
                                <?php echo $row['TransactionDateTime']; ?>
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
                                    <th>Plate</th>
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
                        <div class="text-center fw-bold fs-3">Grand Total :
                            &#8377;
                            <?php echo $row['PaymentAmount']; ?><br>
                            Payment Status :
                            <?php echo $row['PaymentStatus']; ?>
                        </div>
                    </div>

                    <?php
                }

                // Close the table
                echo "</table>";
            } else {
                header("Location: browserclose.php");
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        } else {
            // Handle the case where session variables are not set
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
                    document.getElementById("generate-bill").innerHTML = this.responseText;
                }
            };

            // Specify the PHP script that checks for changes
            xmlhttp.open("GET", "generatebill.php", true);
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