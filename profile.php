<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php');

    if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
        header("location: login.php");
        exit(1);
    }

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }
    ?>
    <title>Profile</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('nav.php'); ?>
    <div class="container p-5" id="recommend-dashboard" style="margin-top:5%;">
        <h3 class="border-bottom pb-2"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-star" viewBox="0 0 16 16">
                <path d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.522-3.356c.33-.314.16-.888-.282-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288L8 2.223l1.847 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.565.565 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z" />
            </svg> Profile </h3>
        <?php
        // $query = "SELECT * FROM user WHERE user_id = {$_SESSION['user_id']};";
        // $arr = $mysqli->query($query)->fetch_array();
        $query = $mysqli->prepare("SELECT * FROM user WHERE user_id =?;");
        $query->bind_param('i', $_SESSION['user_id']);
        $query->execute();
        $arr = $query->get_result()->fetch_array();
        ?>
        <section class="mt-3">
            <div class="container py-5 h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col col-lg-6 mb-3 mb-lg-0">
                        <div class="card mb-3" style="border-radius: .5rem;">
                            <div class="row g-0">
                                <div class="col-md-4 gradient-custom text-center text-white" style="border-top-left-radius: .5rem; border-bottom-left-radius: .5rem;">
                                    <img src="img/profile.png" alt="Avatar" class="img-fluid my-5" style="width: 80px;" />
                                </div>
                                <div class="col-md-8 mt-3">
                                    <div class="card-body p-4" id="profile-card">
                                        <h6>Information <a class="bi bi-pencil-square" data-bs-toggle="modal" data-bs-target="#modal-edit" id="btn-edit"></a></h6>
                                        <hr class="mt-0 mb-4">
                                        <div class="row pt-1">
                                            <div class="col-6 mb-3">
                                                <h6>First Name</h6>
                                                <p class="text-muted"><?php echo $arr['user_fname'] ?></p>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <h6>Last Name</h6>
                                                <p class="text-muted"><?php echo $arr['user_lname'] ?></p>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <h6>Username</h6>
                                                <p class="text-muted"><?php echo $arr['user_username'] ?></p>
                                            </div>
                                            <div class="col-6 mb-3">
                                                <h6>Email</h6>
                                                <p class="text-muted"><?php echo $arr['user_email'] ?></p>
                                            </div>
                                        </div>
                                        <hr class="mt-0 mb-4">
                                        <div class="container" style="display:flex; justify-content:end;">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-change-pwd" id="btn-change-pwd">Change Password</button>
                                        </div>

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
    <?php include('toast-message.php'); ?>

    <!-- Edit-Modal -->
    <div class="modal fade" id="modal-edit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Profile</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="form-edit">
                        <div class="col-md-6">
                            <label for="fname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="form-edit-fname" name="user-fname" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="form-edit-lname" name="user-lname" required>
                        </div>
                        <div class="col-md-12">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="form-edit-username" name="user-username" pattern="(.){5,15}" title="Username must be minimum 5 characters length and maximum 15 characters length." required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="form-edit-email" placeholder="someone@mail.com" name="user-email" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-modal-close-edit">Close</button>
                        <button type="submit" class="btn btn-primary" id="form-btn-edit">Edit</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Edit-Modal -->

    <!-- Change Password-Modal -->
    <div class="modal fade" id="modal-change-pwd" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Change Password</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="form-change-pwd">
                        <div class="col-md-12">
                            <label for="old-pwd" class="form-label">Old Password</label>
                            <input type="password" class="form-control" id="form-old-pwd" name="old-pwd" required>
                        </div>
                        <div class="col-12">
                            <label for="confirm-pwd" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="form-new-pwd" name="confirm-pwd" pattern="(.){8,12}" title="Password must be minimum 8 characters length and maximum 12 characters length." required>
                        </div>
                        <div class="col-12">
                            <label for="new-confirm-pwd" class="form-label">New Confirm Password</label>
                            <input type="password" class="form-control" id="form-new-confirm-pwd" name="new-confirm-pwd" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-modal-close-change-pwd">Close</button>
                        <button type="submit" class="btn btn-primary" id="form-btn-change-pwd">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Change Password-Modal -->


    <script>
        $(document).ready(function() {
            // Edit AJAX Call Starts here
            user_id = "<?= $user_id; ?>";
            $.ajax({
                url: "ajax-get-selected-user.php",
                type: "POST",
                data: {
                    "user_id": user_id
                },
                dataType: 'json',
                success: function(data) {
                    if (data.server_status == 1) {
                        $('#form-edit-username').val(data.user_username);
                        $('#form-edit-fname').val(data.user_fname);
                        $('#form-edit-lname').val(data.user_lname);
                        $('#form-edit-email').val(data.user_email);
                        $('#form-edit-pwd').val(data.user_pwd);
                    } else {
                        $('#edit-fail-toast').toast('show')
                    }
                }
            });

            $("#form-edit").on('submit', function(e) {
                e.preventDefault();
                var user_username = $('#form-edit-username').val()
                var user_fname = $('#form-edit-fname').val()
                var user_lname = $('#form-edit-lname').val()
                var user_email = $('#form-edit-email').val()
                $.ajax({
                    url: "ajax-update-user.php",
                    type: "POST",
                    data: {
                        "user_username": user_username,
                        "user_fname": user_fname,
                        "user_lname": user_lname,
                        "user_email": user_email,
                        "user_id": user_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.server_status == 1) {
                            $('#btn-modal-close-edit').click();
                            $("#profile-card").load(" #profile-card");
                            $('#edit-success-toast').toast('show')
                        } else if (response.server_status == -2) {
                            $('#email-invalid-toast').toast('show')
                        } else if (response.server_status == -4) {
                            $('#username-invalid-toast').toast('show')
                        } else {
                            $('#btn-modal-close-edit').click();
                            $('#edit-fail-toast').toast('show')
                        }
                    }
                });
            });


            $("#form-change-pwd").on('submit', function(e) {
                //Check Password
                e.preventDefault();
                if ($('#form-new-pwd').val() != $('#form-new-confirm-pwd').val()) {
                    $('#password-fail-toast').toast('show')
                } else {
                    e.preventDefault();
                    var user_pwd = $('#form-old-pwd').val();
                    var user_new_pwd = $('#form-new-pwd').val();
                    $.ajax({
                        url: "ajax-check-user-password.php",
                        type: "POST",
                        data: {
                            "user_id": user_id,
                            "user_pwd": user_pwd,
                            "user_new_pwd": user_new_pwd
                        },
                        dataType: 'json',
                        success: function(response) {
                            if (response.server_status == 1) {
                                $('#form-change-pwd')[0].reset();
                                $('#btn-modal-close-change-pwd').click();
                                $('#edit-success-toast').toast('show')
                            } else if (response.server_status == -3) {
                                $('#password-invalid-toast').toast('show')
                            } else if (response.server_status == -5) {
                                $('#password-notfound-toast').toast('show')
                            } else {
                                $('#form-change-pwd')[0].reset();
                                $('#btn-modal-close-change-pwd').click();
                                $('#edit-fail-toast').toast('show')
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>