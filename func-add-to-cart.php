<?php
session_start();
include('conn_db.php');
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit(1);
}
if (isset($_POST['mitem-id'],  $_POST['amount'], $_POST['remark'])) {
    if (!empty($_POST['mitem-id'])  &&  !empty($_POST["amount"])) {

        $mitem_id = mysqli_real_escape_string($mysqli, $_POST["mitem-id"]);
      
        $user_id = mysqli_real_escape_string($mysqli, $_SESSION["user_id"]);
        $amount = mysqli_real_escape_string($mysqli, $_POST["amount"]);
        $remark = mysqli_real_escape_string($mysqli, $_POST["remark"]);

        if (!filter_var($amount, FILTER_VALIDATE_FLOAT)) {
            $_SESSION["server_status"] = 0;
            header("location:index.php");
            exit(1);
        }

        $remark = htmlspecialchars($remark);

        // $query = "SELECT * FROM cart WHERE user_id = {$user_id} GROUP BY user_id";
        // $result = $mysqli->query($query);
        // $rowcount = mysqli_num_rows($result);
        $query = $mysqli->prepare("SELECT * FROM cart WHERE user_id =? GROUP BY user_id");
        $query->bind_param('i', $user_id);
        $query->execute();
        $result = $query->get_result();
        $rowcount = $result->num_rows;

        //case 1 No item in cart
        if ($rowcount == 0) {
            //insert into db
            //         $query = "INSERT INTO cart (user_id, store_id, mitem_id, cart_amount, cart_remark) 
            // VALUES ({$user_id},{$store_id},{$mitem_id},{$amount},'{$remark}')";
            //         $added_result = $mysqli->query($query);
            $query =  $mysqli->prepare("INSERT INTO cart (user_id, mitem_id, cart_amount, cart_remark) VALUES (?,?,?,?)");
            $query->bind_param('iiis', $user_id, $mitem_id, $amount, $remark);
            $added_result = $query->execute();
        } else {
            //case 2 have item in cart
            //$row = $result->fetch_array();
            $row = $result->fetch_assoc();

            //if items in same store
            
                // $cart_query = "SELECT * FROM cart WHERE user_id = {$user_id} AND mitem_id = {$mitem_id}";
                // $cart_result = $mysqli->query($cart_query);
                // $cartrowcount = mysqli_num_rows($cart_result);
                $cart_query = $mysqli->prepare("SELECT * FROM cart WHERE user_id =? AND mitem_id =?;");
                $cart_query->bind_param('ii', $user_id, $mitem_id);
                $cart_query->execute();
                $cart_result = $cart_query->get_result();
                $cartrowcount = $cart_result->num_rows;

                //item is not in cart yet
                if ($cartrowcount == 0) {
                    //         $query = "INSERT INTO cart (user_id, store_id, mitem_id, cart_amount, cart_remark) 
                    // VALUES ({$user_id},{$store_id},{$mitem_id},{$amount},'{$remark}')";
                    //         $added_result = $mysqli->query($query);
                    $query = $mysqli->prepare("INSERT INTO cart (user_id, mitem_id, cart_amount, cart_remark) VALUES (?,?,?,?)");
                    $query->bind_param('iiis', $user_id, $mitem_id, $amount, $remark);
                    $added_result = $query->execute();
                } else {
                    //item in cart already
                    //$cart_row = $cart_result->fetch_array();
                    $cart_row = $cart_result->fetch_assoc();
                    $cart_amount = $cart_row["cart_amount"];
                    $new_cart_amount = $cart_amount + $amount;
                    // $query = "UPDATE cart SET cart_amount = {$new_cart_amount} WHERE user_id = {$user_id} AND mitem_id = {$mitem_id} AND store_id = {$store_id}";
                    // $added_result = $mysqli->query($query);
                    $query = $mysqli->prepare("UPDATE cart SET cart_amount =? WHERE user_id =? AND mitem_id =? ;");
                    $query->bind_param('iii', $new_cart_amount, $user_id, $mitem_id);
                    $added_result = $query->execute();
                }
             
        }
        if ($added_result) {
            $_SESSION["server_status"] = 1;
            header("location:index.php");
            exit(0);
        } else {
            $_SESSION["server_status"] = 0;
            header("location:index?result={$added_result}");
            exit(1);
        }
    } else {
        $_SESSION["server_status"] = 0;
        header("location:index.php");
        exit(1);
    }
} else {
    $_SESSION["server_status"] = 0;
    header("location:index.php");
    exit(1);
}
