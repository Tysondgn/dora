<?php
include "config/dbconnection.php";

$sql = "SELECT * FROM tablestatus";

$result = mysqli_query($conn, $sql);
if ($result->num_rows == 0) {
    echo "No data found.";
}
$Area = "";
while ($row = mysqli_fetch_assoc($result)) {
    if ($row['Area'] !== $Area) {
        $Area = $row['Area'];
        echo "</div><br>";
        echo '<h1 class="m-2 ms-4">' . $Area . '</h1>';

        echo "<div class='row rounded p-1 m-2' style='background-color: #f6ffe6;'>";
    }
    if($row['TableFlag'] == "Available"){
        tablelistAvailable($row['TableNumber'], $row['TableFlag']);
    }
    if($row['TableFlag'] == "Active"){
        tablelistActive($row['TableNumber'], $row['TableFlag']);
    }
}
function tablelistAvailable($TableNumber, $TableFlag)
{
    echo "<div class='col-3 rounded m-2 p-0' style='font-size: 15px; height: 100px; width: 100px; background-color: #e6fdcb; border-width: 1px;  border-style: solid; border-color: #bbbbbb ;'>
    <div class='w-100 rounded border-start border-5 border-success text-center d-flex justify-content-center align-items-center' style='height: 100%;'> 
        " . $TableNumber . "<br>" . $TableFlag . "
    </div>
</div>";
}
function tablelistActive($TableNumber, $TableFlag)
{
    echo "<div class='col-3 rounded m-2 p-0' style='font-size: 15px; height: 100px; width: 100px; background-color: #fdcbcb; border-width: 1px;  border-style: solid; border-color: #bbbbbb ;'>
    <div class='w-100 rounded border-start border-5 border-danger text-center d-flex justify-content-center align-items-center' style='height: 100%;'> 
        " . $TableNumber . "<br>" . $TableFlag . "
    </div>
</div>";
}
echo "</div>";


?>