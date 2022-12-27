<?php
$mysqli = new mysqli("localhost", "root", "", "ddac");
// $mysqli = new mysqli("ddac.co6fyvysy1hr.us-east-1.rds.amazonaws.com","admin","12345678","ucas");
//$mysqli = new mysqli("ddac.co6fyvysy1hr.us-east-1.rds.amazonaws.com","admin","12345678","ucas");


if ($mysqli->connect_errno) {
    header("location: db_error.php");
    exit(1);
}

define('SITE_ROOT', realpath(dirname(__FILE__)));
define('ADD_URL','http://localhost/DDAC-master1/DDAC/func-add-order.php');
define('FAILED_URL','http://localhost/DDAC-master1/DDAC/order-failed.php');
date_default_timezone_set('Asia/Kuala_Lumpur');
