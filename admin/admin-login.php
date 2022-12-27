<!DOCTYPE html>
<html lang="en">

<head>
  <?php session_start();
  include("../conn_db.php");
  ?>
  <?php include('../head.php'); ?>
  <link href="../css/login.css" rel="stylesheet" />
  <title>Log in | Admin</title>
</head>

<body class="d-flex flex-column h-100">
  <div class="container form-signin mt-auto">
    <form class="form-floating" id="form-admin-login">
      <h2 class="mt-5 mb-3 fw-normal text-bold">
        <i class="bi bi-people"></i> Admin Login
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
  <?php include('../footer.php'); ?>

  <script>
    $(document).ready(function() {
      $("#form-admin-login").on('submit', function(e) {
        e.preventDefault();
        var user_username = $('#form-login-username').val()
        var user_pwd = $('#form-login-pwd').val()
        $.ajax({
          url: "ajax-admin-login-validation.php",
          type: "POST",
          data: {
            "user_username": user_username,
            "user_pwd": user_pwd,
          },
          dataType: 'json',
          success: function(response) {
            if (response.server_status == 1) {
              window.location.href = "admin-home.php";
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