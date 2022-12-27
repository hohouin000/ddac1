<?php
session_start();
include("../conn_db.php");

$query = "SELECT * FROM store;";
$result = $mysqli->query($query);
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) {
    $i = 1;
    while ($row = $result->fetch_array()) {
        if ($row['store_status'] == 1) {
            $store_status = "Open For Today";
        } else {
            $store_status = "Not Open For Today";
        }
        $data[] = [
            "row" => $i++,
            "store_id" => $row['store_id'],
            "store_name" => $row['store_name'],
            "store_location" => $row['store_location'],
            "store_openhour" => $row['store_openhour'],
            "store_closehour" => $row['store_closehour'],
            "store_status" => $store_status,
            "store_pic" => $row['store_pic']
        ];
    }
} else {
    $data[] = [
        "row" => '',
        "store_id" => '',
        "store_name" => '',
        "store_location" => '',
        "store_openhour" => '',
        "store_closehour" => '',
        "store_status" => '',
        "store_pic" => ''
    ];
}
$return_array = array('data' => $data);
$jsonData = json_encode($return_array);
echo $jsonData . "\n";
