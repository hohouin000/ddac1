<?php session_start();
include("../conn_db.php");
include("../S3_conn.php");
// File upload folder 
$uploadDir = '/img/menu/';



// Allowed file types 
$allowTypes = array('png','PNG');
if (!empty($_POST['mitem-id'])) {
    $mitem_id = $_POST['mitem-id'];
    $fileName = basename($_FILES["mitem-pic"]["name"]);
    $targetFilePath = $uploadDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);
    if (in_array($fileType, $allowTypes)) {



        //delete old image
        $query = "SELECT * FROM mitem WHERE mitem_id = '{$mitem_id}';";
        $result = $mysqli->query($query);
        $row = $result->fetch_array();

        $mitem_pic = $row['mitem_pic'];
        $key = basename($mitem_pic);
        try {
            $result = $s3Client->deleteObject(array(
                'Bucket' => $bucket,
                'Key'    => $key
            ));
        } catch (Aws\S3\Exception\S3Exception $e) {
            // echo "There was an error deleting the file.\n";
            // echo $e->getMessage();
        }



        //upload image to S3


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
            $query = "UPDATE mitem SET mitem_pic = '{$image_path}' WHERE mitem_id = {$mitem_id};";
            $result = $mysqli->query($query);

            $response['server_status'] = 1;
            echo json_encode($response);
        } catch (Aws\S3\Exception\S3Exception $e) {
            $response['server_status'] = 0;
            echo json_encode($response);
            // echo "There was an error uploading the file.\n";
            // echo $e->getMessage();
        }


        // Upload file to the server 
        // $target_dir = '/img/menu/';
        // $temp = explode(".", $_FILES["mitem-pic"]["name"]);
        // $target_newfilename = "mitem_id_" . $mitem_id . "." . strtolower(end($temp));
        // $target_file = $target_dir . $target_newfilename;
        // if (move_uploaded_file($_FILES["mitem-pic"]["tmp_name"], SITE_ROOT . $target_file)) {
        //     $query = "UPDATE mitem SET mitem_pic = '{$target_newfilename}' WHERE mitem_id = {$mitem_id};";
        //     $result = $mysqli->query($query);
        //     $response['server_status'] = 1;
        //     echo json_encode($response);
        // }
    }
} else {
    $response['server_status'] = 0;
    echo json_encode($response);
}
