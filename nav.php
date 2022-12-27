<!--    NAV For Customer UI-->
<nav class="navbar cstaff-nav navbar-expand-md navbar-light fixed-top bg-light shadow-sm mb-auto">
    <div class="container-fluid mx-4">
        <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarCollapse">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <a class="nav-link px-2 text-dark" href="index.php">Home</a>
                </li>
               
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == "CUST") { ?>
                    <li class="nav-item">
                        <a href="order-history.php" class="nav-link px-2 text-dark">Order History</a>
                    </li>
                <?php } ?>
            </ul>
            <div class="d-flex">
                <?php if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) { ?>
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a class="mx-2 mt-1 mt-md-0 btn btn-outline-success" href="signup.php">Register/Sign Up</a>
                        </li>
                        <li class="nav-item">
                            <a class="mx-2 mt-1 mt-md-0 btn btn-outline-login" href="login.php">Login</a>
                        </li>
                    </ul>
                <?php } else { ?>
                    <ul class="navbar-nav me-auto mb-2 mb-md-0">
                        <li class="nav-item">
                            <a type="button" class="btn btn-light position-relative" href="cart.php">
                                Cart
                                <?php
                                $query = "SELECT COUNT(mitem_id) AS cart_total FROM cart WHERE user_id = {$_SESSION['user_id']}";
                                $result = $mysqli->query($query);
                                $row = $result->fetch_array();
                                $cart_total = $row["cart_total"];
                                if ($cart_total > 0) {
                                ?>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-info" style="margin-top: 10%;">
                                    <?php echo $cart_total;
                                } else {
                                    ?>
                                    </span>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary" style="margin-top: 10%;">0</span>
                                <?php } ?>

                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="profile.php" class="nav-link px-2 text-dark">Welcome back, <?php echo " " .  $_SESSION["user_fname"] ?></a>
                        </li>
                        <li class="nav-item">
                            <a class="mx-2 mt-1 mt-md-0 btn btn-outline-danger" href="logout.php">Log Out</a>
                        </li>
                    <?php } ?>
                    </ul>
            </div>
        </div>
    </div>
</nav>