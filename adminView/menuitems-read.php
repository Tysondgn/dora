<?php
include('../config/dbconnection.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Crud Operation Using PHP and MySQLi</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Roboto', sans-serif;
        }

        .table-responsive {
            margin: 30px 0;
        }

        .table-wrapper {
            min-width: 1000px;
            background: #fff;
            padding: 20px;
            box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        }

        .table-title {
            font-size: 15px;
            padding-bottom: 10px;
            margin: 0 0 10px;
            min-height: 45px;
        }

        .table-title h2 {
            margin: 5px 0 0;
            font-size: 24px;
        }

        .table-title select {
            border-color: #ddd;
            border-width: 0 0 1px 0;
            padding: 3px 10px 3px 5px;
            margin: 0 5px;
        }

        .table-title .show-entries {
            margin-top: 7px;
        }

        .search-box {
            position: relative;
            float: right;
        }

        .search-box .input-group {
            min-width: 200px;
            position: absolute;
            right: 0;
        }

        .search-box .input-group-addon,
        .search-box input {
            border-color: #ddd;
            border-radius: 0;
        }

        .search-box .input-group-addon {
            border: none;
            border: none;
            background: transparent;
            position: absolute;
            z-index: 9;
        }

        .search-box input {
            height: 34px;
            padding-left: 28px;
            box-shadow: none !important;
            border-width: 0 0 1px 0;
        }

        .search-box input:focus {
            border-color: #3FBAE4;
        }

        .search-box i {
            color: #a0a5b1;
            font-size: 19px;
            position: relative;
            top: 8px;
        }

        table.table tr th,
        table.table tr td {
            border-color: #e9e9e9;
        }

        table.table th i {
            font-size: 13px;
            margin: 0 5px;
            cursor: pointer;
        }

        table.table td:last-child {
            width: 130px;
        }

        table.table td a {
            color: #a0a5b1;
            display: inline-block;
            margin: 0 5px;
        }

        table.table td a.view {
            color: #03A9F4;
        }

        table.table td a.edit {
            color: #FFC107;
        }

        table.table td a.delete {
            color: #E34724;
        }

        table.table td i {
            font-size: 19px;
        }

        .pagination {
            float: right;
            margin: 0 0 5px;
        }

        .pagination li a {
            border: none;
            font-size: 13px;
            min-width: 30px;
            min-height: 30px;
            padding: 0 10px;
            color: #999;
            margin: 0 2px;
            line-height: 30px;
            border-radius: 30px !important;
            text-align: center;
        }

        .pagination li a:hover {
            color: #666;
        }

        .pagination li.active a {
            background: #03A9F4;
        }

        .pagination li.active a:hover {
            background: #0397d6;
        }

        .pagination li.disabled i {
            color: #ccc;
        }

        .pagination li i {
            font-size: 16px;
            padding-top: 6px
        }

        .hint-text {
            float: left;
            margin-top: 10px;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <div class="container-xl">
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="table-title">
                    <div class="row">
                        <div class="col-sm-5">
                            <h2>User <b>Details</b></h2>
                        </div>

                    </div>
                </div>
                <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered"
                    id="hidden-table-info">

                    <tbody>
                        <?php
                        $vid = $_GET['viewid'];
                        $ret = mysqli_query($conn, "SELECT * FROM menuitems WHERE ItemID = $vid");
                        while ($row = mysqli_fetch_array($ret)) {
                            ?>
                            <tr>
                                <th>Item Name</th>
                                <td>
                                    <?php echo $row['ItemName']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>
                                <?php 
                                    $itemid = $row['ItemID'];
                                    $Query = "SELECT Price,ItemSize FROM menuitemsprice WHERE ItemID = '$itemid'";
                                    $priceresult = mysqli_query($conn, $Query);
                                    while ($pricerow = mysqli_fetch_assoc($priceresult)) {
                                    echo $pricerow['ItemSize'] . " "; 
                                    echo "Price : " . $pricerow['Price'] . "<br><br>";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>
                                    <?php echo $row['Description']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Preparation Time</th>
                                <td>
                                    <?php echo $row['PreparationTime']; ?> Minutes
                                </td>
                            </tr>
                            <tr>
                                <th>Image URL</th>
                                <td>
                                <?php
                                    if (!empty($row['ImageURL'])) {
                                        echo '<img src="' . $row['ImageURL'] . '" alt="Item Image" style="width:200px;height:auto;">';
                                    } else {
                                        echo 'No Image';
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Video URL</th>
                                <td>
                                    <video width="320" height="240" controls>
                                        <source src="<?php echo $row['VideoURL']; ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </td>
                            </tr>
                            <tr>
                                <th>Cuisine</th>
                                <td>
                                    <?php echo $row['cuisine']; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td>
                                    <?php echo $row['category']; ?>
                                </td>
                            </tr>
                            <?php
                        } ?>
                    </tbody>

                </table>

            </div>
        </div>
    </div>
</body>

</html>