<?php session_start();
include("conn_db.php");
include("SNS_conn.php");

if (isset($_POST['user_fname'], $_POST['user_lname'], $_POST['user_pwd'], $_POST['user_username'], $_POST['user_email'])) {
    if (!empty($_POST['user_fname']) && !empty($_POST['user_lname']) &&  !empty($_POST['user_pwd']) &&  !empty($_POST['user_username']) &&  !empty($_POST['user_email'])) {
        $user_fname = mysqli_real_escape_string($mysqli, $_POST['user_fname']);
        $user_lname = mysqli_real_escape_string($mysqli, $_POST['user_lname']);
        $user_pwd = mysqli_real_escape_string($mysqli, $_POST['user_pwd']);
        $user_username = mysqli_real_escape_string($mysqli, $_POST['user_username']);
        $user_email = mysqli_real_escape_string($mysqli, $_POST['user_email']);
        $user_role = "CUST";
        $user_fname = htmlspecialchars($user_fname);
        $user_lname = htmlspecialchars($user_lname);
        $user_pwd = htmlspecialchars($user_pwd);
        $user_username = htmlspecialchars($user_username);
        $user_email = htmlspecialchars($user_email);

        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
            $response['server_status'] = -2;
            echo json_encode($response);
            exit(1);
        }

        if (strlen($user_pwd) < 8 || strlen($user_pwd) > 12) {
            $response['server_status'] = -3;
            echo json_encode($response);
            exit(1);
        }

        if (strlen($user_username) < 5 || strlen($user_username) > 15) {
            $response['server_status'] = -4;
            echo json_encode($response);
            exit(1);
        }

        // $queryValidate = "SELECT * FROM user WHERE user_username = '{$user_username}';";
        // $result = $mysqli->query($queryValidate);
        $queryValidate = $mysqli->prepare("SELECT * FROM user WHERE user_username =?;");
        $queryValidate->bind_param('s', $user_username);
        $queryValidate->execute();
        $result = $queryValidate->get_result();
        if (mysqli_num_rows($result)) {
            $response['server_status'] = -1;
            echo json_encode($response);
            exit(1);
        } else {
            // $insert_query = "INSERT INTO user (user_username,user_fname,user_lname,user_pwd,user_role,user_email) 
            //                   VALUES ('{$user_username}','{$user_fname}','{$user_lname}','{$user_pwd}','{$user_role}','{$user_email}');";
            // $insert_result = $mysqli->query($insert_query);
            $insert_query = $mysqli->prepare("INSERT INTO user (user_username,user_fname,user_lname,user_pwd,user_role,user_email) VALUES (?,?,?,?,?,?);");
            $insert_query->bind_param('ssssss', $user_username, $user_fname, $user_lname, $user_pwd, $user_role, $user_email);
            $insert_result = $insert_query->execute();
            if ($insert_result) {
                try {
                    $result = $SnSclient->subscribe([
                        'Protocol' => $protocol,
                        'Endpoint' => $user_email,
                        'ReturnSubscriptionArn' => true,
                        'TopicArn' => $topic,
                    ]);

                    $response['server_status'] = 1;
                    echo json_encode($response);
                    exit(1);
                } catch (Aws\Exception\AwsException $e) {
                    // output error message if fails
                    //error_log($e->getMessage());
                    $response['server_status'] = 0;
                    echo json_encode($response);
                    exit(1);
                }
            } else {
                $response['server_status'] = 0;
                echo json_encode($response);
                exit(1);
            }
        }
    } else {
        $response['server_status'] = 0;
        echo json_encode($response);
        exit(1);
    }
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
    exit(1);
}
