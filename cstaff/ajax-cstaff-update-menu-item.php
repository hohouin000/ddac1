<?php session_start();
include("../conn_db.php");
if ($_SESSION["user_role"] != "CSTAFF") {
    header("location:../restricted.php");
    exit(1);
}

$mitem_name = $_POST['mitem_name'];
$mitem_price = $_POST['mitem_price'];
$mitem_status = $_POST['mitem_status'];
$mitem_id = $_POST['mitem_id'];
$query = "UPDATE mitem SET mitem_name = '{$mitem_name}', mitem_price = '{$mitem_price}', mitem_status = '{$mitem_status}'
WHERE mitem_id = {$mitem_id}";
$result = $mysqli->query($query);
if ($result) {
    $response['server_status'] = 1;
} else {
    $response['server_status'] = 0;
}
echo json_encode($response);
