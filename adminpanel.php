<?PHP
session_start();
include "config/dbconnection.php";
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Add the following link for Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link href="assets/css/login.css" rel="stylesheet">
    <title>Welcome, Admin</title>
</head>

<body>
    <?PHP
    if (isset($_SESSION['user-admin'])) {
        // echo "<script>window.location.href = 'adminView/userprofile-table.php'</script>";
        include "adminView/admintable.php";
    } elseif (isset($_SESSION['user-counter'])) {
        echo "<script>window.location.href = 'counter_billing.php'</script>";
    } elseif (isset($_SESSION['user-kitchen'])) {
        echo "<script>window.location.href = 'kitchen.php?refresh=true'</script>";
    } elseif (isset($_SESSION['waiter'])) {
        echo "<script>window.location.href = 'waiterresp.php'</script>";
    } else {
        include_once "adminView/login.php";
    }
    ?>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</body>

</html>