<?php
session_start();
include('conn_db.php');
if (isset($_POST['user_username'], $_POST['user_pwd'])) {
    $user_pwd = mysqli_real_escape_string($mysqli, $_POST['user_pwd']);
    $user_username = mysqli_real_escape_string($mysqli, $_POST['user_username']);

    // $query = "SELECT * FROM user WHERE
    // user_username = '$user_username' AND user_pwd = '$user_pwd' AND user_role = 'CUST' LIMIT 0,1";
    // $result = $mysqli->query($query);
    // $rowcount = mysqli_num_rows($result);
    $query = $mysqli->prepare("SELECT * FROM user WHERE user_username =? AND user_pwd =? AND user_role = 'CUST' LIMIT 0,1");
    $query->bind_param('ss', $user_username, $user_pwd);
    $query->execute();
    $result = $query->get_result();
    $rowcount = $result->num_rows;
    if ($rowcount > 0) {
        $row = $result->fetch_assoc();
        $_SESSION["user_id"] = $row["user_id"];
        $_SESSION["user_username"] = $row["user_username"];
        $_SESSION["user_fname"] = $row["user_fname"];
        $_SESSION["user_lname"] = $row["user_lname"];
        $_SESSION["user_role"] = $row["user_role"];
        $response['server_status'] = 1;
    } else {
        $response['server_status'] = 0;
    }
    echo json_encode($response);
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
}
