<?php
// Database Connection
include('../config/dbconnection.php');

if (isset($_POST['submit'])) {
    $itemId = $_GET['editid'];

    // Getting Post Values
    $itemName = $_POST['itemName'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $preparationTime = $_POST['preparationTime'];
    $cuisine = $_POST['cuisine'];
    $category = $_POST['category'];

    // Get the current image and video URLs
    $ret = mysqli_query($conn, "SELECT ImageURL, VideoURL FROM MenuItems WHERE ItemID='$itemId'");
    $row = mysqli_fetch_array($ret);
    $currentImageURL = $row['ImageURL'];
    $currentVideoURL = $row['VideoURL'];

    // Initialize variables
    $imageUploadPath = $currentImageURL;
    $videoUploadPath = $currentVideoURL;

    // File upload handling for image
    if (isset($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
        if (!empty($currentImageURL) && file_exists($currentImageURL)) {
            unlink($currentImageURL);
        }

        $imageFileName = uniqid() . '_' . $_FILES['imageFile']['name'];
        $imageFileTemp = $_FILES['imageFile']['tmp_name'];
        $imageUploadPath = '../assets/itemimage/' . $imageFileName;
        move_uploaded_file($imageFileTemp, $imageUploadPath);
    }

    // File upload handling for video
    if (isset($_FILES['videoFile']) && $_FILES['videoFile']['error'] === UPLOAD_ERR_OK) {
        if (!empty($currentVideoURL) && file_exists($currentVideoURL)) {
            unlink($currentVideoURL);
        }

        $videoFileName = uniqid() . '_' . $_FILES['videoFile']['name'];
        $videoFileTemp = $_FILES['videoFile']['tmp_name'];
        $videoUploadPath = '../assets/itemvideo/' . $videoFileName;
        move_uploaded_file($videoFileTemp, $videoUploadPath);
    }

    // Query for data updation
    $query = mysqli_query($conn, "UPDATE MenuItems SET ItemName='$itemName', Price=$price, Description='$description', PreparationTime=$preparationTime, ImageURL='$imageUploadPath', VideoURL='$videoUploadPath', cuisine='$cuisine', category='$category' WHERE ItemID='$itemId'");

    if ($query) {
        echo "<script>alert('You have successfully updated the data');</script>";
        echo "<script type='text/javascript'> document.location ='menuitems-table.php'; </script>";
    } else {
        echo "<script>alert('Something Went Wrong. Please try again');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <title>PHP Crud Operation!!</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        body {
            color: #fff;
            background: #63738a;
            font-family: 'Roboto', sans-serif;
        }

        .form-control {
            height: 40px;
            box-shadow: none;
            color: #969fa4;
        }

        .form-control:focus {
            border-color: #5cb85c;
        }

        .form-control,
        .btn {
            border-radius: 3px;
        }

        .signup-form {
            width: 450px;
            margin: 0 auto;
            padding: 30px 0;
            font-size: 15px;
        }

        .signup-form h2 {
            color: #636363;
            margin: 0 0 15px;
            position: relative;
            text-align: center;
        }

        .signup-form h2:before,
        .signup-form h2:after {
            content: "";
            height: 2px;
            width: 30%;
            background: #d4d4d4;
            position: absolute;
            top: 50%;
            z-index: 2;
        }

        .signup-form h2:before {
            left: 0;
        }

        .signup-form h2:after {
            right: 0;
        }

        .signup-form .hint-text {
            color: #999;
            margin-bottom: 30px;
            text-align: center;
        }

        .signup-form form {
            color: #999;
            border-radius: 3px;
            margin-bottom: 15px;
            background: #f2f3f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        .signup-form .form-group {
            margin-bottom: 20px;
        }

        .signup-form input[type="checkbox"] {
            margin-top: 3px;
        }

        .signup-form .btn {
            font-size: 16px;
            font-weight: bold;
            min-width: 140px;
            outline: none !important;
        }

        .signup-form .row div:first-child {
            padding-right: 10px;
        }

        .signup-form .row div:last-child {
            padding-left: 10px;
        }

        .signup-form a {
            color: #fff;
            text-decoration: underline;
        }

        .signup-form a:hover {
            text-decoration: none;
        }

        .signup-form form a {
            color: #5cb85c;
            text-decoration: none;
        }

        .signup-form form a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="signup-form">
        <form method="POST" enctype="multipart/form-data">
            <?php
            $itemId = $_GET['editid'];
            $ret = mysqli_query($conn, "SELECT * FROM MenuItems WHERE ItemID='$itemId'");
            while ($row = mysqli_fetch_array($ret)) {
                ?>
                <h2>Update </h2>
                <p class="hint-text">Update menu item info.</p>
                <div class="form-group">
                    <label for="itemName">Item Name</label>
                    <input type="text" class="form-control" name="itemName" value="<?php echo $row['ItemName']; ?>"
                        required="true">
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" class="form-control" name="price" value="<?php echo $row['Price']; ?>"
                        required="true">
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" name="description"
                        required="true"><?php echo $row['Description']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="preparationTime">Preparation Time</label>
                    <input type="number" class="form-control" name="preparationTime"
                        value="<?php echo $row['PreparationTime']; ?>" required="true">
                </div>
                <div class="form-group">
                    <label for="cuisine">Cuisine</label>
                    <input type="text" class="form-control" name="cuisine" value="<?php echo $row['cuisine']; ?>"
                        required="true">
                </div>
                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" class="form-control" name="category" value="<?php echo $row['category']; ?>"
                        required="true">
                </div>
                <div class="form-group">
                    <label for="imageFile">Update Image</label>
                    <input type="file" class="form-control" name="imageFile" accept="image/*">
                </div>
                <div class="form-group">
                    <label for="videoFile">Update Video</label>
                    <input type="file" class="form-control" name="videoFile" accept="video/*">
                </div>
                <?php
            } ?>
            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg btn-block" name="submit">Update</button>
            </div>
        </form>
    </div>
</body>

</html>