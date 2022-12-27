<?php session_start();
include("../conn_db.php");

$store_name = $_POST['store_name'];
$store_location = $_POST['store_location'];
$store_openhour = $_POST['store_openhour'];
$store_closehour = $_POST['store_closehour'];
$store_status = $_POST['store_status'];
$store_id = $_POST['store_id'];

$query = "UPDATE store SET store_name = '{$store_name}', store_location = '{$store_location}', store_openhour = '{$store_openhour}', 
store_closehour = '{$store_closehour}', store_status = {$store_status} WHERE store_id = {$store_id};";
$result = $mysqli->query($query);
if ($result) {
    $response['server_status'] = 1;
} else {
    $response['server_status'] = 0;
}
echo json_encode($response);
