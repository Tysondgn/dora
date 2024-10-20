<?php
include "../config/dbconnection.php";


// Assuming you have established a connection to your database

// Check for positions that are zero or negative
$query = "SELECT *  FROM menuitems WHERE Position <= 0";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
  // Get the last position
  $last_position_query = "SELECT MAX(Position) AS max_position FROM menuitems";
  $last_position_result = mysqli_query($conn, $last_position_query);
  $row = mysqli_fetch_assoc($last_position_result);
  $last_position = $row['max_position'];

  // Update positions
  while ($row = mysqli_fetch_assoc($result)) {
    $new_position = $last_position + 1;
    $update_query = "UPDATE menuitems SET Position = $new_position WHERE ItemID = " . $row['ItemID'];
    mysqli_query($conn, $update_query);
    $last_position = $new_position; // Update last position for next iteration
  }
  // echo "Positions updated successfully.";
} else {
  // echo "No positions with zero or negative values found.";
}

// Close the database connection
mysqli_free_result($result);


// Fetch categories from database with position
$category = mysqli_query($conn, "SELECT DISTINCT category, Position FROM menuitems ORDER BY Position;");
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Category Sequence Changer</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>


    <!-- <div class="d-lg-none bg-white"> -->
      <ul id="sortable-list" class=" w-100 ps-2 pe-2">
        <div class="d-flex d-inline">
          <a href="../adminpanel.php">
          <div class="rounded-circle shadow shadow-3 mt-5 ms-5" style="height: 50px; width: 50px; font-size: 30px; overflow: hidden;">&nbsp;<-&nbsp;</div></a>
          <h1 class="text-center w-100 p-5"> Category Priority Setting</h1>
        </div>
      <?php while ($menucategory = mysqli_fetch_array($category)) { ?>
        <li class="list-group-item rounded-pill m-1 shadow-3 border border-1 shadow shadow-3 mb-4" style=" padding: 10px; border-box: 3px;"
          data-category="<?php echo $menucategory['category']; ?>"
          data-position="<?php echo $menucategory['Position']; ?>">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <span class="category">
                <?php echo $menucategory['category']; ?>
              </span>
              <span class="position">(Position:
                <?php echo $menucategory['Position']; ?>)
              </span>
            </div>
            <div>
              <button class="btn btn-sm btn-primary move-up">Up</button>
              <button class="btn btn-sm btn-primary move-down">Down</button>
            </div>
          </div>
        </li>
      <?php } ?>
    </ul>
    <!-- </div> -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function () {
      $('.move-up').click(function () {
        var $currentItem = $(this).closest('li');
        var $prevItem = $currentItem.prev('li');
        if ($prevItem.length !== 0) {
          swapPositions($currentItem, $prevItem);
        }
      });

      $('.move-down').click(function () {
        var $currentItem = $(this).closest('li');
        var $nextItem = $currentItem.next('li');
        if ($nextItem.length !== 0) {
          swapPositions($currentItem, $nextItem);
        }
      });

      function swapPositions($item1, $item2) {
        var position1 = $item1.data('position');
        var position2 = $item2.data('position');
        var category1 = $item1.data('category');
        var category2 = $item2.data('category');

        $.ajax({
          url: 'update_sequence.php',
          method: 'POST',
          data: {
            category1: category1,
            position1: position1,
            category2: category2,
            position2: position2
          },
          success: function (response) {
            console.log(response); // Handle success response
            // Optionally, update UI or perform other actions upon success
            $('#sortable-list').html(response);
          },
          error: function (xhr, status, error) {
            console.error(error); // Handle error
          }
        });
      }
    });

  </script>
</body>

</html>