<?php
session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}

$query = "SELECT * FROM mitem";
$result = $mysqli->query($query);
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) {
    $i = 1;
    while ($row = $result->fetch_array()) {
        if ($row['mitem_status'] == 1) {
            $mitem_status = "Available";
        } else {
            $mitem_status = "Not Available";
        }
        $mitem_price = "RM " . $row['mitem_price'];
        $data[] = [
            "row" => $i++,
            "mitem_id" => $row['mitem_id'],
            "mitem_name" => $row['mitem_name'],
            "mitem_price" => $mitem_price,
            "mitem_status" => $mitem_status,
            "mitem_pic" => $row['mitem_pic']
        ];
    }
} else {
    $data[] = [
        "row" => '',
        "mitem_id" => '',
        "mitem_name" => '',
        "mitem_price" => '',
        "mitem_status" => '',
        "mitem_pic" => ''
    ];
}
$return_array = array('data' => $data);
$jsonData = json_encode($return_array);
echo $jsonData . "\n";
