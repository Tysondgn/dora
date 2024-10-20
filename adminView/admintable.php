<?PHP
if (isset($_SESSION['user-admin'])) {
    include "nav.php";
    ?>

    <div class="d-flex justify-content-between align-items-center ps-2 pe-2 pt-0 pb-0"
        >

    </div>



    <div class="container-fluid"
        style="background: #FFF; height: 100vh; margin: auto; margin-top: 70px;">


        <div class="row w-100 text-black" style="background-color: #FAFAFA;">
            <div width="300px" class="m-3">
                <label for="card-data">FROM DATE : </label>
                <input type="date" id="card-data">
                <label for="card-data" class="ps-3">TO DATE : </label>
                <input type="date" id="card-data">
            </div>
            <div class="col border border-3 m-4 border-black" style="width: 200px; height: 100px;">Total Orders<span
                    class="w-100 d-flex justify-content-center align-items-center fs-4">44</span></div>
            <div class="col border border-3 m-4 border-black" style="width: 200px; height: 100px;">Total Sale<span
                    class="w-100 d-flex justify-content-center align-items-center fs-4">69</span></div>
            <div class="col border border-3 m-4 border-black" style="width: 200px; height: 100px;">New Customers<span
                    class="w-100 d-flex justify-content-center align-items-center fs-4">64</span></div>
            <div class="col border border-3 m-4 border-black" style="width: 200px; height: 100px;">Repeated Customers<span
                    class="w-100 d-flex justify-content-center align-items-center fs-4">44</span></div>
        </div>

        <div class="row pt-4 m-2">
            <div class="col p-2">
                <a href="adminView/userprofile-table.php" class="text-dark text-decoration-none">
                    <div class="card text-center" style="width: 20rem; background: #FAFAFA">
                        <i class="bi bi-people fs-1"></i>
                        <div class="card-body">
                            <h5 class="card-title fs-6">View / Edit Profile Table</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col p-2">
                <a href="adminView/userprofile-insert.php" class="text-dark text-decoration-none">
                    <div class="card text-center" style="width: 20rem; background: #FAFAFA">
                        <i class="bi bi-person-fill-add fs-1"></i>
                        <div class="card-body">
                            <h5 class="card-title fs-6">Add Staff Profile</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col p-2">
                <a href="adminView/menuitems-table.php" class="text-dark text-decoration-none">
                    <div class="card text-center" style="width: 20rem;">
                        <i class="bi bi-phone fs-1"></i>
                        <div class="card-body">
                            <h5 class="card-title fs-6">View / Edit Menu Items</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col p-2">
                <a href="adminView/menuitems-insert.php" class="text-dark text-decoration-none">
                    <div class="card text-center" style="width: 20rem;">
                        <i class="bi bi-file-plus-fill fs-1"></i>
                        <div class="card-body">
                            <h5 class="card-title fs-6">Add New Menu Items</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col p-2">
                <a href="adminView/category_sequence.php" class="text-dark text-decoration-none">
                    <div class="card text-center" style="width: 20rem;">
                        <i class="bi bi-align-start fs-1"></i>
                        <div class="card-body">
                            <h5 class="card-title fs-6">Edit Menu Category Priority</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col p-2">
                <a href="menu_admin_demo.php" class="text-dark text-decoration-none">
                    <div class="card text-center" style="width: 20rem;">
                        <i class="bi bi-phone-fill fs-1"></i>
                        <div class="card-body">
                            <h5 class="card-title fs-6">View Menu As Customer</h5>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col p-2">
                <a href="adminView/customerorders-table.php" class="text-dark text-decoration-none">
                    <div class="card text-center" style="width: 20rem;">
                        <i class="bi bi-person-lines-fill fs-1"></i>
                        <div class="card-body">
                            <h5 class="card-title fs-6">View Customer's Orders</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </div>

    <?PHP
}
?>