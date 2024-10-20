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
        header("Location: kitchen.php");
    } else {
        // Update failed
        echo "Error updating order status: " . mysqli_error($conn);
    }
}
?>