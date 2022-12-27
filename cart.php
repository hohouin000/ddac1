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
    ?>
    <link href="css/cart.css" rel="stylesheet" />
    <script src="js/func-stripe.js"></script>
    <title>Cart</title>
</head>

<body class="d-flex flex-column h-100">
    <?php
    include('nav.php');
    ?>
    <div class="container p-5" id="menu-dashboard" style="margin-top:3%;">
        <?php
        if (isset($_SESSION['server_status'])) {
            if ($_SESSION['server_status'] == 1) {
        ?>
                <div class="alert alert-success alert-dismissible fade show mt-3 mb-3" role="alert">
                    Cart Updated Successfully !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
                unset($_SESSION['server_status']);
            } else {
            ?>
                <div class="alert alert-warning alert-dismissible fade show mt-3 mb-3" role="alert">
                    Failed to Update Cart !
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
        <?php
                unset($_SESSION['server_status']);
            }
        }
        ?>
        <div class="row my-3">
            <a class="nav nav-item text-decoration-none text-muted" onclick="history.back()">
                <i class="fa-solid fa-caret-left"> Go back</i></a>
        </div>
        <?php
        // $query = "SELECT * FROM store s WHERE store_id = (SELECT store_id FROM cart WHERE user_id = {$_SESSION['user_id']} LIMIT 0,1)";
        // $result = $mysqli->query($query);
        // $rowcount = mysqli_num_rows($result);
        $query = $mysqli->prepare("SELECT * FROM store ");
        // $query->bind_param('i', $_SESSION['user_id']);
        $query->execute();
        $result = $query->get_result();
        $rowcount = $result->num_rows;
        if ($rowcount > 0) {
            // $arr = $result->fetch_array();
            $arr = $result->fetch_assoc();
            $store_open = $arr["store_openhour"];
            $store_close = $arr["store_closehour"];
            $curr_time = date("H:i");
            $store_open_arr = explode(":", $store_open);
            $new_store_open = $store_open_arr[0] . ":" . $store_open_arr[1];
            $store_close_arr = explode(":", $store_close);
            $new_store_close = $store_close_arr[0] . ":" . $store_close_arr[1];
            if (($arr["store_status"] == 1) && ($curr_time >= $new_store_open) && ($curr_time < $new_store_close)) {
                $store_closed = false;
            } else {
                $store_closed = true;
            }
        ?>
            <section class="h-100 gradient-custom">
                <div class="container py-5">
                    <div class="row d-flex justify-content-center my-4">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Cart - <?php echo $cart_total ?> item(s) from <p><strong><?php echo $arr["store_name"] ?> store</strong></p>
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    // $cart_query = "SELECT c.*, m.* FROM cart c INNER JOIN mitem m ON c.mitem_id = m.mitem_id WHERE c.user_id = {$_SESSION['user_id']}";
                                    // $cart_result = $mysqli->query($cart_query);
                                    $cart_query = $mysqli->prepare("SELECT c.*, m.* FROM cart c INNER JOIN mitem m ON c.mitem_id = m.mitem_id WHERE c.user_id =?");
                                    $cart_query->bind_param('i', $_SESSION['user_id']);
                                    $cart_query->execute();
                                    $cart_result = $cart_query->get_result();
                                    while ($row = $cart_result->fetch_array()) {
                                        $total_price =  $row["cart_amount"] * $row["mitem_price"];
                                    ?>
                                        <!-- Single item -->
                                        <div class="row">
                                            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0">
                                                <!-- Image -->
                                                <div class="img-fluid rounded">
                                                    <img <?php echo "src=\"{$row['mitem_pic']}\""; ?> class="w-100" style="width:100%; height:175px; object-fit:cover;" alt="<?php echo $row["mitem_name"] ?>" />
                                                </div>
                                                <!-- Image -->
                                            </div>

                                            <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                                                <!-- Data -->
                                                <p><strong><?php echo $row["mitem_name"] ?></strong></p>
                                                <p>Amount: <?php echo $row["cart_amount"] ?></p>
                                                <p>Remark: <?php
                                                            if (!empty($row["cart_remark"])) {
                                                                echo $row["cart_remark"];
                                                            } else {
                                                                echo "None";
                                                            } ?></p>
                                                <!-- Data -->
                                            </div>

                                            <div class="col-lg-4 col-md-6 mb-4 mb-lg-0">
                                                <!-- Price -->
                                                <p class="text-start text-md-center">
                                                    <strong><?php printf('RM %0.2f', $total_price); ?></strong>
                                                <p class="text-start text-md-center"> (<?php printf('RM %0.2f', $row["mitem_price"]) ?> each)</p>
                                                </p>
                                                <div class="d-flex align-items-end justify-content-end">
                                                    <a href="update-cart.php?mitem_id=<?php echo $row["mitem_id"]; ?>" button type="button" class="btn btn-primary btn-sm me-1 mb-2" data-mdb-toggle="tooltip" title="Edit item">
                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                    </a>
                                                    <!-- Price -->
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Single item -->
                                        <hr class="my-4" />
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="card mb-4 mb-lg-0">
                                <div class="card-body">
                                    <p><strong>We accept</strong></p>
                                    <img class="me-2" width="45px" src="img/payment/visa.svg" alt="Visa" />
                                    <img class="me-2" width="45px" src="img/payment/mastercard.svg" alt="Mastercard" />
                                    <img class="me-2" width="45px" src="img/payment/google-pay.svg" alt="Google-Pay" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Summary</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    // $sum_query = "SELECT SUM(c.cart_amount*m.mitem_price) AS total_price FROM cart c INNER JOIN mitem m ON c.mitem_id = m.mitem_id WHERE c.user_id = {$_SESSION['user_id']}";
                                    // $sum_arr = $mysqli->query($sum_query)->fetch_array();
                                    $sum_query = $mysqli->prepare("SELECT SUM(c.cart_amount*m.mitem_price) AS total_price FROM cart c INNER JOIN mitem m ON c.mitem_id = m.mitem_id WHERE c.user_id =?");
                                    $sum_query->bind_param('i', $_SESSION['user_id']);
                                    $sum_query->execute();
                                    $sum_arr = $sum_query->get_result()->fetch_array();
                                    ?>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                            Products
                                            <span><?php printf('RM %0.2f', $sum_arr['total_price']); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                            <div>
                                                <strong>Total amount</strong>
                                                <strong>
                                                    <p class="mb-0">(including VAT)</p>
                                                </strong>
                                            </div>
                                            <span><strong><?php printf('RM %0.2f', $sum_arr['total_price']); ?></strong></span>
                                        </li>
                                    </ul>
                                    <?php if (!$store_closed) { ?>
                                        <form action="func-add-order.php" method="POST">
                                            <button type="submit" class="btn btn-primary btn-sm btn-block" title="btn-pay-counter" name="btn-pay-counter" id="btn-pay-counter">
                                                Pay at counter
                                            </button>
                                            <button type="submit" class="btn btn-info btn-sm btn-block" id="btn-pay-online" formaction="func-stripe-checkout.php">
                                                Pay via Online Gateway
                                            </button>
                                            <input type="hidden" name="total-amount" value="<?php echo $sum_arr['total_price']; ?>">
                                        </form>
                                    <?php } else { ?>
                                        <div class="d-flex py-1 mb-3">
                                            <button type="button" class="btn btn-primary btn-sm me-1" disabled>
                                                Pay at counter
                                            </button>
                                            <button type="button" class="btn btn-info btn-sm" disabled>
                                                Pay via Online Gateway
                                            </button>
                                        </div>
                                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                            <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                            </symbol>
                                        </svg>
                                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:">
                                                <use xlink:href="#exclamation-triangle-fill" />
                                            </svg>
                                            <div>
                                                Store Closed ! <br />
                                                Please try again next time.
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } else { ?>
            <section class="h-100 gradient-custom">
                <div class="container py-5">
                    <div class="row d-flex justify-content-center my-4">
                        <div class="col-md-8">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Cart - <?php echo $cart_total ?> items
                                    </h5>
                                </div>
                                <div class="card-body">
                                </div>
                            </div>

                            <div class="card mb-4 mb-lg-0">
                                <div class="card-body">
                                    <p><strong>We accept</strong></p>
                                    <img class="me-2" width="45px" src="img/payment/visa.svg" alt="Visa" />
                                    <img class="me-2" width="45px" src="img/payment/mastercard.svg" alt="Mastercard" />
                                    <img class="me-2" width="45px" src="img/payment/google-pay.svg" alt="Google-Pay" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-header py-3">
                                    <h5 class="mb-0">Summary</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                            Products
                                            <span><?php printf('RM %0.2f', 0); ?></span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                            <div>
                                                <strong>Total amount</strong>
                                                <strong>
                                                    <p class="mb-0">(including VAT)</p>
                                                </strong>
                                            </div>
                                            <span><strong><?php printf('RM %0.2f', 0); ?></strong></span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        <?php } ?>
    </div>
    <?php include('footer.php'); ?>
    <?php include("toast-message.php"); ?>
</body>

</html>