<?php session_start();
include("conn_db.php");
include("SNS_conn.php");

if (isset($_POST['user_fname'], $_POST['user_lname'], $_POST['user_username'], $_POST['user_email'],  $_POST['user_id'])) {
    if (!empty($_POST['user_fname']) && !empty($_POST['user_lname']) &&  !empty($_POST["user_username"]) && !empty($_POST["user_email"]) && !empty($_POST["user_id"])) {
        $user_fname = mysqli_real_escape_string($mysqli, $_POST['user_fname']);
        $user_lname = mysqli_real_escape_string($mysqli, $_POST['user_lname']);
        $user_username = mysqli_real_escape_string($mysqli, $_POST['user_username']);
        $user_email = mysqli_real_escape_string($mysqli, $_POST['user_email']);
        $user_id = mysqli_real_escape_string($mysqli, $_POST['user_id']);

        $user_fname = htmlspecialchars($user_fname);
        $user_lname = htmlspecialchars($user_lname);
        $user_username = htmlspecialchars($user_username);
        $user_email = htmlspecialchars($user_email);

        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $response['server_status'] = -2;
            echo json_encode($response);
            exit(1);
        }

        if (strlen($user_username) < 5 || strlen($user_username) > 15) {
            $response['server_status'] = -4;
            echo json_encode($response);
            exit(1);
        }

        $query = $mysqli->prepare("UPDATE user SET user_username =?, user_fname =?, user_lname =?, user_email =? WHERE user_id =?;");
        $query->bind_param('ssssi', $user_username, $user_fname, $user_lname, $user_email, $user_id);
        $result = $query->execute();

        if ($result) {
            try {
                $result = $SnSclient->subscribe([
                    'Protocol' => $protocol,
                    'Endpoint' => $user_email,
                    'ReturnSubscriptionArn' => true,
                    'TopicArn' => $topic,
                ]);
                
                //var_dump($result);
                $response['server_status'] = 1;
                
            } catch (Aws\Exception\AwsException $e) {
                // output error message if fails
                error_log($e->getMessage());
                $response['server_status'] = 0;
            } 
            //$response['server_status'] = 1;
        } else {
            $response['server_status'] = 0;
        }
        echo json_encode($response);
        exit(0);
    }
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
    exit(1);
}
