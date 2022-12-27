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

    <title>Report Generation | Cafeteria Staff</title>
</head>

<body class="d-flex flex-column">
    <?php include('cstaff-nav.php') ?>
    <div class="container p-2 pb-0 mt-5 pt-3" id="admin-dashboard">
        <h2 class="pt-3 pb-5 display-6">Report Generation</h2>
        <br />
        <h6>Date Selection</h6>
        <div>
            <form method="POST" action="cstaff-generated-report.php" class="form-floating" id="form-report">
                <div class="form-check">
                    <div class="container">
                        <div class="row row-cols-2">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="start_date" placeholder="Start Date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" name="start_date">
                                    <label for="start_date">Start Date</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <input type="date" class="form-control" id="end_date" placeholder="End Date" value="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-d'); ?>" name="end_date">
                                    <label for="end_date">End Date</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="text-align: center;">
                        <button class="w-50 btn btn-outline-success my-3" type="submit">Generate Report</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        $("#form-report").on('submit', function(e) {
            var start_date = new Date($('#start_date').val());
            var end_date = new Date($('#end_date').val());
            if (start_date > end_date) {
                alert('Start date cannot be larger than end date !');
                return false;
            }
        });
    </script>

    <?php include('../footer.php'); ?>
    <?php include("../toast-message.php"); ?>
</body>

</html>