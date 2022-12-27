<?php
session_start();
include('conn_db.php');

if (isset($_POST['user_id'], $_POST['user_pwd'], $_POST['user_new_pwd'])) {
    if (!empty($_POST['user_id']) && !empty($_POST['user_pwd']) &&  !empty($_POST["user_new_pwd"])) {
        $user_id = mysqli_real_escape_string($mysqli, $_POST['user_id']);
        $user_pwd = mysqli_real_escape_string($mysqli, $_POST['user_pwd']);
        $user_new_pwd = mysqli_real_escape_string($mysqli, $_POST['user_new_pwd']);
        $user_new_pwd = htmlspecialchars($user_new_pwd);

        if (strlen($user_new_pwd) < 8 || strlen($user_new_pwd) > 12) {
            $response['server_status'] = -3;
            echo json_encode($response);
            exit(1);
        }

        // $query = "SELECT * FROM user  WHERE user_id = {$user_id} AND user_pwd = '{$user_pwd}';";
        // $result = $mysqli->query($query);
        // $rowcount = mysqli_num_rows($result);
        $query = $mysqli->prepare("SELECT * FROM user  WHERE user_id =? AND user_pwd =? ;");
        $query->bind_param('is', $user_id, $user_pwd);
        $query->execute();
        $result = $query->get_result();
        $rowcount = $result->num_rows;
        if ($rowcount > 0) {
            $query = $mysqli->prepare("UPDATE user SET user_pwd =? WHERE user_id =?;");
            $query->bind_param('si', $user_new_pwd, $user_id);
            $result = $query->execute();

            if ($result) {
                $response['server_status'] = 1;
            } else {
                $response['server_status'] = 0;
            }
            echo json_encode($response);
            exit(0);
        } else {
            $response['server_status'] = -5;
            echo json_encode($response);
            exit(1);
        }
    }
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
    exit(1);
}
