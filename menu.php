<?PHP
include "config/dbconnection.php";
session_start();
// Get the table_number from the session
$tableNumber = $_SESSION['table_number'];

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['item_id']) && isset($_POST['quantity'])) {
    $itemId = $_POST['item_id'];
    $quantity = intval($_POST['quantity']);

    // Check if the item is already in the cart for the specific table
    $checkCartItemQuery = "SELECT * FROM CartItems WHERE TableNUM = '$tableNumber' AND ItemID = $itemId";
    $checkCartItemResult = mysqli_query($conn, $checkCartItemQuery);

    if (mysqli_num_rows($checkCartItemResult) > 0) {
      // Update the quantity if the item is already in the cart
      $updateCartItemQuery = "UPDATE CartItems SET Quantity = Quantity + $quantity WHERE TableNUM = '$tableNumber' AND ItemID = $itemId";
      mysqli_query($conn, $updateCartItemQuery);
    } else {
      // Insert a new item into the cart
      $insertCartItemQuery = "INSERT INTO CartItems (TableNUM, ItemID, Quantity) VALUES ('$tableNumber', $itemId, $quantity)";
      mysqli_query($conn, $insertCartItemQuery);
    }
  }
}

// Fetch cart items for the specific table
$fetchCartItemsQuery = "SELECT ci.CartItemID, m.ItemName, ci.Quantity
                        FROM CartItems ci
                        JOIN MenuItems m ON ci.ItemID = m.ItemID
                        WHERE ci.TableNUM = '$tableNumber'";
$cartItemsResult = mysqli_query($conn, $fetchCartItemsQuery);
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

  <title>Hello, world!</title>
</head>

