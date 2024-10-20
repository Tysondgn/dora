<?php
include "../config/dbconnection.php";

if (isset($_POST['category1'], $_POST['position1'], $_POST['category2'], $_POST['position2'])) {
    $category1 = $_POST['category1'];
    $position1 = $_POST['position1'];
    $category2 = $_POST['category2'];
    $position2 = $_POST['position2'];

    // Update the positions in the database
    $updateCategory1 = "UPDATE menuitems SET Position = '$position2' WHERE category = '$category1'";
    $updateCategory2 = "UPDATE menuitems SET Position = '$position1' WHERE category = '$category2'";
    
    if (mysqli_query($conn, $updateCategory1) && mysqli_query($conn, $updateCategory2)) {
        echo "Positions updated successfully";
        header("location: category_sequence.php");
    } else {
        echo "Error updating positions: " . mysqli_error($conn);
    }
} else {
    echo "Invalid request";
}

mysqli_close($conn);
?>
