<?php
session_start();
include("../conn_db.php");
$user_id = $_SESSION["user_id"];

$query = "SELECT * FROM user WHERE user_id <>'{$user_id}';";
$result = $mysqli->query($query);
$rowcount = mysqli_num_rows($result);

if ($rowcount > 0) {
    $i = 1;
    while ($row = $result->fetch_array()) {

        $data[] = [
            "row" => $i++,
            "user_username" => $row['user_username'],
            "user_fname" => $row['user_fname'],
            "user_lname" => $row['user_lname'],
            "user_email" => $row['user_email'],
            "user_role" => $row['user_role'],
            "user_pwd" => $row['user_pwd'],
            "user_id" => $row['user_id']
        ];
    }
} else {
    $data[] = [
        "row" => "",
        "user_username" => "",
        "user_fname" => "",
        "user_lname" => "",
        "user_email" => "",
        "user_role" => "",
        "user_pwd" => "",
        "user_id" => ""
    ];
}
$return_array = array('data' => $data);
$jsonData = json_encode($return_array);
//print ($jsonData);
echo $jsonData . "\n";
