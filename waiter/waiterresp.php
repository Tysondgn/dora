<?php
session_start();
if (isset ($_SESSION['waiter'])) { //condition check for login
    include "config/dbconnection.php"; //Database Conn
    // Set flag as Atended in database
    // if (isset($_POST['req'])) {
    //     $resp = $_POST['req'];
    //     $callid = $_POST['callid'];
    //     $sql = "UPDATE waiter SET flag = '$resp' WHERE CallID = '$callid'";
    //     mysqli_query($conn, $sql);
    // }
    ?>

    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Customer Call</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>

    <body id="main-body">
        <!-- Navbar -->
        <header class="d-flex flex-row justify-content-between align-items-center w-100 fixed-top bg-light p-2">
            <button class="btn btn-success me-2" type="button" onclick="window.location.href = 'tables.php';">View
                Table</button>
            <div>
                <h3 class="text-center m-0 me-2">Customer Call</h3>
            </div>
            <button class="btn btn-danger" type="button" onclick="window.location.href = 'logout.php';">Logout</button>
        </header>

        <!-- Main Body Section -->
        <section>
            <div class="w-100 text-center mt-5 p-4">
                <?php
                // SQL query to select data from the 'waiter' table
                $sql = "SELECT * FROM `waiter` ORDER BY CallID DESC";

                // Execute the query
                $result = $conn->query($sql);

                // Check if the query was successful
                if ($result) {
                    // Check if there are rows in the result set
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            // echo "CallID: " . $row["CallID"] . "<br>";
                            echo "TableNumber: " . $row["TableNumber"] . "<br>";
                            echo "DateTime: " . $row["DateTime"] . "<br>";
                            echo "CustomerName: " . $row["CustomerName"] . "<br>";
                            if ($row["flag"] == 'req') {
                                echo "
                                    <form action='waiterresp-main.php' method='POST'>
                                    <input type='text' name='callid' value='" . $row['CallID'] . "' hidden>
                                    <input type='text' name='req' value='resp' hidden>
                                    <button class='btn btn-danger'> Waiting </button>
                                    <script>
                                    var audio = new Audio('assets/sound/simple-notification.mp3');
                                    audio.play();
                                    </script>
                                    </form>
                                    ";
                            } elseif ($row["flag"] == 'resp') {
                                echo "<div class='h2 text-success'> Atendend </div>";
                            }
                            echo "<br>----------------------------------------------------------------------<br>";
                        }
                    } else {
                        echo "No records found";
                    }
                } else {
                    echo "Error: " . $conn->error;
                }

                // Close the connection
                $conn->close();
                ?>
            </div>
        </section>

        <!-- Section Main Body Update every 10sec Script -->
        <!-- <script>
            function checkForChanges() {
                // Perform an AJAX request
                var xmlhttp = new XMLHttpRequest();

                xmlhttp.onreadystatechange = function () {
                    if (this.readyState == 4 && this.status == 200) {
                        // Update the content on the page with the response from the PHP script
                        document.getElementById("main-body").innerHTML = this.responseText;
                    }
                };

                // Specify the PHP script that checks for changes
                xmlhttp.open("GET", "waiterresp.php", true);
                xmlhttp.send();
            }

            // Call the function every 5 seconds (adjust the interval as needed)
            setInterval(checkForChanges, 5000);
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

    <?php
} else {
    echo "Unaurthorized User";
}
?>