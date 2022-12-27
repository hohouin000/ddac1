<?php
session_start();
include('../conn_db.php');
    $store_id = $_POST['store_id'];
    $query = "SELECT * FROM store  WHERE store_id = '{$store_id}';";
    $result = $mysqli->query($query);
    $rowcount = mysqli_num_rows($result);

    if ($rowcount > 0) {
        while ($row = $result->fetch_array()) {
            $array = [
                "store_id" => $row['store_id'],
                "store_name" => $row['store_name'],
                "store_location" => $row['store_location'],
                "store_openhour" => $row['store_openhour'],
                "store_closehour" => $row['store_closehour'],
                "store_status" => $row['store_status'],
                "store_pic" => $row['store_pic'],
                "server_status" => 1
            ];
        }
    } else {
        $array = [
            "store_id" => '',
            "store_name" => '',
            "store_location" => '',
            "store_openhour" => '',
            "store_closehour" => '',
            "store_status" => '',
            "store_pic" => '',
            "server_status" => 0
        ];
    }
    echo json_encode($array);
