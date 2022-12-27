<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php');
    if (isset($_GET["odr_id"])) {
        if (!empty($_GET["odr_id"])) {
            $odr_id = mysqli_real_escape_string($mysqli, $_GET["odr_id"]);
        } else {
            header("location: order-history.php");
            exit(1);
        }
    } else {
        header("location: order-history.php");
        exit(1);
    }

    if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
        header("location: login.php");
        exit(1);
    }
    ?>
    <link href="css/order-details.css" rel="stylesheet">
    <title>Order Details</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('nav.php') ?>
    <div class="container px-5 py-4">
        <div class="container p-2 pb-0 mt-5 pt-3">
            <div class="row my-3">
                <a href="order-history.php" class="nav nav-item text-decoration-none text-muted">
                    <i class="fa-solid fa-caret-left"> Go back</i></a>
            </div>
            <h2 class="pt-3 display-6">Order Details</h2>
            <div class="container pt-2 mt-5">
                <?php
                $total = 0;
                // $query = "SELECT odr_ref FROM odr WHERE odr_id = {$odr_id}";
                // $arr = $mysqli->query($query)->fetch_array();
                $query = $mysqli->prepare("SELECT odr_ref FROM odr WHERE odr_id =?");
                $query->bind_param('i', $odr_id);
                $query->execute();
                $arr = $query->get_result()->fetch_array();
                $odr_ref = $arr['odr_ref'];

                // $query = "SELECT SUM(odr_detail_amount*odr_detail_price) AS total_price FROM odr_detail WHERE odr_id = {$odr_id}";
                // $arr = $mysqli->query($query)->fetch_array();
                $query = $mysqli->prepare("SELECT SUM(odr_detail_amount*odr_detail_price) AS total_price FROM odr_detail WHERE odr_id =?");
                $query->bind_param('i', $odr_id);
                $query->execute();
                $arr = $query->get_result()->fetch_array();
                $total = $arr['total_price'];

                // $query = "SELECT SUM(od.odr_detail_amount * od.odr_detail_price)as 'total', m.mitem_name,od.odr_detail_amount,od.odr_detail_price,od.odr_detail_remark FROM odr_detail od INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE od.odr_id = {$odr_id} GROUP BY m.mitem_name";
                // $result = $mysqli->query($query);
                // $rowcount = mysqli_num_rows($result);
                $query =  $mysqli->prepare("SELECT SUM(od.odr_detail_amount * od.odr_detail_price)as 'total', m.mitem_name,od.odr_detail_amount,od.odr_detail_price,od.odr_detail_remark FROM odr_detail od INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE od.odr_id =? GROUP BY m.mitem_name");
                $query->bind_param('i', $odr_id);
                $query->execute();
                $result = $query->get_result();
                $rowcount = $result->num_rows;
                if ($rowcount > 0) {
                ?>
                    <p>
                        <b>Order Ref: <?php echo $odr_ref ?></b><br />
                        <b>Order Summary</b><br />
                        <?php
                        while ($row = $result->fetch_array()) {
                        ?>
                            <?php echo $row["odr_detail_amount"] . "X " ?>
                            <?php
                            if ($row["odr_detail_remark"] != "") {
                                echo $row["mitem_name"] . " (" . $row["odr_detail_remark"] . ") ";
                            ?>
                                <small class="text-muted">- RM <?php echo $row["odr_detail_price"] ?> each</small>
                            <?php
                            } else {
                                echo $row["mitem_name"] . " (No Remark)";
                            ?>
                                <small class="text-muted">- RM <?php echo $row["odr_detail_price"] ?> each</small>
                            <?php
                            }
                            ?>
                            <br />
                        <?php
                        }
                        ?>
                    </p>
                    <?php
                    ?>
                    <p>Total: RM <?php echo $total ?></p>
                <?php
                }
                ?>
            </div>
        </div>
        <section>
            <?php
            // $query = "SELECT o.*, SUM(od.odr_detail_amount*od.odr_detail_price) AS total_price FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id WHERE o.odr_id = {$odr_id};";
            // $result = $mysqli->query($query);
            // $rowcount = mysqli_num_rows($result);
            $query = $mysqli->prepare("SELECT o.*, SUM(od.odr_detail_amount*od.odr_detail_price) AS total_price FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id WHERE o.odr_id =?;");
            $query->bind_param('i', $odr_id);
            $query->execute();
            $result = $query->get_result();
            $rowcount = $result->num_rows;
            if ($rowcount > 0) {
                // $arr = $mysqli->query($query)->fetch_array();
                $arr = $result->fetch_array();
            ?>
                <div class="container py-5">
                    <div class="row d-flex justify-content-center align-items-center">
                        <div class="col">
                            <div class="card card-stepper" style="border-radius: 10px;">
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex flex-column">
                                            <?php switch ($arr['odr_status']) {
                                                case "CMPLT": ?>
                                                    <span class="lead fw-normal">Your order has been completed</span>
                                                    <span class="text-muted small">on <?php echo date("jS-M-Y H:ia", strtotime($arr['odr_compltime'])) ?></span>
                                                    <?php if ($arr['odr_rate_status'] == 0) { ?>
                                                        <div class="d-flex justify-content-between align-items-end">
                                                            <a href="rate.php?odr_id=<?php echo $odr_id ?>" class="btn btn-outline-primary me-1 my-1 btn-sm">Rate Experience</a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="text-muted small"> <b>Order has been rated</b>
                                                        </span>
                                                    <?php }
                                                    break;
                                                case "CXLD": ?>
                                                    <span class="lead fw-normal">Your order has been cancelled</span>
                                                    <span class="text-muted small">on <?php echo date("jS-M-Y H:ia", strtotime($arr['odr_cxldtime'])) ?></span>
                                                <?php break;
                                                case NULL: ?>
                                                    <span class="lead fw-normal">Invalid Order</span>
                                                <?php
                                                    break;
                                                case "RDFK":
                                                ?>
                                                    <span class="lead fw-normal">Your order is ready for pickup</span>
                                                <?php
                                                    break;
                                                default:
                                                ?>
                                                    <span class="lead fw-normal">Your order has been received</span>
                                                    <span class="text-muted small">on <?php echo date("jS-M-Y H:ia", strtotime($arr['odr_placedtime'])) ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <?php
                                    switch ($arr['odr_status']) {
                                        case "UNPD":
                                    ?>
                                            <div class="d-flex flex-row justify-content-between align-items-center align-content-center">
                                                <span class="dot"></span>
                                                <hr class="flex-fill track-line-disabled"><span class="dot-disabled"></span>
                                                <hr class="flex-fill track-line-disabled"><span class="dot-disabled"></span>

                                                <hr class="flex-fill track-line-disabled"><span class="dot-disabled"></span>
                                                <hr class="flex-fill track-line-disabled"><span class="dot-disabled"></span>
                                            </div>
                                            <div class="d-flex flex-row justify-content-between align-items-center">
                                                <div class="d-flex flex-column align-items-start"><span>Order Placed</span>
                                                </div>
                                                <div class="d-flex flex-column align-items-center"><span>Order Paid</span>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center"><span>Preparing
                                                        Order</span></div>
                                                <div class="d-flex flex-column justify-content-center align-items-center"><span>Ready for Pickup</span></div>
                                                <div class="d-flex flex-column align-items-end"><span>Order Completed</span></div>
                                            </div>
                                        <?php
                                            break;
                                        case "PREP":
                                        ?>
                                            <div class="d-flex flex-row justify-content-between align-items-center align-content-center">
                                                <span class="dot"></span>
                                                <hr class="flex-fill track-line"><span class="dot"></span>
                                                <hr class="flex-fill track-line"><span class="d-flex justify-content-center align-items-center big-dot dot">
                                                    <i class="fa fa-check text-white"></i></span>
                                                <hr class="flex-fill track-line-disabled"><span class="dot-disabled"></span>
                                                <hr class="flex-fill track-line-disabled"><span class="dot-disabled"></span>
                                            </div>
                                            <div class="d-flex flex-row justify-content-between align-items-center">
                                                <div class="d-flex flex-column align-items-start"><span>Order Placed</span>
                                                </div>
                                                <div class="d-flex flex-column align-items-center"><span>Order Paid</span><span>RM <?php echo $arr['total_price'] ?></span>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center"><span>Preparing
                                                        Order</span></div>
                                                <div class="d-flex flex-column justify-content-center align-items-center"><span>Ready for Pickup</span></div>
                                                <div class="d-flex flex-column align-items-end"><span>Order Completed</span></div>
                                            </div>
                                        <?php
                                            break;
                                        case "RDFK":
                                        ?>
                                            <div class="d-flex flex-row justify-content-between align-items-center align-content-center">
                                                <span class="dot"></span>
                                                <hr class="flex-fill track-line"><span class="dot"></span>
                                                <hr class="flex-fill track-line"><span class="dot"></span>
                                                <hr class="flex-fill track-line"><span class="d-flex justify-content-center align-items-center big-dot dot">
                                                    <i class="fa fa-check text-white"></i></span>
                                                <hr class="flex-fill track-line-disabled"><span class="dot-disabled"></span>
                                            </div>
                                            <div class="d-flex flex-row justify-content-between align-items-center">
                                                <div class="d-flex flex-column align-items-start"><span>Order Placed</span>
                                                </div>
                                                <div class="d-flex flex-column align-items-center"><span>Order Paid</span><span>RM <?php echo $arr['total_price'] ?></span>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center"><span>Preparing
                                                        Order</span></div>
                                                <div class="d-flex flex-column justify-content-center align-items-center"><span>Ready for Pickup</span></div>
                                                <div class="d-flex flex-column align-items-end"><span>Order Completed</span></div>
                                            </div>
                                        <?php
                                            break;
                                        case "CMPLT":
                                        ?>
                                            <div class="d-flex flex-row justify-content-between align-items-center align-content-center">
                                                <span class="dot"></span>
                                                <hr class="flex-fill track-line"><span class="dot"></span>
                                                <hr class="flex-fill track-line"><span class="dot"></span>
                                                <hr class="flex-fill track-line"><span class="dot"></span>
                                                <hr class="flex-fill track-line"><span class="d-flex justify-content-center align-items-center big-dot dot">
                                                    <i class="fa fa-check text-white"></i></span>
                                            </div>
                                            <div class="d-flex flex-row justify-content-between align-items-center">
                                                <div class="d-flex flex-column align-items-start"><span>Order Placed</span>
                                                </div>
                                                <div class="d-flex flex-column align-items-center"><span>Order Paid</span><span>RM <?php echo $arr['total_price'] ?></span>
                                                </div>
                                                <div class="d-flex flex-column justify-content-center"><span>Preparing
                                                        Order</span></div>
                                                <div class="d-flex flex-column justify-content-center align-items-center"><span>Ready for Pickup</span></div>
                                                <div class="d-flex flex-column align-items-end"><span>Order Completed</span></div>
                                            </div>
                                        <?php
                                            break;
                                        case "CXLD":
                                        ?>
                                            <div class="d-flex flex-row justify-content-between align-items-center align-content-center">
                                                <span class="dot-cancelled"></span>
                                                <hr class="flex-fill track-line-cancelled"><span class="d-flex justify-content-center align-items-center big-dot-cancelled dot-cancelled">
                                                    <i class="fa fa-x text-white"></i></span>
                                            </div>
                                            <div class="d-flex flex-row justify-content-between align-items-center">
                                                <div class="d-flex flex-column align-items-start"><span>Order Placed</span>
                                                </div>
                                                <div class="d-flex flex-column align-items-center"><span>Order Cancelled</span>
                                                </div>
                                            </div>
                                    <?php
                                            break;
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </section>
    </div>
    <?php include('footer.php'); ?>
</body>

</html>