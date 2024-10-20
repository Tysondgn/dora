<?PHP
include "config/dbconnection.php";
// Assuming you have a connection to your database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // if (isset($_POST["OrderID"])) {
    echo "<script> alert('" . $_POST["O_Status"] . "'); </script>";
    // Retrieve values from the form
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