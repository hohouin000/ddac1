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
    <link href="../css/rate.css" rel="stylesheet">
    <title>Rate Experience</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('cstaff-nav.php') ?>
    <div class="container px-5 py-4">
        <div class="container p-2 pb-0 mt-5 pt-3">
            <div class="row my-3">
                <a href="order-history.php" class="nav nav-item text-decoration-none text-muted">
                    <i class="fa-solid fa-caret-left"> Go back</i></a>
            </div>
            <h2 class="pt-3 display-6">Broadcast Message</h2>
        </div>
        <div class="container d-flex justify-content-center mt-5">
            <div class="card text-center" style="border: none;">
                <div class="card-body">
                    <div class="rate py-3 mt-3">
                        <form>
                            <div class="mb-3 mt-3">
                                <label for="comment" class="form-label">Title</label>
                                <input class="form-control" id="comment" name="comment" rows="5" maxlength="100"></input>
                            </div>
                            <div class="mb-3 mt-3">
                                <label for="comment" class="form-label">Content</label>
                                <textarea class="form-control" id="comment" name="comment" rows="5"></textarea>
                            </div>
                            <div class="buttons px-4 mb-3">
                                <button type="submit" class="btn btn-outline-primary btn-block">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include('../footer.php'); ?>
</body>

</html>