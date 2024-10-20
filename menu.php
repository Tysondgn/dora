<?PHP
include "config/dbconnection.php";
session_start();

// If user rescanned then Show previous data  or else show blank form for new entry
// also set name and number session or else set in order-upload.php
if (isset ($_GET['username']) && $_GET['usernumber']) {
  $username = $_GET["username"];
  $_SESSION["CustomerName"] = $username;
  $userNumber = $_GET["usernumber"];
  $_SESSION["CustomerPhone"] = $userNumber;

  // Prevent SQL injection
  $username = $conn->real_escape_string($username);
  $userNumber = $conn->real_escape_string($userNumber);

  // Fetch CustomerID from the customer profile
  $sql = "SELECT CustomerID FROM customerprofile WHERE CustomerName = '$username' AND CustomerPhone = '$userNumber'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    // Fetch the result
    $row = $result->fetch_assoc();
    $_SESSION["customerid"] = $row['CustomerID'];
    // Output the result
    // echo "CustomerID: $customerID";
  } else {
    echo "<Script>console.log('No matching records found.');</script>";
  }
}


if (isset ($_GET['SessionStartTime'])) {
  $_SESSION['indianDateTime'] = $_GET['SessionStartTime'];
  echo "<script>console.log('Old Session Time Re-Started');</script>";
} else {
  echo "<script>console.log('New Session Time Started');</script>";
}

?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <!-- Bootstrap icon -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
  <!-- AOS Animate on scroll -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <!-- Custom Css -->
  <link href="assets/css/style.css" rel="stylesheet">
  <!-- Font font-family -->
  <link href="https://db.onlinewebfonts.com/c/d7e8a95865396cddca89b00080d2cba6?family=SoDo+Sans+SemiBold" rel="stylesheet">

  <title>Welcome To Menu</title>
</head>


