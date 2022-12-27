<?php session_start();
include("../conn_db.php");
$store_id = $_POST['store-id'];

// File upload folder 
$uploadDir = '/img/store/';

// Allowed file types 
$allowTypes = array('png');
if (!empty($_POST['store-id'])) {
    $fileName = basename($_FILES["store-pic"]["name"]);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    if (in_array($fileType, $allowTypes)) {
        // Upload file to the server 
        $target_dir = '/img/store/';
        $temp = explode(".", $_FILES["store-pic"]["name"]);
        $target_newfilename = "store_id_" . $store_id . "." . strtolower(end($temp));
        $target_file = $target_dir . $target_newfilename;
        if (move_uploaded_file($_FILES["store-pic"]["tmp_name"], SITE_ROOT . $target_file)) {
            $query = "UPDATE store SET store_pic = '{$target_newfilename}' WHERE store_id = {$store_id};";
            $result = $mysqli->query($query);
            $response['server_status'] = 1;
            echo json_encode($response);
        }
    }
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
}
