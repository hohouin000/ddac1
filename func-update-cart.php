<?php
session_start();
include('conn_db.php');
if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
    header("location: login.php");
    exit(1);
}

if (isset($_POST["mitem-id"],$_POST["amount"])) {
    if (!empty($_POST['mitem-id'])  && !empty($_POST['amount'])) {
        $mitem_id = mysqli_real_escape_string($mysqli, $_POST["mitem-id"]);
        $user_id = mysqli_real_escape_string($mysqli, $_SESSION["user_id"]);
        $mitem_id = htmlspecialchars($mitem_id);
        $user_id = htmlspecialchars($user_id);

        // $query = "SELECT * from cart WHERE user_id = {$user_id} AND store_id = {$store_id} AND mitem_id = {$mitem_id}";
        // $result = $mysqli->query($query);
        // $rowcount = mysqli_num_rows($result);
        $query = $mysqli->prepare("SELECT * from cart WHERE user_id =?  AND mitem_id =?");
        $query->bind_param('ii', $user_id, $mitem_id);
        $query->execute();
        $result = $query->get_result();
        $rowcount = $result->num_rows;
        if ($rowcount > 0) {
            $amount = mysqli_real_escape_string($mysqli, $_POST["amount"]);
            $remark = mysqli_real_escape_string($mysqli, $_POST["remark"]);

            $remark = htmlspecialchars($remark);

            if (!filter_var($amount, FILTER_VALIDATE_FLOAT)) {
                header("location:cart.php?response=0");
                exit(1);
            }

            // $query = "UPDATE cart set cart_amount = {$amount}, cart_remark = '{$remark}' WHERE user_id = {$user_id} AND store_id = {$store_id} AND mitem_id = {$mitem_id}";
            // $result = $mysqli->query($query);
            $query = $mysqli->prepare("UPDATE cart set cart_amount =? , cart_remark =? WHERE user_id =?  AND mitem_id =?");
            $query->bind_param('isii', $amount, $remark, $user_id, $mitem_id);
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
} else {
    $_SESSION["server_status"] = 0;
    header("location:cart.php");
    exit(1);
}
