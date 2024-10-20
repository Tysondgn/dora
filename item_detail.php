<?php
include "config/dbconnection.php";
$itemId = $_GET['itemid'];
$itemSize = $_GET['item_size'];

// Fetching menu items for offcanvas body
$offcanvasQuery = "SELECT 
                        mi.ItemName AS ItemName, 
                        mi.ImageURL AS ImageURL, 
                        mi.Description AS Description, 
                        mi.category AS Category, 
                        mi.PreparationTime AS PreparationTime, 
                        mi.cuisine AS Cuisine, 
                        mi.ItemID AS ItemID, 
                        mip.Price AS Price, 
                        mip.ItemSize AS ItemSize 
                    FROM 
                        menuitems mi 
                    JOIN 
                        menuitemsprice mip ON mi.ItemID = mip.ItemID 
                    WHERE 
                        mi.ItemID='$itemId' AND 
                        mip.ItemSize ='$itemSize'";

$offcanvasResult = mysqli_query($conn, $offcanvasQuery);
mysqli_data_seek($offcanvasResult, 0);
while ($offcanvasRow = mysqli_fetch_array($offcanvasResult)) {
    ?>

    <div class="offcanvas-item mb-5" id="item_<?php echo $offcanvasRow['ItemID']; ?>">
        <?php
        if (!empty ($offcanvasRow['ImageURL'])) {
            $imageURL = str_replace('../', '', $offcanvasRow['ImageURL']);
            echo '<img src="' . $imageURL . '" alt="image" style="width: 100%; height: 250px; object-fit: cover;">';
        } else {
            echo 'No Image';
        }
        ?>
        <div class="row shadow shadow-3 rounded rounded-4 mt-3 mb-3">
            <p class="fs-6 fw-bold mb-0">
                <?php echo $offcanvasRow['ItemName']; ?>
            </p>
            <div class="col">
                <p class="fs-6 fw-bold">
                    <?php
                    // Fetch Item Sizes from database
                    $itemid = $offcanvasRow['ItemID'];
                    $typequery = "SELECT * FROM menuitemsprice WHERE ItemID = '$itemid'";
                    $typeresult = mysqli_query($conn, $typequery);
                    if (mysqli_num_rows($typeresult) > 0) {
                        while ($typerow = mysqli_fetch_assoc($typeresult)) { ?>
                        <div class="btn btn-outline-success rounded-pill ps-2 pe-2 pt-0 pb-0 fs-5"
                            id="isb<?php echo $typerow['ItemSize']; ?>" value="<?php echo $typerow['ItemSize']; ?>"
                            onclick="sizechange('<?php echo $typerow['ItemSize']; ?>', '<?php echo $typerow['Price']; ?>', '<?php echo $offcanvasRow['ItemID']; ?>')">
                            <?php echo $typerow['ItemSize']; ?>
                        </div>
                    <?php } ?>
                <?php } ?>
                </p>
                <p class="fs-6 fw-bold">
                    <span id="itemsize" value="<?php echo $offcanvasRow['ItemSize']; ?>">
                        <?php echo $offcanvasRow['ItemSize']; ?>
                    </span>
                    &#8377;
                    <?php echo $offcanvasRow['Price']; ?>
                </p>
            </div>
            <div class="col d-flex justify-content-center align-items-center fs-5">
                <div id="quantityButton">
                    <button id='quantityadd' class="btn btn-success rounded-pill text-white btn-lg add-btn"
                        onclick="quantityupdate('<?php echo $offcanvasRow['ItemID']; ?>','<?php echo $offcanvasRow['ItemName']; ?>','<?php echo $offcanvasRow['Price']; ?>','<?php echo $offcanvasRow['ItemSize']; ?>')">Add</button>
                    <div id='quantityedit' style="display: none;" class=" bg-success text-center rounded-pill text-white">
                        <div class="d-flex flex-row">
                            <div class="">
                                <div class="bg-light rounded-circle text-white" style="font-size: 50px;"
                                    onclick="quantityupdate('<?php echo $offcanvasRow['ItemID']; ?>','<?php echo $offcanvasRow['ItemName']; ?>','<?php echo $offcanvasRow['Price']; ?>','<?php echo $offcanvasRow['ItemSize']; ?>','decrease')">
                                    <i class="bi bi-dash-circle-fill text-success"></i>
                                </div>
                            </div>
                            <div class="me-2 ms-2 h-4 d-flex justify-content-center align-items-center">
                                <div id='count' class="text-white bg-success "></div>
                            </div>
                            <div class="">
                                <div class="bg-light rounded-circle text-white" style="font-size: 50px;"
                                    onclick="quantityupdate('<?php echo $offcanvasRow['ItemID']; ?>','<?php echo $offcanvasRow['ItemName']; ?>','<?php echo $offcanvasRow['Price']; ?>','<?php echo $offcanvasRow['ItemSize']; ?>','increase')">
                                    <i class="bi bi-plus-circle-fill text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p class="fs-6 fw-bold">
            <?php echo $offcanvasRow['Description']; ?>
        </p>
    </div>
    <button class="btn btn-warning fw-bold fs-5 fixed-bottom w-100 rounded-pill rounded-bottom-0 mb-1" style="height: 40px;"
        type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom"
        aria-controls="offcanvasBottom">Order</button>

<?php } ?>