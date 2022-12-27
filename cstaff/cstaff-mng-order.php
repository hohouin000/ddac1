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

    <title>Order Management | Cafeteria Staff</title>
</head>

<body class="d-flex flex-column">
    <?php include('cstaff-nav.php') ?>
    <div class="container p-2 pb-0 mt-5 pt-3" id="admin-dashboard">
        <h2 class="pt-3 pb-5 display-6">Order Management</h2>
        <nav>
            <div class="nav nav-pills flex-wrap mb-3" id="pills-tab" role="tablist">
                <button class="nav-link active px-4" id="unpd-tab" data-bs-toggle="tab" data-bs-target="#nav-unpd" type="button" role="tab" aria-controls="nav-unpd" aria-selected="true">Unpaid</button>
                <button class="nav-link px-4" id="prep-tab" data-bs-toggle="tab" data-bs-target="#nav-prep" type="button" role="tab" aria-controls="nav-prep" aria-selected="true">Preparing</button>
                <button class="nav-link px-4" id="rdfk-tab" data-bs-toggle="tab" data-bs-target="#nav-rdfk" type="button" role="tab" aria-controls="nav-rdfk" aria-selected="true">Ready for pick-up</button>
                <button class="nav-link px-4" id="cmplt-tab" data-bs-toggle="tab" data-bs-target="#nav-cmplt" type="button" role="tab" aria-controls="nav-cmplt" aria-selected="false">Completed</button>
                <button class="nav-link px-4" id="cxld-tab" data-bs-toggle="tab" data-bs-target="#nav-cxld" type="button" role="tab" aria-controls="nav-cxld" aria-selected="false">Cancelled</button>
            </div>
        </nav>
    </div>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-unpd" role="tabpanel" aria-labelledby="unpd-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="unpaid-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>Order Reference</th>
                                <th>Customer Name</th>
                                <th>Order Placement Time</th>
                                <th>Total Price</th>
                                <th>Order Details</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-prep" role="tabpanel" aria-labelledby="prep-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="preparing-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>Order Reference</th>
                                <th>Customer Name</th>
                                <th>Order Placement Time</th>
                                <th>Total Price</th>
                                <th>Order Details</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-rdfk" role="tabpanel" aria-labelledby="rdfk-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="rdfk-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>Order Reference</th>
                                <th>Customer Name</th>
                                <th>Order Placement Time</th>
                                <th>Total Price</th>
                                <th>Order Details</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-cmplt" role="tabpanel" aria-labelledby="cmplt-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="completed-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>Order Reference</th>
                                <th>Customer Name</th>
                                <th>Order Placement Time</th>
                                <th>Total Price</th>
                                <th>Order Details</th>
                                <th>Order Completion Time</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="nav-cxld" role="tabpanel" aria-labelledby="cxld-tab">
            <div class="container pt-2">
                <div class="table-responsive">
                    <table id="cancelled-table" table class="table table table-striped rounded-5 table-light table-striped table-hover align-middle caption-top mb-5" style="width:100%">
                        <thead>
                            <tr>
                                <th>Order Reference</th>
                                <th>Customer Name</th>
                                <th>Order Placement Time</th>
                                <th>Total Price</th>
                                <th>Order Details</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include('../footer.php'); ?>

    <script>
        $(document).ready(function() {
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust()
                    .scroller.measure();
            });

            //func reload all table
            $.fn.reloadTable = function() {
                unpd_table.ajax.reload();
                prep_table.ajax.reload();
                rdfk_table.ajax.reload();
                cmplt_table.ajax.reload();
                cxld_table.ajax.reload();
            }

            // Datatable Starts here
            var unpd_table = $('#unpaid-table').DataTable({
                "ajax": {
                    "url": "ajax-cstaff-get-order-unpaid.php",
                },
                'columns': [{
                        data: 'odr_ref'
                    },
                    {
                        data: 'user_name',
                    },
                    {
                        data: 'odr_placedtime'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'odr_details'
                    },
                    {
                        data: 'odr_id',
                        render: function(data, type, row) {
                            if (data != '') {
                                return '<div class="d-grid gap-2 d-md-block"> <a class="btn btn-outline-success btn-sm  btn-received " data-id="' + data + '"> Mark as Paid </a><a class="btn btn-outline-danger btn-sm btn-cancel" data-id="' + data + '"> Cancel </a></div>'
                            } else {
                                return ''
                            }
                        }

                    },
                ],
                scrollY: 200,
                sScrollX: "100%",
                scrollCollapse: true,
            });

            var prep_table = $('#preparing-table').DataTable({
                "ajax": {
                    "url": "ajax-cstaff-get-order-preparing.php",
                },
                'columns': [{
                        data: 'odr_ref'
                    },
                    {
                        data: 'user_name',
                    },
                    {
                        data: 'odr_placedtime'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'odr_details'
                    },
                    {
                        data: 'odr_id',
                        render: function(data, type, row) {
                            if (data != '') {
                                return '<div class="d-grid gap-2 d-md-block"> <a class="btn btn-outline-success me-1 my-1 btn-sm  btn-prepared " data-id="' + data + '"> Mark as Prepared </a></div>'
                            } else {
                                return ''
                            }
                        }

                    },
                ],
                scrollY: 200,
                scrollCollapse: true,
            });

            var rdfk_table = $('#rdfk-table').DataTable({
                "ajax": {
                    "url": "ajax-cstaff-get-order-pickup.php",
                },
                'columns': [{
                        data: 'odr_ref'
                    },
                    {
                        data: 'user_name',
                    },
                    {
                        data: 'odr_placedtime'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'odr_details'
                    },
                    {
                        data: 'odr_id',
                        render: function(data, type, row) {
                            if (data != '') {
                                return '<div class="d-grid gap-2 d-md-block"> <a class="btn btn-outline-success me-1 my-1 btn-sm  btn-pickedup " data-id="' + data + '"> Mark as Picked Up </a></div>'
                            } else {
                                return ''
                            }
                        }

                    },
                ],
                scrollY: 200,
                scrollCollapse: true,
            });

            var cmplt_table = $('#completed-table').DataTable({
                "ajax": {
                    "url": "ajax-cstaff-get-order-completed.php",
                },
                'columns': [{
                        data: 'odr_ref'
                    },
                    {
                        data: 'user_name',
                    },
                    {
                        data: 'odr_placedtime'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'odr_details'
                    },
                    {
                        data: 'odr_compltime'
                    },
                    {
                        data: 'odr_id',
                        render: function(data, type, row) {
                            if (data != '') {
                                return '<div class="d-grid gap-2 d-md-block"> <a class="btn btn-outline-success me-1 my-1 btn-sm  btn-generate-receipt " data-id="' + data + '"style="font-size:smaller;"> Generate Receipt </a>'
                            } else {
                                return ''
                            }
                        }

                    },
                ],
                scrollY: 200,
                scrollCollapse: true,
            });

            var cxld_table = $('#cancelled-table').DataTable({
                "ajax": {
                    "url": "ajax-cstaff-get-order-cancelled.php",
                },
                'columns': [{
                        data: 'odr_ref'
                    },
                    {
                        data: 'user_name',
                    },
                    {
                        data: 'odr_placedtime'
                    },
                    {
                        data: 'total_price'
                    },
                    {
                        data: 'odr_details'
                    }
                ],
                scrollY: 200,
                scrollCollapse: true,
            });

            //Update Order Status
            $(document).on('click', ".btn-received", function(e) {
                e.preventDefault();
                odr_id = $(this).data("id");
                odr_status = "PREP";
                $.ajax({
                    url: "ajax-cstaff-update-order-status.php",
                    type: "POST",
                    data: {
                        "odr_id": odr_id,
                        "odr_status": odr_status
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.server_status == 1) {
                            $.fn.reloadTable();
                            $('#edit-success-toast').toast('show')
                        } else {
                            $('#edit-fail-toast').toast('show')
                        }
                    }
                })
            })

            $(document).on('click', ".btn-cancel", function(e) {
                e.preventDefault();
                odr_id = $(this).data("id");
                odr_status = "CXLD";
                $.ajax({
                    url: "ajax-cstaff-update-order-status.php",
                    type: "POST",
                    data: {
                        "odr_id": odr_id,
                        "odr_status": odr_status
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.server_status == 1) {
                            $.fn.reloadTable();
                            $('#edit-success-toast').toast('show')
                        } else {
                            $('#edit-fail-toast').toast('show')
                        }
                    }
                })
            })

            $(document).on('click', ".btn-prepared", function(e) {
                e.preventDefault();
                odr_id = $(this).data("id");
                odr_status = "RDFK";
                $.ajax({
                    url: "ajax-cstaff-update-order-status.php",
                    type: "POST",
                    data: {
                        "odr_id": odr_id,
                        "odr_status": odr_status
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.server_status == 1) {
                            $.fn.reloadTable();
                            $('#edit-success-toast').toast('show')
                        } else {
                            $('#edit-fail-toast').toast('show')
                        }
                    }
                })
            })

            $(document).on('click', ".btn-pickedup", function(e) {
                e.preventDefault();
                odr_id = $(this).data("id");
                odr_status = "CMPLT";
                $.ajax({
                    url: "ajax-cstaff-update-order-status.php",
                    type: "POST",
                    data: {
                        "odr_id": odr_id,
                        "odr_status": odr_status
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.server_status == 1) {
                            $.fn.reloadTable();
                            $('#edit-success-toast').toast('show')
                        } else {
                            $('#edit-fail-toast').toast('show')
                        }
                    }
                })
            })
        });
    </script>

    <?php include("../toast-message.php"); ?>
</body>

</html>