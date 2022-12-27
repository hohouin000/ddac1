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

    <title>Menu Management | Cafeteria Staff</title>
</head>

<body class="d-flex flex-column">
    <?php include('cstaff-nav.php') ?>
    <div class="container p-2 pb-0 mt-5 pt-3" id="admin-dashboard">
        <h2 class="pt-3 display-6">Menu Management</h2>
        <div class="row g-2 justify-content-md-end">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-new-menu-item">Add New Menu Item</button>
            </div>
        </div>
    </div>
    </div>

    <div class="container pt-2">
        <div class="table-responsive">
            <table id="menu-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <?php include('../footer.php'); ?>

    <script>
        $(document).ready(function() {
            var mitem_id;

            // Datatable Starts here
            var table = $('#menu-table').DataTable({
                "ajax": {
                    "url": "ajax-cstaff-get-menu-item.php",
                },
                'columns': [{
                        data: 'row'
                    },
                    {
                        data: 'mitem_pic',
                        render: function(data) {
                            if (data != "") {
                                //return '<img src="../img/menu/' + data + "?" + new Date().getTime() + '"class="img-fluid rounded" width="125px" height="120px"/>'
                                return '<img src="' + data +"?" + new Date().getTime() +'"class="img-fluid rounded" width="125px" height="120px"/>'
                            } else {
                                return ''
                            }
                        }
                    },
                    {
                        data: 'mitem_name'
                    },
                    {
                        data: 'mitem_price'
                    },
                    {
                        data: 'mitem_status'
                    },
                    {
                        data: 'mitem_id',
                        render: function(data, type, row) {
                            if (data != '') {
                                return '<div class="d-grid gap-2 d-md-block"> <a class="btn btn-outline-warning btn-sm btn-edit" data-id="' + data + '"> Edit </a> <a href= "cstaff-mng-menu-update-pic.php?mitem_id=' + data + '" class="btn btn-outline-info btn-sm btn-update-pic">Update Picture</a> <a class="btn btn-outline-danger btn-sm btn-delete" data-id="' + data + '"> Delete </a></div>'
                            } else {
                                return ''
                            }
                        }

                    },
                ]
            });

            // Add MenuItem AJAX Call Starts here
            $("#form-add-menu-item").on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "ajax-cstaff-add-menu-item.php",
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response.server_status == 1) {
                            table.ajax.reload();
                            $('#form-add-menu-item')[0].reset();
                            $('#btn-modal-close-add').click();
                            $('#add-success-toast').toast('show')
                        } else {
                            table.ajax.reload();
                            $('#form-add-menu-item')[0].reset();
                            $('#btn-modal-close-add').click();
                            $('#add-fail-mng-store-toast').toast('show')
                        }
                    }
                });
            });

            // Delete Store AJAX Call Starts here
            $(document).on('click', ".btn-delete", function(e) {
                e.preventDefault();
                mitem_id = $(this).data("id");
                $.ajax({
                    url: "ajax-cstaff-delete-menu-item.php",
                    type: "POST",
                    data: {
                        "mitem_id": mitem_id
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

            // Edit Store AJAX Call Starts here
            $(document).on('click', ".btn-edit", function(e) {
                e.preventDefault();
                mitem_id = $(this).data("id");
                $.ajax({
                    url: "ajax-cstaff-get-selected-menu-item.php",
                    type: "POST",
                    data: {
                        "mitem_id": mitem_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.server_status == 1) {
                            $("#modal-edit-menu-item").modal('show');

                            // Set Selected Store Details in Modal
                            $('#form-edit-item-name').val(data.mitem_name);
                            $('#form-edit-item-price').val(data.mitem_price);
                            if (data.mitem_status == 1) {
                                $("#form-edit-item-status").prop('checked', true);
                            } else {
                                $("#form-edit-item-status").prop('checked', false);
                            }
                        } else {
                            $('#edit-fail-toast').toast('show')
                        }
                    }
                })
            })

            $("#form-edit-menu-item").on('submit', function(e) {
                // Get Selected Store Details in Modal
                e.preventDefault();
                var mitem_name = $('#form-edit-item-name').val()
                var mitem_price = $('#form-edit-item-price').val()
                var mitem_status;

                if ($('#form-edit-item-status').is(':checked')) {
                    mitem_status = 1;
                } else {
                    mitem_status = 0;
                }

                // Update AJAX Call Starts here
                $.ajax({
                    url: "ajax-cstaff-update-menu-item.php",
                    type: "POST",
                    data: {
                        "mitem_name": mitem_name,
                        "mitem_price": mitem_price,
                        "mitem_status": mitem_status,
                        "mitem_id": mitem_id
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.server_status == 1) {
                            table.ajax.reload();
                            $('#btn-modal-close-edit').click();
                            $('#edit-success-toast').toast('show')
                        } else {
                            $('#edit-fail-toast').toast('show')
                        }
                    }
                })
            });

            // File type validation
            $("input[name='mitem-pic']").change(function() {
                var img;
                var file = this.files[0];
                var fileType = file.type;
                var match = ['image/png'];
                if (!(fileType == match[0])) {
                    alert('Sorry, only PNG files are allowed.');
                    $("input[name='mitem-pic']").val('');
                    return false;
                }
                if (this.files[0].size > 2097152) {
                    alert("File is too big! File size must be less than 2mb!");
                    $("input[name='mitem-pic']").val('');
                    return false;
                };
            });
        });
    </script>

    <!-- Add Modal -->
    <div class="modal fade" id="modal-add-new-menu-item" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="form-add-menu-item">
                        <div class="col-md-6">
                            <label for="storename" class="form-label">Item Name</label>
                            <input type="text" class="form-control" name="mitem-name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="storelocation" class="form-label">Item Price</label>
                            <input type="text" class="form-control" name="mitem-price" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="mitem-status" value="1" checked required>
                                <label class="form-check-label" for="storestatus">Available For Today</label>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="formFile" class="form-label">Upload Store Picture</label>
                            <input class="form-control" type="file" accept="image/png" name="mitem-pic" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-modal-close-add">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-add-menu-item" name="btn-add-menu-item">Add Menu Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Add Modal -->

    <!-- Edit Modal -->
    <div class="modal fade" id="modal-edit-menu-item" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Menu Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="form-edit-menu-item">
                        <div class="col-md-6">
                            <label for="storename" class="form-label">Item Name</label>
                            <input type="text" class="form-control" id="form-edit-item-name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="storelocation" class="form-label">Item Price</label>
                            <input type="text" class="form-control" id="form-edit-item-price" required>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="form-edit-item-status">
                                <label class="form-check-label" for="itemstatus">Available For Today</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-modal-close-edit">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-edit-menu-item">Edit Menu Item</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Edit Modal -->

    <?php include("../toast-message.php"); ?>
</body>

</html>