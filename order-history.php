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

    <title>Order History</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('nav.php') ?>
    <div class="container px-5 py-4">
        <div class="container p-2 pb-0 mt-5 pt-3">
            <h2 class="pt-3 display-6">Order History</h2>
            <?php
            if (isset($_SESSION['server_status'])) {
                if ($_SESSION['server_status'] == 1) {
            ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3 mb-0" role="alert">
                        Rating Added Successfully !
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['server_status']);
                } else if ($_SESSION['server_status'] == -1) {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show mt-3 mb-0" role="alert">
                        Rating Existed !
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php unset($_SESSION['server_status']);
                } else {
                ?>
                    <div class="alert alert-warning alert-dismissible fade show mt-3 mb-0" role="alert">
                        Failed to Add Rating !
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
            <?php unset($_SESSION['server_status']);
                }
            }
            ?>
            <nav class="mt-5">
                <div class="nav nav-pills flex-wrap mb-3" id="pills-tab" role="tablist">
                    <button class="nav-link active px-4" id="ongoing-tab" data-bs-toggle="tab" data-bs-target="#nav-ongoing" type="button" role="tab" aria-controls="nav-ongoing" aria-selected="true">Ongoing</button>
                    <button class="nav-link px-4" id="cmplt-tab" data-bs-toggle="tab" data-bs-target="#nav-cmplt" type="button" role="tab" aria-controls="nav-cmplt" aria-selected="false">Completed</button>
                    <button class="nav-link px-4" id="cxld-tab" data-bs-toggle="tab" data-bs-target="#nav-cxld" type="button" role="tab" aria-controls="nav-cxld" aria-selected="false">Cancelled</button>
                </div>
            </nav>
        </div>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-ongoing" role="tabpanel" aria-labelledby="ongoing-tab">
                <div class="container pt-3">
                    <div class="row">
                        <?php
                        // $query = "SELECT o.odr_placedtime, o.odr_id, o.odr_ref, s.store_name, COUNT(m.mitem_id) as 'itemcount' FROM odr o INNER JOIN store s on o.store_id = s.store_id INNER JOIN odr_detail od on o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE odr_status NOT IN ('CMPLT','CXLD') AND user_id = {$_SESSION['user_id']} GROUP BY odr_ref ORDER BY odr_placedtime DESC;";
                        // $result = $mysqli->query($query);
                        // $rowcount = mysqli_num_rows($result);
                        $query = $mysqli->prepare("SELECT o.odr_placedtime, o.odr_id, o.odr_ref, COUNT(m.mitem_id) as 'itemcount' FROM odr o INNER JOIN odr_detail od on o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE odr_status NOT IN ('CMPLT','CXLD') AND user_id =? GROUP BY odr_ref ORDER BY odr_placedtime DESC;");
                        $query->bind_param('i', $_SESSION['user_id']);
                        $query->execute();
                        $result = $query->get_result();
                        $rowcount = $result->num_rows;
                        if ($rowcount > 0) {
                            while ($row = $result->fetch_array()) {
                        ?>
                                <div class="card border-info mb-3">
                                    <div class="card-body">
                                        <h5 class="card-title">Order Ref: <?php echo $row['odr_ref'] ?></h5>
                                        <p class="card-text"><?php echo $row['itemcount'] ?> Item(s) <br />Store: Rong Sheng’s Famous Pastries and Cake<br />Order Placed: <?php echo date("jS-M-Y H:ia", strtotime($row['odr_placedtime'])) ?></p>
                                        <a href="order-details.php?odr_id=<?php echo $row['odr_id'] ?>" class="btn btn-outline-info">View Details</a>
                                    </div>
                                </div>
                            <?php
                            }
                        } else {
                            ?>
                            <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
                                    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                </symbol>
                            </svg>
                            <div class="alert alert-warning d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:" width="24" height="24">
                                    <use xlink:href="#exclamation-triangle-fill" />
                                </svg>
                                <div>
                                    No order has been made yet.
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-cmplt" role="tabpanel" aria-labelledby="cmplt-tab">
                <div class="container pt-2">
                    <div class="container pt-3">
                        <div class="row">
                            <?php
                            // $query = "SELECT o.odr_compltime, o.odr_id, o.odr_ref, s.store_name, COUNT(m.mitem_id) as 'itemcount' FROM odr o INNER JOIN store s on o.store_id = s.store_id INNER JOIN odr_detail od on o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE odr_status = 'CMPLT' AND user_id = {$_SESSION['user_id']} GROUP BY odr_ref ORDER BY odr_compltime DESC;";
                            // $result = $mysqli->query($query);
                            // $rowcount = mysqli_num_rows($result);
                            $query = $mysqli->prepare("SELECT o.odr_compltime, o.odr_id, o.odr_ref,  COUNT(m.mitem_id) as 'itemcount' FROM odr o INNER JOIN odr_detail od on o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE odr_status = 'CMPLT' AND user_id =? GROUP BY odr_ref ORDER BY odr_compltime DESC;");
                            $query->bind_param('i', $_SESSION['user_id']);
                            $query->execute();
                            $result = $query->get_result();
                            $rowcount = $result->num_rows;
                            if ($rowcount > 0) {
                                while ($row = $result->fetch_array()) {
                            ?>
                                    <div class="card border-success mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Order Ref: <?php echo $row['odr_ref'] ?></h5>
                                            <p class="card-text"><?php echo $row['itemcount'] ?> Item(s) <br />Store: <?php echo $row['store_name'] ?><br />Order Completed: <?php echo date("jS-M-Y H:ia", strtotime($row['odr_compltime'])) ?></p>
                                            <a href="order-details.php?odr_id=<?php echo $row['odr_id'] ?>" class="btn btn-outline-success">View Details</a>
                                        </div>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                    </symbol>
                                </svg>
                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:" width="24" height="24">
                                        <use xlink:href="#exclamation-triangle-fill" />
                                    </svg>
                                    <div>
                                        No completed order(s).
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-cxld" role="tabpanel" aria-labelledby="cxld-tab">
                <div class="container pt-2">
                    <div class="container pt-3">
                        <div class="row">
                            <?php
                            // $query = "SELECT o.odr_cxldtime, o.odr_id, o.odr_ref, s.store_name, COUNT(m.mitem_id) as 'itemcount' FROM odr o INNER JOIN store s on o.store_id = s.store_id INNER JOIN odr_detail od on o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE odr_status = 'CXLD' AND user_id = {$_SESSION['user_id']} GROUP BY odr_ref ORDER BY odr_cxldtime DESC;";
                            // $result = $mysqli->query($query);
                            // $rowcount = mysqli_num_rows($result);
                            $query = $mysqli->prepare("SELECT o.odr_cxldtime, o.odr_id, o.odr_ref, COUNT(m.mitem_id) as 'itemcount' FROM odr o INNER JOIN odr_detail od on o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE odr_status = 'CXLD' AND user_id =? GROUP BY odr_ref ORDER BY odr_cxldtime DESC;");
                            $query->bind_param('i', $_SESSION['user_id']);
                            $query->execute();
                            $result = $query->get_result();
                            $rowcount = $result->num_rows;
                            if ($rowcount > 0) {
                                while ($row = $result->fetch_array()) {
                            ?>
                                    <div class="card border-danger mb-3">
                                        <div class="card-body">
                                            <h5 class="card-title">Order Ref: <?php echo $row['odr_ref'] ?></h5>
                                            <p class="card-text"><?php echo $row['itemcount'] ?> Item(s) <br />Store: <?php echo $row['store_name'] ?><br />Order Cancelled: <?php echo date("jS-M-Y H:ia", strtotime($row['odr_cxldtime'])) ?></p>
                                            <a href="order-details.php?odr_id=<?php echo $row['odr_id'] ?>" class="btn btn-outline-danger">View Details</a>
                                        </div>
                                    </div>
                                <?php
                                }
                            } else {
                                ?>
                                <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                                    <symbol id="exclamation-triangle-fill" viewBox="0 0 16 16">
                                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
                                    </symbol>
                                </svg>
                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Warning:" width="24" height="24">
                                        <use xlink:href="#exclamation-triangle-fill" />
                                    </svg>
                                    <div>
                                        No cancelled order(s).
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include('footer.php');
    include('toast-message.php');
    ?>
</body>

</html>