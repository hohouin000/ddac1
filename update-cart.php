<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php');
    if ( isset($_GET["mitem_id"])) {
        if (!empty($_GET["mitem_id"])) {
            $mitem_id = mysqli_real_escape_string($mysqli, $_GET["mitem_id"]);
        } else {
            header("location: cart.php");
            exit(1);
        }
    } else {
        header("location: cart.php");
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
    $query =  $mysqli->prepare("SELECT m.* FROM mitem m WHERE  m.mitem_id =?");
    $query->bind_param('i', $mitem_id);
    $query->execute();
    $row = $query->get_result()->fetch_array();
    ?>
    <div class="container p-5" id="menuitem-dashboard" style="margin-top:5%; margin-bottom:12%;">
        <div class="row my-3">
            <a class="nav nav-item text-decoration-none text-muted mb-2" onclick="history.back();">
                <i class="fa-solid fa-caret-left"> Go back</i></a>
        </div>
        <div class="row row-cols-1 row-cols-md-2 mb-5">
            <div class="col mb-3 mb-md-0">
                <img <?php
                        echo "src=\"{$row['mitem_pic']}\"";
                        ?> class="img-fluid rounded-25 float-start" alt="<?php echo $row["mitem_name"] ?>">
            </div>
            <div class="col text-wrap">
                <h1 class="fw-light"><?php echo $row["mitem_name"] ?></h1>
                <h3 class="fw-light">RM <?php echo $row["mitem_price"] ?></h3>
                <?php
                // $cart_query = "SELECT * FROM cart WHERE user_id = {$_SESSION['user_id']} AND mitem_id = {$mitem_id} AND store_id = {$store_id}";
                // $cart_result = $mysqli->query($cart_query)->fetch_array();
                $cart_query = $mysqli->prepare("SELECT * FROM cart WHERE user_id =? AND mitem_id =? ");
                $cart_query->bind_param('ii', $_SESSION['user_id'], $mitem_id);
                $cart_query->execute();
                $cart_result = $cart_query->get_result()->fetch_array();
                ?>
                <div class="form">
                    <form class="mt-3" id="form-atc" method="POST" action="func-update-cart.php">
                        <div class="input-group mb-3">
                            <input type="number" class="form-control text-center border-secondary" id="amount" value="<?php if (!empty($cart_result["cart_amount"])) echo $cart_result["cart_amount"] ?>" name="amount" value="1" min="1" max="99" required>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="remark" value="<?php if (!empty($cart_result["cart_remark"])) echo $cart_result["cart_remark"] ?>" name="remark" placeholder=" ">
                            <label for="remark" class="d-inline-text">Remark (Optional)</label>
                        </div>
                        <div class="d-grid gap-2 d-md-block">
                            <button class="btn btn-info" type="submit" title="update-cart" name="btn-update" id="btn-update">
                                <i class="fa-solid fa-pen-to-square"></i> Update Cart Item
                            </button>
                            <button class="btn btn-danger" type="submit" formaction="func-remove-item.php?mitem_id=<?php echo $mitem_id ?>" title="remove-item" name="btn-remove" id="btn-remove">
                                <i class="fa-solid fa-trash"></i> Remove Item
                            </button>
                        </div>
                        <input type="hidden" name="mitem-id" value="<?php echo $mitem_id ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>