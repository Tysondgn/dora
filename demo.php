<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<?php
include "config/dbconnection.php"; 
?>

<?php
// ... (previous code)

// Display Grand Total with Additional Information and Dishes for Each User Today
$sqlGrandTotal = "SELECT co.UserID, co.TableNumber,
                         SUM(co.TotalAmount) AS GrandTotal, 
                         GROUP_CONCAT(cd.ItemName) AS Dishes, 
                         GROUP_CONCAT(cd.ItemCount) AS Quantities, 
                         GROUP_CONCAT(cd.ItemPrice) AS Prices
                  FROM customerorders co
                  JOIN (
                      SELECT OrderID, 
                             JSON_UNQUOTE(JSON_EXTRACT(json_cart, '$**.ItemName')) AS ItemName,
                             JSON_UNQUOTE(JSON_EXTRACT(json_cart, '$**.Itemcount')) AS ItemCount,
                             JSON_UNQUOTE(JSON_EXTRACT(json_cart, '$**.ItemPrice')) AS ItemPrice
                      FROM customerorders
                  ) cd ON co.OrderID = cd.OrderID
                  WHERE DATE(co.OrderTime) = CURDATE() 
                  GROUP BY co.UserID, co.TableNumber
                  ORDER BY co.OrderID DESC";

$resultGrandTotal = mysqli_query($conn, $sqlGrandTotal);

if (mysqli_num_rows($resultGrandTotal) > 0) {
    echo '<div class="container mt-4">';
    echo '<h3 class="mb-3">Grand Total with Additional Information and Dishes for Each User Today:</h3>';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>User ID</th>';
    // echo '<th>User Name</th>';
    echo '<th>Table Number</th>';
    echo '<th>Dishes</th>';
    echo '<th>Quantities</th>';
    echo '<th>Prices</th>';
    echo '<th>Grand Total</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($rowGrandTotal = mysqli_fetch_assoc($resultGrandTotal)) {
        echo '<tr>';
        echo '<td>' . $rowGrandTotal['UserID'] . '</td>';
        // echo '<td>' . $rowGrandTotal['UserName'] . '</td>';
        echo '<td>' . $rowGrandTotal['TableNumber'] . '</td>';
        echo '<td>' . $rowGrandTotal['Dishes'] . '</td>';
        echo '<td>' . $rowGrandTotal['Quantities'] . '</td>';
        echo '<td>' . $rowGrandTotal['Prices'] . '</td>';
        echo '<td>' . $rowGrandTotal['GrandTotal'] . '</td>';
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
} else {
    echo '<p class="mt-4">No orders found for today.</p>';
}
?>


    
</body>
</html>