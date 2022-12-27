<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("../conn_db.php");
    include('../head.php');
    if ($_SESSION["user_role"] != "CSTAFF") {
        header("location:../restricted.php");
        exit(1);
    }
    ?>
    <title>User Management | Admin</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('../cstaff/cstaff-nav.php') ?>
    <div class="container p-2 pb-0 mt-5 pt-3" id="admin-dashboard">
        <h2 class="pt-3 display-6">User Management</h2>
        <div class="row g-2 justify-content-md-end">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#add-new-user">Add New User</button>
            </div>
        </div>
    </div>
    </div>

    <div class="container pt-2">
        <div class="table-responsive">
            <table id="user-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Role</th>  
                        <th>---</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <?php include('../footer.php'); ?>
    <script>
        // Hide Div based on Value for Add
        $(document).ready(function() {
            var store_id;
            var user_id;
            

            // Add User AJAX Call Starts here
            $("#form-add-user").on('submit', function(e) {
                e.preventDefault();
                var user_username = $('#modal-add-user-username').val()
                var user_fname = $('#modal-add-user-fname').val()
                var user_lname = $('#modal-add-user-lname').val()
                var user_email = $('#modal-add-user-email').val()
                var user_role = $('#form-add-role').val()
                var user_pwd = $('#modal-add-user-pwd').val()
                $.ajax({
                    url: "ajax-admin-add-user.php",
                    type: "POST",
                    data: {
                        "store_id": store_id,
                        "user_username": user_username,
                        "user_fname": user_fname,
                        "user_lname": user_lname,
                        "user_email": user_email,
                        "user_role": user_role,
                        "user_pwd": user_pwd
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.server_status == 1) {
                            table.ajax.reload();
                            $('#form-add-user')[0].reset();
                            $('#btn-modal-close-add').click();
                            $('#add-success-toast').toast('show')
                            $("#div-add-select-store").hide();
                        } else {
                            table.ajax.reload();
                            $('#form-add-user')[0].reset();
                            $('#btn-modal-close-add').click();
                            $('#add-fail-mng-user-toast').toast('show')
                            $("#div-add-select-store").hide();
                        }
                    }
                });
            });

            // Edit User AJAX Call Starts here
            $(document).on('click', ".btn-edit", function(e) {
                e.preventDefault();
                user_id = $(this).data("id");
                console.log("After edit pressed")
                console.log(user_id)
                $.ajax({
                    url: "ajax-admin-get-selected-user.php",
                    type: "POST",
                    data: {
                        "user_id": user_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.server_status == 1) {
                            $("#modal-edit-user").modal('show');
                            // if (data.user_role == "CSTAFF") {
                            //     $("#div-edit-select-store").show();
                            //     $('#form-edit-store').val(data.store_id);
                            //     $("#form-edit-store").prop('required', true);
                            // } else {
                            //     $("#div-edit-select-store").hide();
                            //     $("#form-edit-store").prop('required', false);
                            // }
                            $('#form-edit-username').val(data.user_username);
                            $('#form-edit-fname').val(data.user_fname);
                            $('#form-edit-lname').val(data.user_lname);
                            $('#form-edit-email').val(data.user_email);
                            $('#form-edit-role').val(data.user_role);
                            $('#form-edit-pwd').val(data.user_pwd);
                        } else {
                            $('#edit-fail-toast').toast('show')
                        }
                    }
                });
            });

            $("#form-edit-user").on('submit', function(e) {
                e.preventDefault();
                var user_username = $('#form-edit-username').val()
                var user_fname = $('#form-edit-fname').val()
                var user_lname = $('#form-edit-lname').val()
                var user_email = $('#form-edit-email').val()
                var user_role = $('#form-edit-role').val()
                var user_pwd = $('#form-edit-pwd').val()
             
                $.ajax({
                    url: "ajax-admin-update-user.php",
                    type: "POST",
                    data: {
                        
                        "user_username": user_username,
                        "user_fname": user_fname,
                        "user_lname": user_lname,
                        "user_email": user_email,
                        "user_role": user_role,
                        "user_pwd": user_pwd,
                        "user_id": user_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.server_status == 1) {
                            table.ajax.reload();
                            $('#btn-modal-close-edit').click();
                            $('#edit-success-toast').toast('show')
                        } else {
                            table.ajax.reload();
                            $('#btn-modal-close-edit').click();
                            $('#edit-fail-toast').toast('show')
                        }
                    }
                });
            });

            // Delete Store AJAX Call Starts here
            $(document).on('click', ".btn-delete", function(e) {
                e.preventDefault();
                var user_id = $(this).data("id");
                $.ajax({
                    url: "ajax-admin-delete-user.php",
                    type: "POST",
                    data: {
                        "user_id": user_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.server_status == 1) {
                            table.ajax.reload();
                            $('#delete-success-toast').toast('show')
                        } else {
                            $('#delete-fail-toast').toast('show')
                        }
                    }
                })
            })

            // Datatable Starts here
            var table = $('#user-table').DataTable({
                "ajax": {
                    "url": "ajax-admin-get-user.php",
                },
                'columns': [{
                        data: 'row'
                    },
                    {
                        data: 'user_username'
                    },
                    {
                        data: 'user_fname'
                    },
                    {
                        data: 'user_lname'
                    },
                    {
                        data: 'user_email'
                    },
                    {
                        data: 'user_pwd'
                    },
                    {
                        data: 'user_role'
                    },
                    {
                        data: 'user_id',
                        render: function(data, type, row) {
                            if (data != '') {
                                return '<div class="d-grid gap-2 d-md-block"> <a class="btn btn-outline-warning btn-sm btn-edit" data-id="' + data + '"> Edit </a> <a class="btn btn-outline-danger btn-sm btn-delete" data-id="' + data + '"> Delete </a></div>'
                            } else {
                                return ''
                            }
                        }
                    },
                ],
                responsive: true
            })
        });
    </script>
    <?php
    $query = "SELECT store_id,store_name FROM store;";
    $result = $mysqli->query($query);
    ?>

    <!-- Add-Modal -->
    <div class="modal fade" id="add-new-user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add New User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="form-add-user">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="modal-add-user-username" name="user-username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputPassword4" class="form-label">Password</label>
                            <input type="password" class="form-control" id="modal-add-user-pwd" name="user-pwd" minlength="8" required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="modal-add-user-email" placeholder="someone@mail.com" name="user-email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="modal-add-user-fname" name="user-fname" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="modal-add-user-lname" name="user-lname" required>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <select id="form-add-role" class="form-select" name="user-role" required>
                                <option value="">Please select</option>
                                <option value="CUST" id="role-cust">CUST</option>
                                <option value="ADMN">ADMN</option>
                                <option value="CSTAFF" id="role-cstaff">CSTAFF</option>
                            </select>
                        </div>
                       
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-modal-close-add">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-modal-add">Add User</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Add-Modal -->

    <?php
    $query = "SELECT store_id,store_name FROM store;";
    $result = $mysqli->query($query);
    ?>

    <!-- Edit-Modal -->
    <div class="modal fade" id="modal-edit-user" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="form-edit-user">
                        <div class="col-md-6">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="form-edit-username" name="user-username" required>
                        </div>
                        <div class="col-md-6">
                            <label for="inputPassword4" class="form-label">Password</label>
                            <input type="password" class="form-control" id="form-edit-pwd" name="user-pwd" minlength="8" required>
                        </div>
                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="form-edit-email" placeholder="someone@mail.com" name="user-email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="form-edit-fname" name="user-fname" required>
                        </div>
                        <div class="col-md-6">
                            <label for="lname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="form-edit-lname" name="user-lname" required>
                        </div>
                        <div class="col-md-6">
                            <label for="role" class="form-label">Role</label>
                            <select id="form-edit-role" class="form-select" name="user-role" required>
                                <option value="">Please select</option>
                                <option value="CUST">CUST</option>
                                <option value="ADMN">ADMN</option>
                                <option value="CSTAFF">CSTAFF</option>
                            </select>
                        </div>
                   
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-modal-close-edit">Close</button>
                        <button type="submit" class="btn btn-primary" id="form-btn-edit">Edit User</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of Edit-Modal -->

    <?php include("../toast-message.php"); ?>

</body>

</html>