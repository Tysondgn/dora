<?php
// Database Connection file
include ('../config/dbconnection.php');

if (isset ($_POST['submit'])) {
    // Form submitted

    if (isset ($_POST['category'])) {
        // Insert into MenuItems table
        $itemName = $_POST['itemName'];
        $description = $_POST['description'];
        $preparationTime = $_POST['preparationTime'];
        $cuisine = $_POST['cuisine'];
        $category = $_POST['category'];

        // File upload handling for video
        if (isset ($_FILES['videoFile']) && $_FILES['videoFile']['error'] === UPLOAD_ERR_OK) {
            $videoFileName = uniqid() . '_' . $_FILES['videoFile']['name'];
            $videoFileTemp = $_FILES['videoFile']['tmp_name'];
            $videoUploadPath = '../assets/itemvideo/' . $videoFileName;
            move_uploaded_file($videoFileTemp, $videoUploadPath);
            $escapedVideoUploadPath = mysqli_real_escape_string($conn, $videoUploadPath);
        } else {
            $escapedVideoUploadPath = NULL; // Set to NULL if video file is not uploaded
        }

        // File upload handling for image
        if (isset ($_FILES['imageFile']) && $_FILES['imageFile']['error'] === UPLOAD_ERR_OK) {
            $imageFileName = uniqid() . '_' . $_FILES['imageFile']['name'];
            $imageFileTemp = $_FILES['imageFile']['tmp_name'];
            $imageUploadPath = '../assets/itemimage/' . $imageFileName;
            move_uploaded_file($imageFileTemp, $imageUploadPath);
            $escapedImageUploadPath = mysqli_real_escape_string($conn, $imageUploadPath);
        } else {
            $escapedImageUploadPath = NULL; // Set to NULL if image file is not uploaded
        }

        // Insert into MenuItems table
        $Query = "INSERT INTO menuitems (ItemName, Description, PreparationTime, ImageURL, VideoURL, cuisine, category) VALUES ('$itemName', '$description', '$preparationTime', '$escapedImageUploadPath', '$escapedVideoUploadPath', '$cuisine', '$category')";
        $Result = mysqli_query($conn, $Query);
        if (!$Result) {
            echo "Error: " . mysqli_error($conn);
        } else {
            echo "<script>alert('Menu item added successfully!');</script>";
        }
    }

    if (isset ($_POST['plate_size'])) {
        // Insert into menuitemsprice table
        $itemId = $_POST['itemId'];
        $price = $_POST['price'];
        $plate_size = $_POST['plate_size'];

        $query = "INSERT INTO menuitemsprice (ItemID, Price, ItemSize) VALUES ('$itemId', '$price', '$plate_size')";
        $result = mysqli_query($conn, $query);
        if (!$result) {
            echo "Error: " . mysqli_error($conn);
        } else {
            echo "<script>alert('Menu item Price Updated successfully!');</script>";
        }
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
            width: 850px;
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
        <div class="row">
            <div class="col">
                <form method="POST" enctype="multipart/form-data">
                    <h2>Fill Data</h2>
                    <p class="hint-text">Fill below form.</p>
                    <div class="form-group">
                        <input type="text" class="form-control" name="itemName" placeholder="Item Name"
                            required="true"><span style="color: red;">*<span>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="description" placeholder="Description"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="number" class="form-control" name="preparationTime"
                            placeholder="Preparation Time In Minutes">
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
                        <input type="text" class="form-control" name="category" placeholder="Category"
                            required="true"><span style="color: red;">*<span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-lg btn-block" name="submit">Submit</button>
                    </div>
                </form>
                <div class="text-center">View Already Inserted Data!! <a href="menuitems-table.php">View</a></div>

            </div>
            <div class="col">
                <form method="POST" enctype="multipart/form-data">
                    <h2>Price</h2>
                    <p class="hint-text">Fill below form.</p>

                    <div class="form-group">
                        <select class="form-control" name="itemId" placeholder="Item Name" required="true">
                            <option value="Select">Select</option>
                            <?php
                            $Query = "SELECT ItemID,ItemName FROM menuitems";
                            $Result = mysqli_query($conn, $Query);
                            while ($row = mysqli_fetch_assoc($Result)) {
                                ?>
                                <option value="<?php echo $row['ItemID'] ?>">
                                    <?php echo $row['ItemName'] ?>
                                </option>
                                <?php
                            }
                            mysqli_free_result($Result)
                                ?>
                        </select><span style="color: red;">*<span>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="price" placeholder="Price (e.g., 1234.56)"
                            required="true" pattern="^\d{1,4}(\.\d{1,2})?$">
                        <span style="color: red;">*</span>
                    </div>
                    <div class="form-group">
                        <select class="form-control" name="plate_size" required="true">
                            <option value="Full">Full</option>
                            <option value="Half">Half</option>
                            <option value="Small">Small</option>
                            <option value="Medium">Medium</option>
                            <option value="Large">Large</option>
                        </select><span style="color: red;">*<span>
                    </div>
                    <!-- <div class="form-group">
                        <input type="text" class="form-control" name="category" placeholder="Category"
                            required="true"><span style="color: red;">*<span>
                    </div> -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-success btn-lg btn-block" name="submit">Submit</button>
                    </div>
                </form>
                <div class="text-center">View Already Inserted Data!! <a href="menuitems-table.php">View</a></div>
            </div>
        </div>
    </div>

</body>

</html>