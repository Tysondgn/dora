<?php
// Database Connection file
include('../config/dbconnection.php');

if (isset($_POST['submit'])) {
    // Getting the post values
    $itemName = $_POST['itemName'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $preparationTime = $_POST['preparationTime'];
    // $videoURL = $_POST['videoURL'];
    $cuisine = $_POST['cuisine'];
    $category = $_POST['category'];

    // File upload handling for video
    $videoFileName = uniqid() . '_' . $_FILES['videoFile']['name'];
    $videoFileTemp = $_FILES['videoFile']['tmp_name'];
    $videoUploadPath = '../assets/itemvideo/' . $videoFileName;
    move_uploaded_file($videoFileTemp, $videoUploadPath);

    // File upload handling for image
    $imageFileName = uniqid() . '_' . $_FILES['imageFile']['name'];
    $imageFileTemp = $_FILES['imageFile']['tmp_name'];
    $imageUploadPath = '../assets/itemimage/' . $imageFileName;
    move_uploaded_file($imageFileTemp, $imageUploadPath);

    // Insert into MenuItems table
    $insertMenuItemQuery = "INSERT INTO MenuItems (ItemName, Price, Description, PreparationTime, ImageURL, VideoURL, cuisine, category) VALUES ('$itemName', $price, '$description', $preparationTime, '$imageUploadPath', '$videoUploadPath', '$cuisine', '$category')";
    $menuItemResult = mysqli_query($conn, $insertMenuItemQuery);
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
            <h2>Fill Data</h2>
            <p class="hint-text">Fill below form.</p>
            <div class="form-group">
                <input type="text" class="form-control" name="itemName" placeholder="Item Name" required="true">
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="price" placeholder="Price" required="true">
            </div>
            <div class="form-group">
                <textarea class="form-control" name="description" placeholder="Description" required="true"></textarea>
            </div>
            <div class="form-group">
                <input type="number" class="form-control" name="preparationTime" placeholder="Preparation Time In Minutes" 
                    required="true">
            </div>
            <div class="form-group">
                <label for="imageFile">Image Upload</label>
                <!-- <input type="text" class="form-control" name="imageURL" placeholder="Image URL"> -->
                <input type="file" class="form-control" name="imageFile" accept="image/*">
            </div>
            <div class="form-group">
                <label for="videoFile">Video Upload</label>
                <!-- <input type="text" class="form-control" name="videoURL" placeholder="Video URL"> -->
                <input type="file" class="form-control" name="videoFile" accept="video/*">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="cuisine" placeholder="Cuisine">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="category" placeholder="Category">
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-success btn-lg btn-block" name="submit">Submit</button>
            </div>
        </form>
        <div class="text-center">View Already Inserted Data!! <a href="menuitems-table.php">View</a></div>
    </div>

</body>

</html>