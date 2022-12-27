<?php
session_start();
include('../conn_db.php');
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}
$mitem_id = $_POST['mitem_id'];
$query = "SELECT * FROM mitem  WHERE mitem_id = '{$mitem_id}' LIMIT 0,1;";
$result = $mysqli->query($query);
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) {
    while ($row = $result->fetch_array()) {
        $array = [
            "mitem_id" => $row['mitem_id'],
            "mitem_name" => $row['mitem_name'],
            "mitem_price" => $row['mitem_price'],
            "mitem_status" => $row['mitem_status'],
            "mitem_pic" => $row['mitem_pic'],
            "server_status" => 1
        ];
    }
} else {
    $array = [
        "mitem_id" => "",
        "mitem_name" => "",
        "mitem_price" => "",
        "mitem_status" => "",
        "mitem_pic" => "",
        "server_status" => 0
    ];
}
echo json_encode($array);
