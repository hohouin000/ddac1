<!DOCTYPE html>
<html lang="en">

<head>
  <?php session_start();
  include("conn_db.php");
  if (isset($_SESSION["user_role"]) && !empty($_SESSION["user_role"]) && $_SESSION["user_role"] == "CUST") {
    header("location: index.php");
    exit(1);
  }
  ?>
  <?php include('head.php'); ?>
  <title>Login</title>
</head>

<body class="d-flex flex-column h-100">
  <div class="container p-5" id="login-dashboard" style="margin-top:5%;">
    <a class="nav nav-item text-decoration-none text-muted" href="index.php">
      <i class="bi bi-arrow-left-square me-2"></i>Go back</a>
    <!-- Login Form -->
    <div class="container">
      <div class="row justify-content-center mt-5">
        <div class="col-lg-4 col-md-6 col-sm-6">
          <div class="card shadow">
            <div class="card-title text-center border-bottom">
              <h2 class="p-3">Login</h2>
            </div>
            <div class="card-body">
              <form id="form-login">
                <div class="mb-4">
                  <label for="username" class="form-label">Username</label>
                  <input type="text" class="form-control" id="username" required />
                </div>
                <div class="mb-4">
                  <label for="password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" required />
                </div>
                <div class="d-grid">
                  <button type="submit" class="btn btn-outline-primary">Login</button>
                </div>
              </form>
              <div class="alert alert-dark mt-3" role="alert">
                Click <a href="cstaff/cstaff-login.php" class="alert-link">here</a> to login as staff.
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include('footer.php'); ?>

  <script>
    $(document).ready(function() {
      $("#form-login").on('submit', function(e) {
        e.preventDefault();
        var user_username = $('#username').val()
        var user_pwd = $('#password').val()
        $.ajax({
          url: "ajax-login-validation.php",
          type: "POST",
          data: {
            "user_username": user_username,
            "user_pwd": user_pwd,
          },
          dataType: 'json',
          success: function(response) {
            if (response.server_status == 1) {
              window.location.href = "index.php";
            } else {
              $('#login-fail-toast').toast('show')
            }
          }
        });
      });
    });
  </script>
  <?php include("toast-message.php"); ?>
</body>

</html>