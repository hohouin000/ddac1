<?php
session_start();
include('../conn_db.php');
if (!empty(($_POST['user_id']))) {
    $user_id = $_POST['user_id'];
    $query = "DELETE FROM user WHERE user_id = '{$user_id}';";
    $result = $mysqli->query($query);
    $response['server_status'] = 1;
} else {
    $response['server_status'] = 0;
}
echo json_encode($response);
