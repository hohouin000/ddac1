<?php 
###################everytime when restart learner lab, need to go /root/.aws/credentials and update the credentials
session_start();
include("../conn_db.php");
include("../S3_conn.php");
require('../vendor/autoload.php');
    use Pkerrigan\Xray\Trace;
    use Pkerrigan\Xray\Submission\DaemonSegmentSubmitter;
    use Pkerrigan\Xray\SqlSegment;
use Pkerrigan\Xray\HttpSegment;

    Trace::getInstance()
    ->setTraceHeader($_SERVER['HTTP_X_AMZN_TRACE_ID'] ?? null)
    ->setName('cstaff')
    ->setUrl($_SERVER['REQUEST_URI'])
    ->setMethod($_SERVER['REQUEST_METHOD'])
    ->begin(); 


// File upload folder 
$uploadDir = '/img/menu/';


// Allowed file types 
$allowTypes = array('png');
$mitem_name = $_POST['mitem-name'];
$mitem_price = $_POST['mitem-price'];
$mitem_status = $_POST['mitem-status'];

$queryValidate = "SELECT mitem_name FROM mitem WHERE mitem_name = '{$mitem_name}';";

Trace::getInstance()
    ->getCurrentSegment()
    ->addSubsegment(
        (new SqlSegment())
            ->setName('ddac.co6fyvysy1hr.us-east-1.rds.amazonaws.com')
            ->setDatabaseType('MySql')
            ->setQuery($queryValidate)    // Make sure to remove sensitive data before passing in a query
            ->begin()    
               
    );

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
             Trace::getInstance()
    ->getCurrentSegment()
    ->addSubsegment(
        (new HttpSegment())
            ->setUrl("https://dcczugkilqv0c.cloudfront.net/" . basename($result->get('ObjectURL')))   
            ->setMethod('POST')
            ->setName('cstaff-add')

            ->begin());

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

 Trace::getInstance()
    ->getCurrentSegment()
    ->end();
 Trace::getInstance()
    ->getCurrentSegment()
    ->end();

    Trace::getInstance()
    ->end()
    ->setResponseCode(http_response_code())
    ->submit(new DaemonSegmentSubmitter()); 
}
