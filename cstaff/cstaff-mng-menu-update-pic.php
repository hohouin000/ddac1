<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("../conn_db.php");
    include('../head.php');
    $mitem_id = $_GET['mitem_id'];
    ?>

    <title>Menu Management - Update Picture | Cafeteria Staff</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('cstaff-nav.php') ?>
    <div class="container form-e">
        <a class="nav nav-item text-decoration-none text-muted" href="cstaff-mng-menu.php">
            <i class="bi bi-arrow-left-square me-2"></i>Go back</a>
        <form id="form-update-pic" class="form-floating">
            <div class="mb-3">
                <label for="formFile" class="form-label">Upload Menu Item Picture</label>
                <input class="form-control" type="file" id="form-edit-menu-item-pic" accept="image/png" name="mitem-pic" required>
            </div>
            <input type="hidden" name="mitem-id" value="<?php echo $mitem_id; ?>">
            <button type="submit" class="btn btn-primary btn-sm" id="btn-update-pic" name="btn-update-pic">Update Picture</button>
        </form>
    </div>

    <?php include('../footer.php'); ?>

    <script>
        $(document).ready(function() {
            var updatePicFailAlert = document.getElementById('update-pic-fail-toast');
            var updatePicFail = new bootstrap.Toast(updatePicFailAlert);

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


            // Update Store Pic AJAX Call Starts here
            $("#form-update-pic").on('submit', function(e) {
                e.preventDefault();
                $.ajax({
                    url: "ajax-cstaff-update-menu-item-pic.php",
                    type: "POST",
                    data: new FormData(this),
                    dataType: 'json',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        if (response.server_status == 1) {
                            window.location.href = "cstaff-mng-menu.php";
                        } else {
                            updatePicFail.show();
                        }
                    }
                });
            });
        });
    </script>

    <!-- Edit Failed Toast Message -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true" id="update-pic-fail-toast">
            <div class="d-flex">
                <div class="toast-body">
                    Failed to Update Picture
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    <!-- End of Edit Failed Toast Message -->
</body>

</html>