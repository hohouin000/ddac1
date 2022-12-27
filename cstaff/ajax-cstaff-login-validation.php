<?php
session_start();
include('../conn_db.php');
$user_username = $_POST["user_username"];
$user_pwd = $_POST["user_pwd"];

$query = "SELECT * FROM user WHERE
    user_username = '$user_username' AND user_pwd = '$user_pwd' AND user_role = 'CSTAFF' LIMIT 0,1";

$result = $mysqli->query($query);
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
    $row = $result->fetch_array();
    $_SESSION["user_id"] = $row["user_id"];
    $_SESSION["user_fname"] = $row["user_fname"];
    $_SESSION["user_lname"] = $row["user_lname"];
    $_SESSION["user_role"] = $row["user_role"];
    // $_SESSION["store_id"] = $row["store_id"];
    $response['server_status'] = 1;
} else {
    $response['server_status'] = 0;
}
echo json_encode($response);
