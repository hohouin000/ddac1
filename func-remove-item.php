<?php
session_start();
include('conn_db.php');
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
    header("location: login.php");
    exit(1);
}

if (isset($_POST["mitem-id"])) {
    if (!empty($_POST['mitem-id']) ) {
        $mitem_id = mysqli_real_escape_string($mysqli, $_POST["mitem-id"]);

        $user_id = mysqli_real_escape_string($mysqli, $_SESSION["user_id"]);

        // $query = "SELECT * from cart WHERE user_id = {$user_id} AND store_id = {$store_id} AND mitem_id = {$mitem_id}";
        // $result = $mysqli->query($query);
        // $rowcount = mysqli_num_rows($result);
        $query = $mysqli->prepare("SELECT * from cart WHERE user_id =?  AND mitem_id =?");
        $query->bind_param('ii', $user_id, $mitem_id);
        $query->execute();
        $result = $query->get_result();
        $rowcount = $result->num_rows;
        if ($rowcount > 0) {
            // $query = "DELETE FROM cart WHERE user_id = {$user_id} AND store_id = {$store_id} AND mitem_id = {$mitem_id}";
            // $result = $mysqli->query($query);
            $query = $mysqli->prepare("DELETE FROM cart WHERE user_id =? AND mitem_id =?");
            $query->bind_param('ii', $user_id, $mitem_id);
            $result = $query->execute();
            if ($result) {
                $_SESSION["server_status"] = 1;
                header("location:cart.php");
                exit(0);
            } else {
                $_SESSION["server_status"] = 0;
                header("location:cart.php");
                exit(1);
            }
        } else {
            $_SESSION["server_status"] = 0;
            header("location:cart.php");
            exit(1);
        }
    } else {
        $_SESSION["server_status"] = 0;
        header("location:cart.php");
        exit(1);
    }
}
