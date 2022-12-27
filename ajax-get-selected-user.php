<?php
session_start();
include('conn_db.php');
if (isset($_POST['user_id'])) {
    $user_id = mysqli_real_escape_string($mysqli, $_POST['user_id']);
    // $query = "SELECT * FROM user  WHERE user_id = '{$user_id}';";
    // $result = $mysqli->query($query);
    // $rowcount = mysqli_num_rows($result);
    $query = $mysqli->prepare("SELECT * FROM user  WHERE user_id =?;");
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();
    $rowcount = $result->num_rows;

    if ($rowcount > 0) {
        while ($row = $result->fetch_assoc()) {
            $array = [
                "user_username" => $row['user_username'],
                "user_fname" => $row['user_fname'],
                "user_lname" => $row['user_lname'],
                "user_email" => $row['user_email'],
                "user_role" => $row['user_role'],
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
            "server_status" => 0
        ];
    }
    echo json_encode($array);
} else {
    $array = [
        "user_username" => '',
        "user_fname" => '',
        "user_lname" => '',
        "user_email" => '',
        "user_role" => '',
        "server_status" => 0
    ];
    echo json_encode($array);
}
