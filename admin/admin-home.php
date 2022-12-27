<!DOCTYPE html>
<html lang="en">

<head>
  <?php session_start();
  include("../conn_db.php");
  include('../head.php');
  if ($_SESSION["user_role"] != "ADMN") {
    header("location:../restricted.php");
    exit(1);
  }
  ?>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home | Admin</title>
</head>

<body class="d-flex flex-column h-100">
  <?php include('admin-nav.php') ?>

  <div class="container p-5" id="admin-dashboard" style="margin-top:5%;">
    <h3 class="border-bottom pb-2"><svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
        <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
      </svg> Welcome Back, <?php echo $_SESSION["user_fname"] . ' ' . $_SESSION["user_lname"] ?></h3>

    <!-- ADMIN GRID DASHBOARD -->
    <div class="row row-cols-1 row-cols-lg-2 align-items-stretch g-4 py-3">

      <!-- GRID OF STORE -->
      <div class="col">
        <div class="card border-info p-2">
          <div class="card-body">
            <h4 class="card-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
              </svg>
              Customer
            </h4>
            <p class="card-text my-2">
              <span class="h6">
                <?php
                $query = "SELECT COUNT(*) AS userCount FROM user;";
                $result = $mysqli->query($query)->fetch_array();
                echo $result["userCount"];
                ?>
              </span>
              User(s) Registered in the System
            </p>
            <div class="text-end">
              <a href="admin-mng-user.php" class="btn btn-sm btn-outline-dark">Go to User Management</a>
            </div>
          </div>
        </div>
      </div>
      <!-- END GRID OF CUSTOMER -->

      <!-- GRID OF SHOP -->
      <div class="col">
        <div class="card border-success p-2">
          <div class="card-body">
            <h4 class="card-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-shop" viewBox="0 0 16 16">
                <path d="M2.97 1.35A1 1 0 0 1 3.73 1h8.54a1 1 0 0 1 .76.35l2.609 3.044A1.5 1.5 0 0 1 16 5.37v.255a2.375 2.375 0 0 1-4.25 1.458A2.371 2.371 0 0 1 9.875 8 2.37 2.37 0 0 1 8 7.083 2.37 2.37 0 0 1 6.125 8a2.37 2.37 0 0 1-1.875-.917A2.375 2.375 0 0 1 0 5.625V5.37a1.5 1.5 0 0 1 .361-.976l2.61-3.045zm1.78 4.275a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0 1.375 1.375 0 1 0 2.75 0V5.37a.5.5 0 0 0-.12-.325L12.27 2H3.73L1.12 5.045A.5.5 0 0 0 1 5.37v.255a1.375 1.375 0 0 0 2.75 0 .5.5 0 0 1 1 0zM1.5 8.5A.5.5 0 0 1 2 9v6h1v-5a1 1 0 0 1 1-1h3a1 1 0 0 1 1 1v5h6V9a.5.5 0 0 1 1 0v6h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1V9a.5.5 0 0 1 .5-.5zM4 15h3v-5H4v5zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1v-3zm3 0h-2v3h2v-3z" />
              </svg>
              Food Shop
            </h4>
            <p class="card-text my-2">
              <span class="h6">
                <?php
                $query = "SELECT COUNT(*) AS storeCount FROM store;";
                $result = $mysqli->query($query)->fetch_array();
                echo $result["storeCount"];
                ?>
              </span>
              Store(s) Registered in the System
            </p>
            <div class="text-end">
              <a href="admin-mng-store.php" class="btn btn-sm btn-outline-dark">Go to Store Management</a>
            </div>
          </div>
        </div>
      </div>
      <!-- END GRID OF SHOP -->
    </div>
    <!-- END ADMIN GRID DASHBOARD -->

  </div>
  <?php include('../footer.php'); ?>
</body>

</html>