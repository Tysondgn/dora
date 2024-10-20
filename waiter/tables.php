<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> Tables Activity</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
  <header class="fixed-top bg-light">
    <!-- <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: #D2001A;">
      <div class="w-100 d-flex justify-content-between align-items-center">
        <div class="fw-bold fst-italic text-white m-2 h5">Student's Corner</div>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse" style="background-color: #D2001A;">
          <div class="navbar-nav">
            <a href="tables.php" class="nav-item nav-link active">Tables 📑</a>
            <a href="adminpanel.php" class="nav-item nav-link active">Home 🧑‍💼</a>
            <a href='logout.php' ;" class="nav-item nav-link active">Logout</a>
          </div>
        </div>
      </div>
    </nav> -->
    <div class="d-flex flex-row m-2 mt-1 float-end">
      <div class="d-flex flex-row">
        Available <div class="bg-success m-2" style=" width: 10px; height: 10px;"></div>
      </div>
      /
      <div class="d-flex flex-row ms-2">
        Active <div class="bg-danger m-2" style=" width: 10px; height: 10px;"></div>
      </div>
    </div>
  </header>

  <section class="m-2 mt-3">
    <?php include "tables_status.php"; ?>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>