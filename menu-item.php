<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php');
    if ( isset($_GET["mitem_id"])) {
        if ( !empty($_GET["mitem_id"])) {
 
            $mitem_id = mysqli_real_escape_string($mysqli, $_GET["mitem_id"]);
        } else {
            header("location: index.php");
            exit(1);
        }
    } else {
        header("location: index.php");
        exit(1);
    }

    if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
        header("location: login.php");
        exit(1);
    }
    ?>
    <title>Menu Item</title>
</head>

<body class="d-flex flex-column h-100">
    <?php
    include('nav.php');
    // $query = "SELECT m.*,s.* FROM mitem m INNER JOIN store s ON m.store_id = s.store_id WHERE m.store_id = {$store_id} AND m.mitem_id = {$mitem_id}";
    // $result = $mysqli->query($query);
    // $row = $result->fetch_array();
    $query = $mysqli->prepare("SELECT m.* FROM mitem m WHERE m.mitem_id =?");
    $query->bind_param('i', $mitem_id);
    $query->execute();
    $row = $query->get_result()->fetch_array();
    ?>
    <div class="container p-5" id="menuitem-dashboard" style="margin-top:5%;">
        <div class="row my-3">
            <a class="nav nav-item text-decoration-none text-muted mb-2" onclick="history.back();">
                <i class="fa-solid fa-caret-left"> Go back</i></a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 mb-5">
            <div class="col mb-3 mb-md-0">
                <img <?php
                        echo "src=\"{$row['mitem_pic']}\"";
                        ?> class="img-fluid rounded-25 float-start" style="width:300%; height:450px;" alt="<?php echo $row["mitem_name"] ?>">
            </div>
            <div class="col text-wrap">
                <h1 class="fw-light"><?php echo $row["mitem_name"] ?></h1>
                <h3 class="fw-light">RM <?php echo $row["mitem_price"] ?></h3>
                <div class="form">
                    <form class="mt-3" id="form-atc" method="POST" action="func-add-to-cart.php">
                        <div class="input-group mb-3">
                            <input type="number" class="form-control text-center border-secondary" id="amount" name="amount" value="1" min="1" max="99" required>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="remark" name="remark" placeholder=" ">
                            <label for="remark" class="d-inline-text">Remark (Optional)</label>
                        </div>
                        <button class="btn btn-info w-100" type="submit" title="add-to-cart" name="btn-submit" id="btn-submit">
                            <?php
                            // $query = "SELECT COUNT(*) AS itemcnt FROM cart WHERE user_id = {$_SESSION['user_id']}";
                            // $row = $mysqli->query($query)->fetch_array();
                            $query = $mysqli->prepare("SELECT COUNT(*) AS itemcnt FROM cart WHERE user_id =?");
                            $query->bind_param('i', $_SESSION['user_id']);
                            $query->execute();
                            $row = $query->get_result()->fetch_array();
                            if ($row["itemcnt"] > 0) {
                                // $query2 = "SELECT COUNT(*) AS itemcnt FROM cart WHERE user_id = {$_SESSION['user_id']} AND store_id = {$store_id}";
                                // $row2 = $mysqli->query($query2)->fetch_array();
                                $query2 = $mysqli->prepare("SELECT COUNT(*) AS itemcnt FROM cart WHERE user_id =?");
                                $query2->bind_param('i', $_SESSION['user_id']);
                                $query2->execute();
                                $row2 = $query2->get_result()->fetch_array();
                                // if ($row2["itemcnt"] == 0) { 
                                // }
                            }
                            ?>
                            <i class="fa-solid fa-cart-shopping"></i> Add to Cart
                        </button>
                        <input type="hidden" name="mitem-id" value="<?php echo $mitem_id ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
    <script>
        $(document).ready(function() {
            $("#form-atc").on('submit', function(e) {
                var cnt = "<?php echo $row2["itemcnt"]; ?>";
                if (cnt == 0) {
                    e.preventDefault();
                    alertify.confirm('Store Change Notification', 'You have already selected different store. If you wish to continue, the current items added in your cart will be automatically removed.', function() {
                            $("#form-atc")[0].submit();
                        },
                        function() {});
                }
            });
        });
    </script>
</body>

</html>