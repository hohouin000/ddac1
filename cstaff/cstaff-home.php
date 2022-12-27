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
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Home | Cafeteria Staff</title>
</head>

<body class="d-flex flex-column h-100">
    <?php include('cstaff-nav.php') ?>

    <div class="container p-5" id="admin-dashboard" style="margin-top:5%;">
        <h3 class="border-bottom pb-2"><svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2 13.5V7h1v6.5a.5.5 0 0 0 .5.5h9a.5.5 0 0 0 .5-.5V7h1v6.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5zm11-11V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z" />
                <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z" />
            </svg> Welcome Back, <?php echo $_SESSION["user_fname"] . ' ' . $_SESSION["user_lname"] ?></h3>
            


                  <!-- GRID OF STORE -->
      <div class="col">
        <div class="card border-info p-2">
          <div class="card-body">
            <h4 class="card-title">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
              </svg>
              Customer
            </h4>
            <p class="card-text my-2">
              <span class="h6">
                <?php
                $query = "SELECT COUNT(*) AS userCount FROM user;";
                $result = $mysqli->query($query)->fetch_array();
                echo $result["userCount"];
                ?>
              </span>
              User(s) Registered in the System
            </p>
            <div class="text-end">
              <a href="../admin/admin-mng-user.php" class="btn btn-sm btn-outline-dark">Go to User Management</a>
            </div>
          </div>
        </div>
      </div>
        <!-- DASHBOARD -->
        <div class="row row-cols-1 row-cols-lg-2 align-items-stretch g-4 py-3">

            <div class="col">
                <div class="card border-info p-2">
                    <div class="card-body">
                        <h4 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                                <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z" />
                                <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0zM7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0z" />
                            </svg>
                            Order
                        </h4>
                        <p class="card-text my-2">
                            <span class="h6">
                                <?php
                                $query = "SELECT COUNT(*) AS orderCount FROM odr WHERE odr_status NOT IN ('CXLD','CMPLT');";
                                $result = $mysqli->query($query)->fetch_array();
                                echo $result["orderCount"];
                                ?>
                            </span>
                            Order(s) Placed in the System
                        </p>
                        <div class="text-end">
                            <a href="cstaff-mng-order.php" class="btn btn-sm btn-outline-dark">Go to Order Management</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-success p-2">
                    <div class="card-body">
                        <h4 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-menu-up" viewBox="0 0 16 16">
                                <path d="M7.646 15.854a.5.5 0 0 0 .708 0L10.207 14H14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h3.793l1.853 1.854zM1 9V6h14v3H1zm14 1v2a1 1 0 0 1-1 1h-3.793a1 1 0 0 0-.707.293l-1.5 1.5-1.5-1.5A1 1 0 0 0 5.793 13H2a1 1 0 0 1-1-1v-2h14zm0-5H1V3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v2zM2 11.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 0-1h-8a.5.5 0 0 0-.5.5zm0-4a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 0-1h-11a.5.5 0 0 0-.5.5zm0-4a.5.5 0 0 0 .5.5h6a.5.5 0 0 0 0-1h-6a.5.5 0 0 0-.5.5z" />
                            </svg>
                            Pastry 
                        </h4>
                        <p class="card-text my-2">
                            <span class="h6">
                                <?php
                                $query = "SELECT COUNT(*) AS itemCount FROM mitem ;";
                                $result = $mysqli->query($query)->fetch_array();
                                echo $result["itemCount"];
                                ?>
                            </span>
                            Pastry (s) Available in the System
                        </p>
                        <div class="text-end">
                            <a href="cstaff-mng-menu.php" class="btn btn-sm btn-outline-dark">Go to Pastry Management</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card border-success p-2">
                    <div class="card-body">
                        <div>
                            <canvas id="rdsoChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col">
                <div class="card border-success p-2">
                    <div class="card-body">
                        <div>
                            <canvas id="bsmiChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- DASHBOARD -->

    </div>
    <?php include('../footer.php'); ?>

    <!-- Get Recent Sales Orders Results -->
    <?php
    $query = "SELECT date_format(o.odr_compltime, '%e%b%Y') AS date_revenue, SUM(od.odr_detail_amount*od.odr_detail_price) AS menu_revenue FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id WHERE o.odr_status = 'CMPLT' GROUP BY YEAR(odr_compltime), Month(odr_compltime), Day(odr_compltime) ORDER BY (odr_compltime) DESC LIMIT 5;";

    $result = $mysqli->query($query);
    $rowcount = mysqli_num_rows($result);
    if ($rowcount > 0) {
        while ($row = $result->fetch_array()) {
            $dates[] = $row['date_revenue'];
            $sales[] = $row['menu_revenue'];
        }

        $dates = array_reverse($dates);
        $sales = array_reverse($sales);
    } else {
        $dates = '';
        $sales = '';
    }
    ?>
    <!-- END OF Get Recent Sales Orders Results -->

    <!-- Get Top Selling Items Results -->
    <?php
    $query2 = "SELECT m.mitem_name AS menu_item, SUM(od.odr_detail_amount) AS total_volume FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id INNER JOIN mitem m ON m.mitem_id = od.mitem_id WHERE o.odr_status = 'CMPLT' GROUP BY od.mitem_id ORDER BY Total_Volume DESC LIMIT 3;";

    $result2 = $mysqli->query($query2);
    $rowcount2 = mysqli_num_rows($result2);

    if ($rowcount2 > 0) {
        while ($row2 = $result2->fetch_array()) {
            $menu_item[] = $row2['menu_item'];
            $total_volume[] = $row2['total_volume'];
        }
    } else {
        $menu_item = '';
        $total_volume = '';
    }
    ?>
    <!-- END OF Get Top Selling Items Results -->


    <!--Line Chart ChartJS Init-->
    <script>
        const labels = <?php echo json_encode($dates) ?>;
        const data = {
            labels: labels,
            datasets: [{
                label: 'Recent Daily Sales',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgb(255, 99, 132)',
                pointBackgroundColor: 'rgb(255, 255, 255)',
                pointRadius: 3.5,
                pointHoverRadius: 3.5,
                data: <?php echo json_encode($sales) ?>,
                fill: true,
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                scales: {
                    y: {
                        min: 0,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return "RM" + value;
                            }
                        }
                    }
                },
                elements: {
                    line: {
                        fill: false,
                        tension: 0.4
                    }
                },
                hitRadius: 30,
            },
        };

        const rdsoChart = new Chart(
            document.getElementById('rdsoChart'),
            config
        );
    </script>
    <!--END OF Line ChartJS Init-->

    <!--Bar Chart ChartJS Init-->
    <script>
        const labels2 = <?php echo json_encode($menu_item) ?>;
        const data2 = {
            labels: labels2,
            datasets: [{
                axis: 'y',
                label: 'Top Selling Menu Items',
                data: <?php echo json_encode($total_volume) ?>,
                fill: false,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(201, 203, 207, 0.2)'
                ],
                borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 159, 64)',
                    'rgb(255, 205, 86)',
                    'rgb(75, 192, 192)',
                    'rgb(54, 162, 235)',
                    'rgb(153, 102, 255)',
                    'rgb(201, 203, 207)'
                ],
                // borderWidth: 1,
                borderWidth: 2,
                borderRadius: 5,
                borderSkipped: false,
            }]
        };
        const config2 = {
            type: 'bar',
            data: data2,
            options: {
                indexAxis: 'y',
            }
        };

        const bsmiChart = new Chart(
            document.getElementById('bsmiChart'),
            config2
        );
    </script>
    <!--END OF Bar ChartJS Init-->
</body>

</html>