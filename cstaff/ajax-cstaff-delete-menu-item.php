<?php
session_start();
include('../conn_db.php');
include("../S3_conn.php");


#$bucket = 'ddac-pastry-tp053060';

if (!empty(($_POST['mitem_id']))) {
    $mitem_id = $_POST['mitem_id'];
    $query = "SELECT * FROM mitem WHERE mitem_id = '{$mitem_id}';";
    $result = $mysqli->query($query);
    $row = $result->fetch_array();
    //Set pic name into variable instead of using mysql query;
    $mitem_pic = $row['mitem_pic'];
    $query = "DELETE FROM mitem WHERE mitem_id = '{$mitem_id}';";
    $result = $mysqli->query($query);
    $key = basename($mitem_pic);
    if ($result) {
        try {
            $result = $s3Client->deleteObject(array(
                'Bucket' => $bucket,
                'Key'    => $key
            ));
        } catch (Aws\S3\Exception\S3Exception $e) {
            // echo "There was an error deleting the file.\n";
            // echo $e->getMessage();
        }
        // $target_dir = '/img/menu/';
        // $target_file = $target_dir . $mitem_pic;
        // unlink(SITE_ROOT . $target_file);
        $response['server_status'] = 1;
    } else {
        $response['server_status'] = 0;
    }
}
echo json_encode($response);
