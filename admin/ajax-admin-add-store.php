<?php session_start();
include("../conn_db.php");
// File upload folder 
$uploadDir = '/img/store/';

// Allowed file types 
$allowTypes = array('png');
$store_name = $_POST['store-name'];
$store_location = $_POST['store-location'];
$store_openhour = $_POST['store-openhour'];
$store_closehour = $_POST['store-closehour'];
$store_status = $_POST['store-status'];

$queryValidate = "SELECT store_name, store_location FROM store WHERE store_name = '{$store_name}' OR store_location = '{$store_location}';";
$result = $mysqli->query($queryValidate);
if (mysqli_num_rows($result)) {
    $response['server_status'] = 0;
    echo json_encode($response);
    exit();
} else {
    $insert_query = "INSERT INTO store (store_name,store_location,store_openhour,store_closehour,store_status) 
    VALUES ('{$store_name}','{$store_location}','{$store_openhour}','{$store_closehour}','{$store_status}');";
    $insert_result = $mysqli->query($insert_query);

    //Image upload
    $fileName = basename($_FILES["store-pic"]["name"]);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    if (in_array($fileType, $allowTypes)) {
        // Upload file to the server 
        $store_id = $mysqli->insert_id;
        $target_dir = '/img/store/';
        $temp = explode(".", $_FILES["store-pic"]["name"]);
        $target_newfilename = "store_id_" . $store_id . "." . strtolower(end($temp));
        $target_file = $target_dir . $target_newfilename;
        if (move_uploaded_file($_FILES["store-pic"]["tmp_name"], SITE_ROOT . $target_file)) {
            $insert_query = "UPDATE store SET store_pic = '{$target_newfilename}' WHERE store_id = {$store_id};";
            $insert_result = $mysqli->query($insert_query);
        } else {
            $insert_result = false;
        }

        if ($insert_result) {
            $response['server_status'] = 1;
        } else {
            $response['server_status'] = 0;
        }
        echo json_encode($response);
    }
}
