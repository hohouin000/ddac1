<?php
session_start();
include('../conn_db.php');
if (!empty(($_POST['store_id']))) {
    $target_dir = '/img/store/';
    $store_id = $_POST['store_id'];
    $query = "SELECT store_pic FROM store WHERE store_id = '{$store_id}';";
    $result = $mysqli->query($query);
    $row = mysqli_fetch_array($result);
    $target_file = $target_dir . $row['store_pic'];
    unlink(SITE_ROOT . $target_file);

    $query = "DELETE FROM store WHERE store_id = '{$store_id}';";
    $result = $mysqli->query($query);
    $response['server_status'] = 1;
} else {
    $response['server_status'] = 0;
}
echo json_encode($response);
