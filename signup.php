<!DOCTYPE html>
<html lang="en">

<head>
  <?php session_start();
  include("conn_db.php");
  ?>
  <?php include('head.php'); ?>
  <title>Sign Up</title>
</head>

<body class="d-flex flex-column h-100">
  <div class="container p-5 mt-3" id="login-dashboard">
    <a class="nav nav-item text-decoration-none text-muted" href="index.php">
      <i class="bi bi-arrow-left-square me-2"></i>Go back</a>
    <!-- Signup Form -->
    <section>
      <div class="container py-3">
        <div class="row d-flex justify-content-center align-items-center">
          <div class="col">
            <div class="card card-registration my-4">
              <div class="row g-0">
                <div class="col-xl-6 d-none d-xl-block">
                  <img src="img/cafeteria.jpg" alt="Sample photo" class="img-fluid" style="border-top-left-radius: .35rem; border-bottom-left-radius: .35rem;" />
                </div>
                <div class="col-xl-6">
                  <div class="card-body p-md-5 text-black">
                    <form id="form-signup">
                      <h3 class="mb-5 text-uppercase">Sign Up</h3>
                      <div class="row">
                        <div class="col-md-6 mb-4">
                          <div class="form-outline">
                            <input type="text" id="fname" name="fname" class="form-control" required />
                            <label class="form-label" for="fname">First name</label>
                          </div>
                        </div>
                        <div class="col-md-6 mb-4">
                          <div class="form-outline">
                            <input type="text" id="lname" name="lname" class="form-control" required />
                            <label class="form-label" for="lname">Last name</label>
                          </div>
                        </div>
                      </div>

                      <div class="form-outline mb-4">
                        <input type="text" id="username" name="username" class="form-control" pattern="(.){5,15}" title="Username must be minimum 5 characters length and maximum 15 characters length." required />
                        <label class="form-label" for="username">Username</label>
                      </div>


                      <div class="form-outline mb-4">
                        <input type="email" id="email" name="email" class="form-control" placeholder="someone@mail.com" required />
                        <label class="form-label" for="email">Email</label>
                      </div>


                      <div class="form-outline mb-4">
                        <input type="password" id="password" name="password" class="form-control" pattern="(.){8,12}" title="Password must be minimum 8 characters length and maximum 12 characters length." required />
                        <label class="form-label" for="password">Password</label>
                      </div>

                      <div class="form-outline mb-4">
                        <input type="password" id="confirm_password" class="form-control" pattern="(.){8,12}" title="Password must be minimum 8 characters length and maximum 12 characters length." required />
                        <label class="form-label" for="confirm_password">Confirm Password</label>
                      </div>

                      <div class="d-flex justify-content-end pt-3">
                        <button type="submit" class="btn btn-outline-primary ms-2">Sign Up</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <?php include('footer.php'); ?>
  <?php include("toast-message.php"); ?>
  <script>
    $(document).ready(function() {
      $("#form-signup").on('submit', function(e) {
        e.preventDefault();
        if ($('#password').val() != $('#confirm_password').val()) {
          $('#password-fail-toast').toast('show')
        } else {
          e.preventDefault();
          var user_username = $('#username').val()
          var user_pwd = $('#password').val()
          var user_email = $('#email').val()
          var user_fname = $('#fname').val()
          var user_lname = $('#lname').val()
          $.ajax({
            url: "ajax-user-signup.php",
            type: "POST",
            data: {
              "user_username": user_username,
              "user_pwd": user_pwd,
              "user_email": user_email,
              "user_fname": user_fname,
              "user_lname": user_lname
            },
            dataType: 'json',
            success: function(response) {
              if (response.server_status == 1) {
                alertify.alert('Success Notification', 'Account registered successfully.', function() {
                  window.location.href = "index.php";
                });
              } else if (response.server_status == -1) {
                $('#user-exist-toast').toast('show')
              } else if (response.server_status == -2) {
                $('#email-invalid-toast').toast('show')
              } else if (response.server_status == -3) {
                $('#password-invalid-toast').toast('show')
              } else if (response.server_status == -4) {
                $('#username-invalid-toast').toast('show')
              } else {
                $('#signup-fail-toast').toast('show')
              }
            }
          });
        }
      });
    });
  </script>

</body>

</html>