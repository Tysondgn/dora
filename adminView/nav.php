<nav class="navbar navbar-expand-lg bg-body-tertiary" style="height: 70px; background-color: #D2001A;">
    <div class="container-fluid">
        <a class="navbar-brand text-decoration-none text-white fst-italic text-center" href="#">DORA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                </li>
                <li class="nav-item">
                </li>
            </ul>
            <form class="d-flex" role="search">
                <div class="btn" style="width:100px; height:40px;" id="logoutBtn">
                    Logout
                </div>
                <script>
                    document.getElementById('logoutBtn').addEventListener('click', function () {
                        // Perform an AJAX request to the logout script
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.onreadystatechange = function () {
                            if (this.readyState == 4 && this.status == 200) {
                                // Redirect to the login page or perform any other actions
                                window.location.href = "adminpanel.php";
                            }
                        };
                        // Specify the logout script
                        xmlhttp.open("GET", "logout.php", true);
                        xmlhttp.send();
                    });
                </script>
            </form>
        </div>
    </div>
</nav>