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

    <title>Store Management | Admin</title>
</head>

<body class="d-flex flex-column">
    <?php include('admin-nav.php') ?>
    <div class="container p-2 pb-0 mt-5 pt-3" id="admin-dashboard">
        <h2 class="pt-3 display-6">Store Management</h2>
        <div class="row g-2 justify-content-md-end">
            <div class="col-auto">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-add-new-store">Add New Store</button>
            </div>
        </div>
    </div>
    </div>

    <div class="container pt-2">
        <div class="table-responsive">
            <table id="store-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Opening Hour</th>
                        <th>Closing Hour</th>
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
            var store_id;

            // Datatable Starts here
            var table = $('#store-table').DataTable({
                "ajax": {
                    "url": "ajax-admin-get-store.php",
                },
                'columns': [{
                        data: 'row'
                    },
                    {
                        data: 'store_pic',
                        render: function(data) {
                            if (data != '') {
                                return '<img src="/fyp/img/store/' + data + "?" + new Date().getTime() + '"class="img-circle" width="125px" height="120px"/>'
                            } else {
                                return ''
                            }
                        }
                    },
                    {
                        data: 'store_name'
                    },
                    {
                        data: 'store_location'
                    },
                    {
                        data: 'store_openhour'
                    },
                    {
                        data: 'store_closehour'
                    },
                    {
                        data: 'store_status'
                    },
                    {
                        data: 'store_id',
                        render: function(data, type, row) {
                            if (data != '') {
                                return '<div class="d-grid gap-2 d-md-block"> <a class="btn btn-outline-warning btn-sm btn-edit" data-id="' + data + '"> Edit </a> <a href= "admin-mng-store-update-pic.php?store_id=' + data + '" class="btn btn-outline-info btn-sm btn-update-pic">Update Picture</a> <a class="btn btn-outline-danger btn-sm btn-delete" data-id="' + data + '"> Delete </a></div>'
                            } else {
                                return ''
                            }
                        }

                    },
                ],
                responsive: true
            });

            // Add Store AJAX Call Starts here
            $("#form-add-store").on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "ajax-admin-add-store.php",
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response.server_status == 1) {
                            table.ajax.reload();
                            $('#form-add-store')[0].reset();
                            $('#btn-modal-close-add').click();
                            $('#add-success-toast').toast('show')
                        } else {
                            table.ajax.reload();
                            $('#form-add-store')[0].reset();
                            $('#btn-modal-close-add').click();
                            $('#add-fail-mng-store-toast').toast('show')
                        }
                    }
                });
            });

            // Delete Store AJAX Call Starts here
            $(document).on('click', ".btn-delete", function(e) {
                e.preventDefault();
                store_id = $(this).data("id");
                console.log(store_id)
                $.ajax({
                    url: "ajax-admin-delete-store.php",
                    type: "POST",
                    data: {
                        "store_id": store_id
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
                store_id = $(this).data("id");
                $.ajax({
                    url: "ajax-admin-get-selected-store.php",
                    type: "POST",
                    data: {
                        "store_id": store_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.server_status == 1) {
                            $("#modal-edit-store").modal('show');

                            // Set Selected Store Details in Modal
                            $('#form-edit-store-name').val(data.store_name);
                            $('#form-edit-store-location').val(data.store_location);
                            $('#form-edit-store-openhour').val(data.store_openhour);
                            $('#form-edit-store-closehour').val(data.store_closehour);
                            if (data.store_status == 1) {
                                $("#form-edit-store-status").prop('checked', true);
                            } else {
                                $("#form-edit-store-status").prop('checked', false);
                            }
                        } else {
                            $('#edit-fail-toast').toast('show')
                        }
                    }
                })
            })

            $("#form-edit-store").on('submit', function(e) {
                // Get Selected Store Details in Modal
                e.preventDefault();
                var store_name = $('#form-edit-store-name').val()
                var store_location = $('#form-edit-store-location').val()
                var store_openhour = $('#form-edit-store-openhour').val()
                var store_closehour = $('#form-edit-store-closehour').val()
                var store_status;

                if ($('#form-edit-store-status').is(':checked')) {
                    store_status = 1;
                } else {
                    store_status = 0;
                }

                // Update AJAX Call Starts here
                $.ajax({
                    url: "ajax-admin-update-store.php",
                    type: "POST",
                    data: {
                        "store_id": store_id,
                        "store_name": store_name,
                        "store_location": store_location,
                        "store_openhour": store_openhour,
                        "store_closehour": store_closehour,
                        "store_status": store_status,
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
            $("input[name='store-pic']").change(function() {
                var img;
                var file = this.files[0];
                var fileType = file.type;
                var match = ['image/png'];
                if (!(fileType == match[0])) {
                    alert('Sorry, only PNG files are allowed.');
                    $("input[name='store-pic']").val('');
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
    <div class="modal fade" id="modal-add-new-store" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="form-add-store">
                        <div class="col-md-6">
                            <label for="storename" class="form-label">Store Name</label>
                            <input type="text" class="form-control" name="store-name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="storelocation" class="form-label">Location</label>
                            <input type="text" class="form-control" name="store-location" required>
                        </div>
                        <div class="row row-cols-2 g-2 mb-2">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="time" class="form-control" placeholder="Open Hour" name="store-openhour" required>
                                    <label for="storeopenhour">Open Hour</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <input type="time" class="form-control" placeholder="Close Hour" name="store-closehour" required>
                                    <label for="storeopenhour">Close Hour</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="store-status" value="1" checked required>
                                <label class="form-check-label" for="storestatus">Open For Today</label>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label for="formFile" class="form-label">Upload Store Picture</label>
                            <input class="form-control" type="file" accept="image/png" name="store-pic" required>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-modal-close-add">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-add-store" name="btn-add-store">Add Store</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Add Modal -->

    <!-- Edit Modal -->
    <div class="modal fade" id="modal-edit-store" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Store</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="form-edit-store">
                        <div class="col-md-6">
                            <label for="storename" class="form-label">Store Name</label>
                            <input type="text" class="form-control" id="form-edit-store-name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="storelocation" class="form-label">Location</label>
                            <input type="text" class="form-control" id="form-edit-store-location" required>
                        </div>
                        <div class="row row-cols-2 g-2 mb-2">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="time" class="form-control" id="form-edit-store-openhour" placeholder="Open Hour" required>
                                    <label for="storeopenhour">Open Hour</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <input type="time" class="form-control" id="form-edit-store-closehour" placeholder="Close Hour" required>
                                    <label for="storeopenhour">Close Hour</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="form-edit-store-status">
                                <label class="form-check-label" for="storestatus">Open For Today</label>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btn-modal-close-edit">Close</button>
                        <button type="submit" class="btn btn-primary" id="btn-edit-store" name="btn-edit-store">Edit Store</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Edit Modal -->

    <?php include("../toast-message.php"); ?>
</body>

</html>