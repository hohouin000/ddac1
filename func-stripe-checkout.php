<?php
session_start();
require 'vendor/autoload.php';
include("conn_db.php");
if (isset($_POST['total-amount'])) {
    if (!empty($_POST['total-amount'])) {
        $total_amount = mysqli_real_escape_string($mysqli, $_POST["total-amount"]);
        $total_amount = (int)($total_amount  * 100);
        $stripe = new Stripe\StripeClient("sk_test_51MBGiuHGbqwDRBAKP9yCcv2q4EltFvPh5UbpMCRCpn7PkS2diEAlKfoe4ZHsRJYLnHZt0qKExGlbb1UI962x70cn00mLE1tInW");
        header('Content-Type', 'application/json');
        $store_query = $mysqli->prepare("SELECT * FROM store WHERE store_id = (SELECT store_id FROM cart WHERE user_id =? GROUP BY user_id)");
        $store_query->bind_param('i', $_SESSION['user_id']);
        $store_query->execute();
        $store_arr = $store_query->get_result()->fetch_assoc();
        $store_name = $store_arr["store_name"];

        // $store_query = "SELECT * FROM store WHERE store_id = (SELECT store_id FROM cart WHERE user_id = {$_SESSION['user_id']} GROUP BY user_id)";
        // $store_arr = $mysqli->query($store_query)->fetch_array();
        // $query = "SELECT c.*, m.*, u.* FROM user u INNER JOIN cart c ON u.user_id = c.user_id INNER JOIN mitem m ON c.mitem_id = m.mitem_id WHERE c.user_id = {$_SESSION['user_id']} AND c.store_id = {$store_id};";
        // $result = $mysqli->query($query);
        $query =  $mysqli->prepare("SELECT c.*, m.*, u.* FROM user u INNER JOIN cart c ON u.user_id = c.user_id INNER JOIN mitem m ON c.mitem_id = m.mitem_id WHERE c.user_id =?");
        $query->bind_param('i', $_SESSION['user_id']);
        $query->execute();
        $result = $query->get_result();
        $line_items_array = [];

        while ($row = $result->fetch_object()) {
            array_push(
                $line_items_array,
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => $row->mitem_name,
                            'metadata' => [
                                'pro_id' => $row->mitem_id
                            ]
                        ],
                        'unit_amount' => (int)($row->mitem_price  * 100),
                        'currency' => "myr",
                    ],
                    'quantity' => $row->cart_amount
                ]
            );
        }

        try {
            $session = $stripe->checkout->sessions->create([
                "success_url" => ADD_URL . '?response=1',
                "cancel_url" => FAILED_URL,
                "payment_method_types" => ['card'],
                "mode" => 'payment',
                "line_items" => $line_items_array,
                "payment_intent_data" => [
                    "description" => "Store Name: " . $store_name,
                ],

            ]);

            header("Location: " . $session->url);
        } catch (Exception $e) {
            $api_error = $e->getMessage();
        }

        if (($api_error) && !$session) {
            header("location: order-failed.php");
            exit(1);
        }
    } else {
        header("location: order-failed.php");
        exit(1);
    }
}