<body>
  <header class="fixed-top">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #D2001A;">
      <div class="container-fluid">
        <a href="#" class="navbar-brand">
          <img src="/examples/images/logo.svg" height="28" alt="Student's Corner">
        </a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse" style="background-color: #D2001A;">
          <div class="navbar-nav">
            <a href="#" class="nav-item nav-link active">Home</a>
            <a href="#" class="nav-item nav-link active">Profile</a>
            <a href="#" class="nav-item nav-link active">Messages</a>
            <a href="#" class="nav-item nav-link active" tabindex="-1">Reports</a>
          </div>
          <div class="navbar-nav ms-auto">
            <a href="adminpanel.php" class="nav-item nav-link active">Login</a>
          </div>
        </div>
      </div>
    </nav>

    <?php
    // Get the selected category from the URL parameters
    $selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'All';

    // Modify your SQL query to filter by category
    $query = "SELECT * FROM `menuitems`";
    if ($selectedCategory !== 'All') {
      $query .= " WHERE category = '$selectedCategory'";
    }

    $result = mysqli_query($conn, $query);
    echo"<script> var numOfItems= ".mysqli_num_rows($result)." </script>";
    $category = mysqli_query($conn, "SELECT DISTINCT category FROM MenuItems;");
    ?>


    <div class="d-lg-none" style="background: #EfEfEf;">
      <ul class="list-group list-group-horizontal position-relative overflow-auto w-100">
        <li class="list-group-item" onclick="filterItems('All')">All</li>
        <?php while ($menucategory = mysqli_fetch_array($category)) { ?>
          <li class="list-group-item" onclick="filterItems('<?php echo $menucategory['category']; ?>')">
            <?php echo $menucategory['category']; ?>
          </li>
        <?php } ?>
      </ul>
    </div>
  </header>

  <section class="d-lg-none">

    <div class="row ms-1 me-1" style="margin-top: 130px; margin-bottom: 45px;">
      <?php while ($row = mysqli_fetch_array($result)) { ?>
        <div class="col-6 p-0 <?php echo $row['category']; ?>">
          <!-- Add the category class to each item div -->
          <div class="w-100 p-2">
            <a class="text-decoration-none" style="color: #000000"
              onclick="displayOffcanvasContent(<?php echo $row['ItemID']; ?>)" type="button" data-bs-toggle="offcanvas"
              data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
              <div class="card rounded-4" style="width: 100%; height: 200px;">
                <?php
                if (!empty($row['ImageURL'])) {
                  $imageURL = str_replace('../', '', $row['ImageURL']);
                  echo '<img src="' . $imageURL . '" alt="image" style="width: 100%; height: 150px; object-fit: cover;">';
                } else {
                  echo 'No Image';
                }
                ?>
                <div class="card-body p-2">
                  <div class="row">

                    <p class="fs-6 fw-bold">
                      <?php echo $row['ItemName']; ?>
                    </p>


                    <p class="fs-6 fw-bold">
                      <?php echo $row['Price']; ?> Rs
                    </p>

                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>
      <?php } ?>
    </div>

  </section>



  <?php
  // Fetching menu items for offcanvas body
  $offcanvasQuery = "SELECT * FROM `menuitems`";
  $offcanvasResult = mysqli_query($conn, $offcanvasQuery);
  ?>

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

  <!-- This script link category to card -->
  <script>
    function filterItems(category) {
      // Other logic to filter items
      window.location.href = '?category=' + category;
      var items = document.querySelectorAll('.col-6');
      items.forEach(function (item) {
        if (category === 'All' || item.classList.contains(category)) {
          item.style.display = 'block';
          item.addEventListener('click', function () {
            displayOffcanvasContent(item.getAttribute('data-item-id'));
          });
        } else {
          item.style.display = 'none';
        }
      });
    }
  </script>

  <!-- This script link card to right offcanvas-body -->
  <script>
    // Initialize a global variable to store cart data
    var cartData = [];
    // localStorage

    function displayOffcanvasContent(itemId) {
      var offcanvasContent = document.getElementById('offcanvasContent');
      offcanvasContent.innerHTML = '';

      <?php
      mysqli_data_seek($offcanvasResult, 0);
      while ($offcanvasRow = mysqli_fetch_array($offcanvasResult)) {
        ?>
        if (itemId == '<?php echo $offcanvasRow['ItemID']; ?>') {
          offcanvasContent.innerHTML += `
                      <div class="offcanvas-item" id="item_<?php echo $offcanvasRow['ItemID']; ?>">
                          <?php
                          if (!empty($offcanvasRow['ImageURL'])) {
                            $imageURL = str_replace('../', '', $offcanvasRow['ImageURL']);
                            echo '<img src="' . $imageURL . '" alt="image" style="width: 100%; height: 250px; object-fit: cover;">';
                          } else {
                            echo 'No Image';
                          }
                          ?>

                          <div class="row shadow shadow-3 rounded rounded-4 mt-3 mb-3">
                              <div class="col">
                                  <p class="fs-6 fw-bold">
                                      <?php echo $offcanvasRow['ItemName']; ?>
                                  </p>
                                  <p class="fs-6 fw-bold">
                                      <?php echo $offcanvasRow['Price']; ?> Rs
                                  </p>
                              </div>
                              <div class="col d-flex justify-content-center align-items-center fs-5">
                                  <div id="quantityButton">
                                  <button class="btn btn-success rounded-pill text-white btn-lg" onclick="toggleQuantity('<?php echo $offcanvasRow['ItemID']; ?>')">Add</button>
                                  </div>
                              </div>
                          </div>
                          <p class="fs-6 fw-bold">
                              <?php echo $offcanvasRow['Description']; ?>
                          </p>
                      </div>

                      <button class="btn btn-warning fw-bold fixed-bottom w-100 rounded-pill rounded-bottom-0" style="height: 40px;"
                          type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
                          aria-controls="offcanvasBottom">Order</button>
                  `;
        }
      <?php } ?>
    }

    // This script changes Add button to + - button
    function toggleQuantity(itemId) {
      console.log(itemId)
      var existingCartItem = cartData.find(item => item.itemId === itemId);
      var newvar = 0;

      if (!existingCartItem) {
        // Add new item to the cart
        console.log(existingCartItem)
      
      cartData.push({ itemId: String(itemId), quantity: 1 });


      } else {
        // Toggle quantity for existing item
        existingCartItem.quantity = (existingCartItem.quantity === 1) ? 0 : 1;
        const selectedCartItem = cartData.findIndex(item => item.itemId === itemId);

      let quantity = 1;

      if(selectedCartItem ){
        quantity = selectedCartItem.quantity;

      }




      }

      updateButton();
    }

    function updateButton(newvar = 0) {
      var quantityButton = document.getElementById('quantityButton');
      var selectedCartItem = cartData.find(item => item.quantity > 0);


      if (selectedCartItem) {
        quantityButton.innerHTML = `<div class='rounded-pill btn btn-success'>
  <button class="btn btn-success rounded-circle btn-lg" onclick="updateCart('increase', '${selectedCartItem.itemId}')">+</button>
  ${newvar}
  <button class="btn btn-success rounded-circle btn-lg" onclick="updateCart('decrease', '${selectedCartItem.itemId}')">-</button>
</div>`;

      } else {
        quantityButton.innerHTML = '<button class="btn btn-success rounded-pill text-white btn-lg" onclick="toggleQuantity()">Add</button>';
      }
    }

    
    // Function to update cart quantity on + and - button click
    function updateCart(action, itemId) {
      var selectedCartItem = cartData.find(item => item.itemId === itemId);

      if (selectedCartItem) {
        if (action === 'increase') {
          selectedCartItem.quantity++;
        } else if (action === 'decrease' && selectedCartItem.quantity > 0) {
          selectedCartItem.quantity--;
        }

        updateButton();
      }
    }

  </script>


  <!-- Display only on wide Screen -->
  <div class="d-none d-lg-block">
    <h1>
      Menu Opens on small screen
    </h1>
  </div>

  <footer>
    <!-- Cart -->

    <button class="btn btn-warning fw-bold fixed-bottom w-100 rounded-pill rounded-bottom-0" style="height: 40px;"
      type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
      aria-controls="offcanvasBottom">Order</button>

    <div class="offcanvas offcanvas-bottom w-100" tabindex="-1" id="offcanvasBottom"
      aria-labelledby="offcanvasBottomLabel" style="height: 600px;">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasBottomLabel">Your Order</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body" id="order-item-list">


        <!-- Form to add new item to the cart -->
      </div>
    </div>
    <!-- /Cart -->

  </footer>

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>
</body>

</html>