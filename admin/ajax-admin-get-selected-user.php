<?php
session_start();
include('../conn_db.php');
$user_id = $_POST['user_id'];
$query = "SELECT * FROM user  WHERE user_id = '{$user_id}';";
$result = $mysqli->query($query);
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) {
    while ($row = $result->fetch_array()) {
        $array = [
            "user_username" => $row['user_username'],
            "user_fname" => $row['user_fname'],
            "user_lname" => $row['user_lname'],
            "user_email" => $row['user_email'],
            "user_role" => $row['user_role'],
            "user_pwd" => $row['user_pwd'],
            "server_status" => 1
        ];
    }
} else {
    $array = [
        "user_username" => '',
        "user_fname" => '',
        "user_lname" => '',
        "user_email" => '',
        "user_role" => '',
        "user_pwd" => '',
        "server_status" => 0
    ];
}
echo json_encode($array);
