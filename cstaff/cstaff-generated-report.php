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
    if (isset($_POST["start_date"]) && (isset($_POST["end_date"])) ) {
        $start_date = $_POST["start_date"];
        $end_date = $_POST["end_date"];
        
    } else {
        header("location: cstaff-report-generation.php");
        exit(1);
    }
    ?>
    
    <style>
        @media print {
            canvas.mpChart {
                min-height: 100%;
                max-width: 100%;
                max-height: 100%;
                height: auto !important;
                width: auto !important;
            }

            .admin-dashboard {
                display: none !important;
            }

            .cstaff-nav {
                display: none !important;
            }
        }
    </style>
    <title>Report Summary | Cafeteria Staff</title>
</head>

<body class="d-flex flex-column">
    <?php include('cstaff-nav.php');
    $formatted_start_date = date("jS-M-Y", strtotime($start_date));
    $formatted_end_date = date("jS-M-Y", strtotime($end_date));
    ?>
    <div class="container admin-dashboard p-2 pb-0 mt-5 pt-3" id="admin-dashboard">
        <h2 class="pt-3 pb-5 display-6">Report Summary</h2>
        <a class=" nav nav-item text-decoration-none text-muted" href="cstaff-report-generation.php">
            <i class="bi bi-arrow-left-square me-2"></i>Go back</a>
        <div class="row g-2 mb-5  justify-content-md-end">
            <div class="col-auto">
                <button type="button" onclick="window.print()" class="btn btn-outline-primary" style="--bs-btn-padding-y: .5rem; --bs-btn-padding-x: 1rem; --bs-btn-font-size: .75rem;">Print</button>
            </div>
        </div>
    </div>
    <div class="container pb-0" id="div-report">
        <div ALIGN=CENTER>
            <b>
                <h6>Report Summary Generated on <?php echo date("jS-M-Y H:ia") ?>
            </b>
        </div>
        <br />
        <div ALIGN=CENTER>
            <p><?php
                if ($formatted_start_date == $formatted_end_date) {
                    echo "Date of Report: " . "<b>" .  $formatted_start_date . "</b>";
                } else {
                    echo "Date of Report: From " . "<b>" . $formatted_start_date . "</b>" . " to " . "<b>" . $formatted_end_date . "</b>";
                } ?>
            </p>
        </div>
        <div class="row row-cols-2 row-cols-md-3 g-3 py-3">
            <div class="col">
                <div class="card border-info">
                    <div class="card-body">
                        <h6 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cash" viewBox="0 0 16 16">
                                <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z" />
                                <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z" />
                            </svg>
                            Total Sales Revenue
                        </h6>
                        <p class="card-text my-2">
                            <?php
                            $query = "SELECT SUM(od.odr_detail_amount*od.odr_detail_price) AS  total_sales_revenue FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id
                                WHERE odr_status = 'CMPLT' AND (DATE(odr_compltime) BETWEEN DATE('{$start_date}') AND DATE('{$end_date}'));";
                            $result = $mysqli->query($query);
                            $row = $result->fetch_array();
                            if ($row["total_sales_revenue"] != NULL) {
                                echo "RM " . $row["total_sales_revenue"];
                            } else {
                                echo "RM 0.00";
                            }
                            ?>
                        </p>

                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-info">
                    <div class="card-body">
                        <h6 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                                <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27zm.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0l-.509-.51z" />
                                <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5z" />
                            </svg>
                            Total Number of Order Placed
                        </h6>
                        <p class="card-text my-2">
                            <?php
                            $query = "SELECT COUNT(*) AS number_of_order_placed FROM odr o
                            WHERE odr_status = 'CMPLT' AND (DATE(odr_compltime) BETWEEN DATE('{$start_date}') AND DATE('{$end_date}'));";
                            $result = $mysqli->query($query);
                            $row = $result->fetch_array();
                            if ($row["number_of_order_placed"] != 0) {
                                echo $row["number_of_order_placed"] . " Order(s) Placed";
                            } else {
                                echo "0 Order Placed";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-info">
                    <div class="card-body">
                        <h6 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-person-check" viewBox="0 0 16 16">
                                <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H1s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C9.516 10.68 8.289 10 6 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                <path fill-rule="evenodd" d="M15.854 5.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 0 1 .708-.708L12.5 7.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                            </svg>
                            Total Number of Customer Visited
                        </h6>
                        <p class="card-text my-2">
                            <?php
                            $query = "SELECT COUNT(DISTINCT o.user_id) AS number_of_customer FROM odr o 
                              WHERE odr_status = 'CMPLT' AND (DATE(odr_compltime) BETWEEN DATE('{$start_date}') AND DATE('{$end_date}'));";
                            $result = $mysqli->query($query);
                            $row = $result->fetch_array();
                            if ($row["number_of_customer"] != 0) {
                                echo $row["number_of_customer"] . " Customer(s) Visited";
                            } else {
                                echo "0 Customer Visited";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-info">
                    <div class="card-body">
                        <h6 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-diagram-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H11a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 5 7h2.5V6A1.5 1.5 0 0 1 6 4.5v-1zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1zM3 11.5A1.5 1.5 0 0 1 4.5 10h1A1.5 1.5 0 0 1 7 11.5v1A1.5 1.5 0 0 1 5.5 14h-1A1.5 1.5 0 0 1 3 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1A1.5 1.5 0 0 1 9 12.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z" />
                            </svg>
                            Best-Selling Menu Item
                        </h6>
                        <p class="card-text my-2">
                            <?php
                            $query = "SELECT m.mitem_name as menu_item, SUM(od.odr_detail_amount) as total FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE odr_status = 'CMPLT' AND (DATE(odr_compltime) BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')) GROUP BY mitem_name ORDER BY total DESC LIMIT 1;";
                            $result = $mysqli->query($query);
                            $row = $result->fetch_array();
                            if (isset($row["menu_item"])) {
                                echo $row["menu_item"];
                            } else {
                                echo "None";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card border-info">
                    <div class="card-body">
                        <h6 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-hourglass-split" viewBox="0 0 16 16">
                                <path d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1h-11zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2h-7zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48V8.35zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z" />
                            </svg>
                            Peak Hour
                        </h6>
                        <p class="card-text my-2">
                            <?php
                            $query = "SELECT EXTRACT(HOUR from o.odr_placedtime) as peak_hour, count(*) as cnt FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE odr_status = 'CMPLT' AND (DATE(odr_compltime) BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')) GROUP BY EXTRACT(HOUR from o.odr_placedtime) ORDER BY count(*) DESC LIMIT 1;";
                            $result = $mysqli->query($query);
                            $row = $result->fetch_array();
                            if (isset($row["peak_hour"])) {
                                $time = $row['peak_hour'] . ":00";
                                $time2 = $row['peak_hour'] . ":59";
                                echo date('H:ia', strtotime($time)) . " - " . date('H:ia', strtotime($time2));
                            } else {
                                echo "None";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <br />
        <h4 class="border-top fw-light pt-3 mt-2">Menu Performance</h4>
        <div id="div-mpMessage">
            <p class="fw-light">None</p>
        </div>
        <div class="container p-2 pb-0 mt-2 mb-5 pt-3" style="text-align: center;" id="div-mpChart">
            <canvas id="mpChart"></canvas>
        </div>
    </div>

    <?php include('../footer.php'); ?>
    <?php include("../toast-message.php"); ?>
    <?php
    $query = "SELECT m.mitem_name, SUM(od.odr_detail_amount) AS total_amount, SUM(od.odr_detail_amount*od.odr_detail_price) AS sub_total FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE odr_status = 'CMPLT' AND (DATE(odr_compltime) BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')) GROUP BY mitem_name ORDER BY total_amount, sub_total;";
    $result = $mysqli->query($query);
    $rowcount = mysqli_num_rows($result);
    print_r($rowcount);
    if ($rowcount > 0) {
        while ($row = $result->fetch_array()) {
            $mitem_name[] = $row['mitem_name'];
            $total_amount[] = $row['total_amount'];
            $sub_total[] = $row['sub_total'];
        }
    ?>
        <style type="text/css">
            #div-mpMessage {
                display: none;
            }
        </style>
    <?php
    } else {
        $mitem_name = '';
        $total_amount = '';
        $sub_total = '';
    ?>
        <style type="text/css">
            #div-mpChart {
                display: none;
            }
        </style>

    <?php
    }
    ?>
    <script>
        const legendMargin = {
            id: 'legendMargin',
            beforeInit(chart, legend, options) {
                const fitValue = chart.legend.fit;

                chart.legend.fit = function fit() {
                    fitValue.bind(chart.legend)();
                    return this.height += 30;
                }

            }

        }
        const labels = <?php echo json_encode($mitem_name) ?>;
        const data = {
            labels: labels,
            datasets: [{
                    label: 'Total Sales (RM)',
                    data: <?php echo json_encode($sub_total) ?>,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(93, 190, 255, 0.3)',
                    //stack: 'combined',
                    type: 'bar',
                },
                {
                    label: 'Total Amount Sold',
                    data: <?php echo json_encode($total_amount) ?>,
                    borderColor: 'rgb(255, 99, 132)',
                    pointBackgroundColor: 'rgb(255, 255, 255)',
                    backgroundColor: 'rgba(255, 99, 132, 0.3)',
                    //stack: 'combined',
                    type: 'bar',
                },
            ]
        };

        const config = {
            type: 'bar',
            data: data,
            options: {
                plugins: {
                    datalabels: {
                        formatter: (value, context) => context.datasetIndex === 1 ? value : '',
                        backgroundColor: function(context) {
                            return context.dataset.backgroundColor;
                        },
                        borderRadius: 3,
                        color: 'black',
                        font: {
                            weight: 'bold'
                        },
                        formatter: Math.round,
                        padding: 2,
                        align: 'end',
                        anchor: 'center',
                    },
                    title: {
                        display: true,
                        text: 'Menu Performance Chart',
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                elements: {
                    line: {
                        fill: false,
                        tension: 0.4
                    }
                },
                layout: {
                    padding: {
                        top: 35,
                        right: 16,
                        bottom: 10,
                        left: 8
                    }
                },
            },
            plugins: [ChartDataLabels, legendMargin],
        };

        const myChart = new Chart(
            document.getElementById('mpChart'),
            config
        );

        function beforePrintHandler() {
            for (let id in Chart.instances) {
                Chart.instances[id].resize();
            }
        }

        window.addEventListener('beforeprint', () => {
            myChart.resize(1000, 1000);
        });

        window.addEventListener('afterprint', () => {
            myChart.resize();
        });
    </script>
</body>

</html>