<body class="scroll-hide" style=" font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif ; font-size: 16px;">
<!-- <body class="scroll-hide" style=" font-family: 'SoDo Sans SemiBold'; font-size: 16px;"> -->
  <!-- Condition for checking if table session is set -->
  <?PHP if (isset ($_SESSION['table_number']) && isset ($_SESSION['indianDateTime'])) { ?>

    <!-- Navigation -->
    <header class="fixed-top d-lg-none" id="navbar">
      <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: #D2001A;">
        <div class="w-100 d-flex justify-content-between align-items-center">
          <!-- <img src="/examples/images/logo.svg" height="28" alt="Student's Corner"> -->
          <!-- <a href=""> -->
          <div class="fw-bold fst-italic text-white m-2 h5">Student's Corner</div>
          <!-- </a> -->
          <div class="border border-secondary rounded-pill d-flex flex-row m-1 w-50 align-items-center"
            style="background-color: #fff ; display: hidden;">
            <input type="text" id="search-menu" class="border-0 rounded-pill w-100" style="padding: 0 0 0 10px; "
              placeholder="Search..." onkeyup="showItem(this.value);">
            <!-- <i class="bi bi-x-lg p-1 pe-2" onclick=""></i> -->
            <i class="bi bi-search p-1 pe-2 me-1" style="display: block;" onclick=""></i>
          </div>
          <!-- <i class="bi bi-search text-white p-1 pe-2 me-3" style="display: block;" onclick=""></i> -->


          <!-- <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarCollapse" style="background-color: #D2001A;">
            <div class="navbar-nav">
              <a href="#" class="nav-item nav-link active">Call a Waiter 🤵</a>
              <a href="order_confirmation.php" class="nav-item nav-link active">View Orders 📑</a>
            </div>
            <div class="navbar-nav ms-auto">
              <a href="adminpanel.php" class="nav-item nav-link active">Admin Login 🧑‍💼</a>
            </div>
          </div> -->
        </div>
      </nav>

      <?php
      $query = "SELECT * FROM `menuitems`";
      $result = mysqli_query($conn, $query);
      $category = mysqli_query($conn, "SELECT DISTINCT category FROM menuitems ORDER BY Position;");
      ?>


      <!-- Category Div -->
      <div class="d-lg-none bg-white">

        <ul
          class="list-group list-group-horizontal position-relative overflow-auto w-100 rounded-5 rounded-top-0 scroll-hide p-2">
          <li class="list-group-item rounded-pill m-1 active-li-color"
            style="display: inline-block;  white-space: nowrap; padding: 5px 10px 5px 10px;" onclick="filterItems('All')">
            All</li>
          <?php while ($menucategory = mysqli_fetch_array($category)) { ?>
            <li class="list-group-item rounded-pill m-1"
              style="display: inline-block;  white-space: nowrap; border-left-width: 0.80px !important; padding: 5px 10px 5px 10px;"
              onclick="filterItems('<?php echo $menucategory['category']; ?>')">
              <?php echo $menucategory['category']; ?>
            </li>
          <?php } ?>
        </ul>

        <!-- VEG NON-VEG FILTER-SECTION -->
        <!-- <div class="filter-section">
          <div class="d-flex flex-row p-3 pt-1" style="font-size: 16px; !important">
            <div class="btn btn-success rounded-pill" style="width: 120px;" id="vegCheckbox"
              onclick="updateFoodType('VEG')" value="Pure Veg">Pure Veg</div>
            <div class="btn btn-danger rounded-pill ms-2" style="width: 120px;" id="nonVegCheckbox"
              onclick="updateFoodType('NON-Veg')" value="Non-Veg">Non-Veg</div>
          </div>
        </div> -->
      </div>

      <!-- Food type Veg/Non-Veg -->
      <script>
        function updateFoodType(foodType) {
          const url = new URL(window.location.href);
          const params = url.searchParams;

          // Remove any existing 'foodType' parameter
          params.delete('foodType');

          // Add the new foodType to the URL if it's not 'All'
          if (foodType !== 'All') {
            params.append('foodType', foodType);
          }

          // Reload the page with the updated URL
          window.location.href = url.href;
        }
      </script>
    </header>

    <!-- Menu Item Cards -->
    <section class="d-lg-none scroll-hide" id="menu-card-id" style="margin-top: 100px; margin-bottom: 130px;">

      <!-- combo Ad Section start -->
      <?php
      $combo = mysqli_query($conn, "Select * FROM menuitems WHERE category = 'Combo'");
      ?>
      <ul class="list-group list-group-horizontal position-relative overflow-auto w-100 rounded-5 scroll-hide p-2">
        <?php while ($comborow = mysqli_fetch_array($combo)) {
          ?>
          <li class="list-group-item border-0 p-0 m-2"
            style="display: inline-block;  white-space: nowrap; background: transparent;">
            <a class="text-decoration-none" style="color: #000000"
              onclick="displayOffcanvasContent(<?php echo $comborow['ItemID']; ?>,'full')" type="button"
              data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
              <?php
              if (!empty ($comborow['ImageURL'])) {
                $imageURL = str_replace('../', '', $comborow['ImageURL']);
                echo '<img class="mb-3" src="' . $imageURL . '" alt="image" style="width: 80vw; height: auto; object-fit: cover; border-bottom-left-radius: 50px 20px !important; border-bottom-right-radius: 50px 20px !important;  
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">';
              } else {
                echo 'No Image';
              }
              ?>
            </a>
          </li>
        <?php } ?>
      </ul>
      <!-- combo Ad Section end -->

      <div class="row ms-1 me-1" style="background-color: #FFFFF0;">
        <?php while ($row = mysqli_fetch_array($result)) { ?>
          <div class="col-6 p-1 pb-3 <?php echo $row['category']; ?> d-flex justify-content-center">
            <!-- Add the category class to each item div -->
            <!-- <div class="w-100 p-2"> -->
            <a class="text-decoration-none" style="color: #000000"
              onclick="displayOffcanvasContent(<?php echo $row['ItemID']; ?>,'full')" type="button"
              data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
              <div data-aos="fade-up" data-aos="fade-down" class="card rounded shadow pb-2"
                style="width: 45vw; height: auto; border-bottom-left-radius: 50px 20px !important; border-bottom-right-radius: 50px 20px !important;">
                <?php
                if (!empty ($row['ImageURL'])) {
                  $imageURL = str_replace('../', '', $row['ImageURL']);
                  echo '<div style="position: relative; width: 100%; height: 100px;">';
                  echo '<img class="rounded-top" src="' . $imageURL . '" alt="image" style="width: 100%; height: 100px; object-fit: cover;">';
                  // echo '<div style="position: absolute; bottom: 0; right: 0; background-color: rgba(0, 0, 0, 0.5); color: white; padding: 5px; border-radius: 50%;">';
                  // echo '<i class="bi bi-clock" width="10" height="10"></i>'. $row['PreparationTime'] ;
                  // echo '</div>';
                  echo '</div>';
                } else {
                  echo 'No Image';
                }
                ?>
                <div class="card-body rounded-circle p-2">
                  <div class="row">
                    <p class="fs-6 fw-bold mb-0"
                      style="display: inline-block; white-space: nowrap; overflow: hidden !important; text-overflow: ellipsis;">
                      <?php echo $row['ItemName']; ?>
                    </p>
                    <p class="text-muted mb-0" style="font-size : 0.8rem;">
                      <?php echo $row['category']; ?>
                    </p>
                    <p class="fs-6 fw-bold mb-0">
                      &#8377;
                      <?php
                      // Fetch Item Sizes from database
                      $typequery = "SELECT Price FROM menuitemsprice WHERE ItemID = '$row[ItemID]' AND (ItemSize = 'Full' OR ItemSize = 'Large')";
                      $typeresult = mysqli_query($conn, $typequery);

                      if (mysqli_num_rows($typeresult) > 0) {
                        while ($typerow = mysqli_fetch_assoc($typeresult)) {
                          echo $typerow['Price'];
                        }
                      }
                      mysqli_free_result($typeresult);
                      ?>
                    </p>

                  </div>
                </div>
              </div>
            </a>
            <!-- </div> -->
          </div>
        <?php } ?>
      </div>
    </section>

    <!-- Offcanvas Display and Add Dish to order table -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">MENU</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body" id="offcanvasContent">
        <!-- Content will be dynamically updated here using JavaScript -->
      </div>
    </div>

    <?php
    // Fetching menu items for offcanvas body
    $offcanvasQuery = "SELECT * FROM `menuitems`";
    $offcanvasResult = mysqli_query($conn, $offcanvasQuery);
    ?>
    <!-- This script link card to right offcanvas-body -->

    <script>
      function sizechange(itemSize, itemPrice, itemid) {
        displayOffcanvasContent(itemid, itemSize);
      }
    </script>

    <script>
      function displayOffcanvasContent(itemId, item_size) {
        var offcanvasContent = document.getElementById('offcanvasContent');
        offcanvasContent.innerHTML = ''; //clear existing Data
        <?php
        mysqli_data_seek($offcanvasResult, 0);
        while ($offcanvasRow = mysqli_fetch_array($offcanvasResult)) {
          ?>
          if (itemId == '<?php echo $offcanvasRow['ItemID']; ?>') {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
              if (this.readyState == 4 && this.status == 200) {
                document.getElementById("offcanvasContent").innerHTML =
                  this.responseText;
                handleResponse();
              }
            };
            xhttp.open("GET", "item_detail.php?itemid=" + itemId + "&item_size=" + item_size, true);
            xhttp.send();

          }
        <?php } ?>

        function handleResponse() {
          //button clicked change color
          var sizeindicator = document.getElementById('itemsize').innerText.trim(); // Getting the text content and trimming any extra spaces
          var btnid = "isb" + sizeindicator; // Constructing the button ID without an extra space
          var itemsizebutton = document.getElementById(btnid).innerText.trim(); // Getting the element by ID

          if (itemsizebutton == sizeindicator) {
            document.getElementById(btnid).style.backgroundColor = "green"; // Note: backgroundColor, not backgroundcolor
            document.getElementById(btnid).style.color = "white"; // Note: backgroundColor, not backgroundcolor
          } else {
            document.getElementById(btnid).style.backgroundColor = "white"; // Note: backgroundColor, not backgroundcolor
            document.getElementById(btnid).style.color = "black"; // Note: backgroundColor, not backgroundcolor
          }

          // localstorage get
          if (localStorage.getItem("giveinfo") !== null && localStorage.getItem("giveinfo") !== '{}') {
            cartdata = JSON.parse(localStorage.getItem('giveinfo'));
            var newid = itemId;
            var newitemsize = document.getElementById("itemsize").innerText.trim();
            // Check if the item exists in the cart data
            if (cartdata[newid]) {
              // Get the item object
              var item = cartdata[newid];
              // Iterate over each size
              for (var size in item.Itemsize) {
                // Check if ItemSize is defined for the specific item
                if (size === newitemsize) {
                  // Update the quantity display for this size
                  document.getElementById("quantityadd").style.display = "none";
                  document.getElementById("quantityedit").style.display = "block";
                  var count = item.Itemsize[size].Itemcount;
                  document.getElementById('count').innerHTML = count;

                }
              }
            }
          } else {
            document.getElementById("quantityadd").style.display = "block";
            document.getElementById("quantityedit").style.display = "none";
          }
        }
      }
    </script>

    <!-- Display only on wide Screen -->
    <div class="d-none d-lg-block d-flex justify-content-center w-100 text-center align-items-center"
      style=" height: 100%vh;">
      <h1 style="  display: flex;
  justify-content: center;
  align-items: center; height: 800px; width:100%;">
        Menu Opens on Phone screen
      </h1>
    </div>

    <!-- Order Canvas and Footer Section -->
    <footer class="d-lg-none">
      <!-- Cart -->
      <div class="w-100 p-2 pb-1 fixed-bottom bg-light" style="--bs-bg-opacity: .8;">
        <button class="btn btn-warning fw-bold w-100 rounded-pill rounded-bottom-0 fs-5" style="height: 40px;"
          onclick="showorder()" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
          aria-controls="offcanvasBottom"> Pending Order 🛒</button>
        <div>
          <div class="d-flex flex-row p-3 pt-2 pb-0">
            <div class="w-50">
              <form action="waitercall.php" method="POST" class="mb-1">
                <input type="text" name="waiter" Value="<?php echo $_SESSION['table_number']; ?>" hidden>
                <button type="submit" class="btn btn-danger fw-bold w-100 rounded-pill " style="height: 40px;"
                  data-bs-toggle="modal" data-bs-target="#callwaiter">Call Waiter 🔔
                </button>
              </form>
            </div>
            <div class="w-50  ps-1">
              <a href="order_confirmation.php" class="btn btn-danger fw-bold w-100 rounded-pill "
                style="height: 40px;">View Order 🍚</a>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal for waiter Call -->

      <!-- Order offcanvas -->
      <div class="offcanvas offcanvas-bottom w-100" tabindex="-1" id="offcanvasBottom"
        aria-labelledby="offcanvasBottomLabel" style="height: 600px;">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasBottomLabel">Your Order</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body" id="order-item-list" style="background:  #F9FEFF; display: block;">

          <hr>
          <div id="cart-container"></div>
          <hr>
          <p class="fs-4 fw-bold">Total Amount: <span id="total-amount">&#8377; 0.00 </span></p>
          <hr>

          <form action="order-upload.php" method="POST">
            <input type="text" id="upload-json" name="upload-json" hidden />
            <input type="text" id="upload-total-price" name="upload-total-price" hidden />
            <div class="mb-3">
              <label for="UserName" class="form-label">Enter Name</label>
              <div class="input-group">
                <input type="text" class="form-control" id="UserName" name="UserName" placeholder="Enter Name"
                  autocomplete="off" Required>
              </div>
              <div class="fs-6 text-danger">* Required</div>
            </div>
            <div class="mb-3">
              <label for="UserPhone" class="form-label">Enter Phone Number</label>
              <div class="input-group">
                <span class="input-group-text">+ 91</span>
                <input type="tel" class="form-control" id="UserPhone" name="UserPhone" placeholder="Phone Number"
                  pattern="[0-9]{10}" Required>
              </div>
              <div class="fs-6 text-danger">* Required</div>
            </div>
            <div class="mb-3">
              <label for="dishdes" class="form-label">Description For Chef</label>
              <textarea class="form-control" id="dishdes" name="dishdes" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold" name="Order">ORDER</button>
          </form>
          <br>
          <br>
          <button onclick="localStorage.removeItem('giveinfo'); cartdata = {}; showorder();"
            class="btn btn-danger btn-lg w-100">Erase</button>
        </div>
        <div id="nothing-to-order" style="padding-top: 100px; display: none;">
          <div style="font-size:5rem;width:100%;text-align:center;">🛒</div>
          <h1 class="text-center">
            Nothing To Order!
          </h1>
        </div>

      </div>
      <!-- /Cart -->

    </footer>

  <?PHP } else {
    header("Location: browserclose.php");
  }
  ?>

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script src="assets/js/scripts.js"></script>
  <!-- AOS Animate on scroll -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init();
  </script>
  <!-- Navbar OnScroll Designe -->
  <script>
    var prevScrollpos = window.pageYOffset;
    window.onscroll = function () {
      var currentScrollPos = window.pageYOffset;
      if (prevScrollpos > currentScrollPos) {
        document.getElementById("navbar").style.top = "0";
        document.getElementById("navbar").style.boxShadow = "0px 0px 0px";
      } else {
        document.getElementById("navbar").style.top = "-60px";
        document.getElementById("navbar").style.boxShadow = "0px 5px 16px 0px";
      }
      prevScrollpos = currentScrollPos;
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>

  <script>
    // Search ajax
    function showItem(str) {
      if (str.length !== 0) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
          if (this.readyState == 4 && this.status == 200) {
            document.getElementById("menu-card-id").innerHTML =
              this.responseText;
          }
        };
        xhttp.open("GET", "menu-card.php/?search=" + str, true);
        xhttp.send();
      } else {
        filterItems("All");
      }
    }
  </script>

</body>

</html>