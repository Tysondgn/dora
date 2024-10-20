<?php
include "config/dbconnection.php";
// Get the selected category from the URL parameters
if (isset ($_GET['category']) || isset ($_GET['category'])) {
  $selectedCategory = isset ($_GET['category']) ? $_GET['category'] : 'All';

  // Get the selected food type from the URL parameters
  $selectedFoodType = isset ($_GET['foodType']) ? $_GET['foodType'] : 'All';

  // Modify your SQL query to filter by category and food type
  $query = "SELECT * FROM `menuitems`";
  if ($selectedCategory !== 'All') {
    $query .= " WHERE category = '$selectedCategory'";
    if ($selectedFoodType !== 'All') {
      $query .= " AND FoodType = '$selectedFoodType'";
    }
  } else {
    // If category is 'All', include both Veg and Non-Veg items
    if ($selectedFoodType !== 'All') {
      $query .= " WHERE FoodType = '$selectedFoodType'";
    }
  }

  $result = mysqli_query($conn, $query);
  ?>

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

  <div class="row ms-1 me-1" style="margin-top: 0px; margin-bottom: 130px; background-color: #FFFFF0;">
    <?php while ($row = mysqli_fetch_array($result)) { ?>
      <div class="col-6 p-1 pb-3 <?php echo $row['category']; ?> d-flex justify-content-center">
        <!-- Add the category class to each item div -->
        <!-- <div class="w-100 p-2"> -->
        <a class="text-decoration-none" style="color: #000000"
          onclick="displayOffcanvasContent(<?php echo $row['ItemID']; ?>,'full')" type="button" data-bs-toggle="offcanvas"
          data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
          <div class="card rounded shadow pb-2"
            style="width: 45vw; height: auto; border-bottom-left-radius: 50px 20px !important; border-bottom-right-radius: 50px 20px !important;">
            <?php
            if (!empty ($row['ImageURL'])) {
              $imageURL = str_replace('../', '', $row['ImageURL']);
              echo '<img class="rounded-top" src="' . $imageURL . '" alt="image" style="width: 100%; height: 100px; object-fit: cover;">';
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
    <?php }
    mysqli_free_result($result);
    ?>
  </div>
<?php } ?>
<!-- ===========================================================Searchbar==================================================================== -->

<?php
if (isset ($_GET['search'])) {
  $search = $_GET['search'];

  // Perform SQL query to retrieve items
  $query = "SELECT * FROM menuitems WHERE ItemName LIKE '%$search%'";
  $result = mysqli_query($conn, $query);

  // Display search results in dropdown
  if (mysqli_num_rows($result) > 0) { ?>

    <div class="row ms-1 me-1" style="margin-top: 115px; margin-bottom: 130px; background-color: #FFFFF0;">
      <?php while ($row = mysqli_fetch_array($result)) { ?>
        <div class="col-6 p-1 pb-3 <?php echo $row['category']; ?> d-flex justify-content-center">
          <!-- Add the category class to each item div -->
          <!-- <div class="w-100 p-2"> -->
          <a class="text-decoration-none" style="color: #000000"
            onclick="displayOffcanvasContent(<?php echo $row['ItemID']; ?>,'full')" type="button" data-bs-toggle="offcanvas"
            data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
            <div class="card rounded shadow pb-2"
              style="width: 45vw; height: auto; border-bottom-left-radius: 50px 20px !important; border-bottom-right-radius: 50px 20px !important;">
              <?php
              if (!empty ($row['ImageURL'])) {
                $imageURL = str_replace('../', '', $row['ImageURL']);
                echo '<img class="rounded-top" src="' . $imageURL . '" alt="image" style="width: 100%; height: 100px; object-fit: cover;">';
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
      <?php }
      mysqli_free_result($result);
      ?>
    </div>


    <?php

  } else {
    echo "<div class='h1 d-flex justify-content-center align-items-center' style='height: 100%;' href='#'>No results found</div>";
  }
}
?>