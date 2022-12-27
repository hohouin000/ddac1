<!DOCTYPE html>
<html lang="en">

<head>
  <?php session_start();
  include("../conn_db.php");
  if (isset($_SESSION["user_role"]) && !empty($_SESSION["user_role"])) {
    header("location: cstaff-home.php");
    exit(1);
  }
  ?>
  <?php include('../head.php'); ?>
  <link href="../css/login.css" rel="stylesheet" />
  <title>Log in | Staff</title>
</head>

<body class="d-flex flex-column h-100">
  <div class="container form-e">
    <a class="nav nav-item text-decoration-none text-muted" href="../index.php">
      <i class="bi bi-arrow-left-square me-2"></i>Go back</a>
    <div class="container form-signin mt-auto">
      <form class="form-floating" id="form-cstaff-login">
        <h2 class="mt-5 mb-3 fw-normal text-bold">
          <i class="bi bi-people"></i> Staff Login
        </h2>
        <div class="form-floating mb-3">
          <input type="text" class="form-control" id="form-login-username" placeholder="admin_username" required />
          <label for="floatingInput">Username</label>
        </div>
        <div class="form-floating mb-2">
          <input type="password" class="form-control" id="form-login-pwd" placeholder="Password" required />
          <label for="floatingPassword">Password</label>
        </div>
        <button class="w-100 btn btn-outline-primary" type="submit">Log In</button>
      </form>
    </div>
  </div>
  <?php include('../footer.php'); ?>

  <script>
    $(document).ready(function() {
      $("#form-cstaff-login").on('submit', function(e) {
        e.preventDefault();
        var user_username = $('#form-login-username').val()
        var user_pwd = $('#form-login-pwd').val()
        $.ajax({
          url: "ajax-cstaff-login-validation.php",
          type: "POST",
          data: {
            "user_username": user_username,
            "user_pwd": user_pwd,
          },
          dataType: 'json',
          success: function(response) {
            if (response.server_status == 1) {
              window.location.href = "cstaff-home.php";
            } else {
              $('#login-fail-toast').toast('show')
            }
          }
        });
      });
    });
  </script>
  <?php include("../toast-message.php"); ?>
</body>

</html>