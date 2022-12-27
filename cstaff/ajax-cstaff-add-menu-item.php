<?php session_start();
include("../conn_db.php");
include("../S3_conn.php");

// File upload folder 
$uploadDir = '/img/menu/';


// Allowed file types 
$allowTypes = array('png','PNG');
$mitem_name = $_POST['mitem-name'];
$mitem_price = $_POST['mitem-price'];
$mitem_status = $_POST['mitem-status'];

$queryValidate = "SELECT mitem_name FROM mitem WHERE mitem_name = '{$mitem_name}';";
$result = $mysqli->query($queryValidate);
if (mysqli_num_rows($result)) {
    $response['server_status'] = 0;
    echo json_encode($response);
    exit();
} else {
    $insert_query = "INSERT INTO mitem (mitem_name,mitem_price,mitem_status) 
    VALUES ('{$mitem_name}','{$mitem_price}','{$mitem_status}');";
    $insert_result = $mysqli->query($insert_query);

    //Image upload
    $fileName = basename($_FILES["mitem-pic"]["name"]);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    if (in_array($fileType, $allowTypes)) {

        $mitem_id = $mysqli->insert_id;
        #$bucket = 'ddac-pastry-tp053060';
        $temp_file_location = $_FILES['mitem-pic']['tmp_name'];
        $key = basename($fileName);
        try {
            $result = $s3Client->putObject([
                'Bucket' => $bucket,
                'Key'    => $mitem_id . $key,
                'Body'   => $temp_file_location,
                'SourceFile' => $temp_file_location,
                'ACL'    => 'public-read', // make file 'public'
                'ContentType' => 'image/png',
            ]);

            $image_path = "https://dcczugkilqv0c.cloudfront.net/" . basename($result->get('ObjectURL'));
            //echo "Image uploaded successfully. Image path is: ".$image_path;
            $insert_query = "UPDATE mitem SET mitem_pic = '{$image_path}' WHERE mitem_id = {$mitem_id}";
            $insert_result = $mysqli->query($insert_query);
            if ($insert_result) {
                $response['server_status'] = 1;
            } else {
                $response['server_status'] = 0;
            }
            echo json_encode($response);
        } catch (Aws\S3\Exception\S3Exception $e) {
            // echo "There was an error uploading the file.\n";
            // echo $e->getMessage();
            $response['server_status'] = 0;
        }
    }
}
