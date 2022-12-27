<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

$mail_status = 0;
$user_email = "";
if (isset($_SESSION["server_status"], $_GET["odr"])) {
    if (!empty($_GET["odr"])) {
        if ($_SESSION["server_status"] == 1) {
            $odr_id =  mysqli_real_escape_string($mysqli, $_GET["odr"]);
            // $query = "SELECT o.*, u.user_email, s.store_name FROM odr o INNER JOIN user u ON o.user_id = u.user_id INNER JOIN store s ON o.store_id = s.store_id WHERE o.odr_id = {$odr_id};";
            // $arr = $mysqli->query($query)->fetch_array();
            $query = $mysqli->prepare("SELECT o.*, u.user_email FROM odr o INNER JOIN user u ON o.user_id = u.user_id WHERE o.odr_id =?;");
            $query->bind_param('i', $odr_id);
            $query->execute();
            $arr = $query->get_result()->fetch_assoc();
            $user_email = $arr['user_email'];
            $odr_ref = $arr['odr_ref'];
            $store_name = "Rong Sheng’s Famous Pastries and Cake";
            $date = date("jS-M-Y");
            $details = "";
            // $odr_detail_query = "SELECT m.*,od.* FROM odr_detail od INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE od.odr_id = {$odr_id}";
            // $odr_detail_result = $mysqli->query($odr_detail_query);
            $odr_detail_query = $mysqli->prepare("SELECT m.*,od.* FROM odr_detail od INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE od.odr_id =?");
            $odr_detail_query->bind_param('i', $odr_id);
            $odr_detail_query->execute();
            $odr_detail_result = $odr_detail_query->get_result();
            // while ($odr_detail_row = $odr_detail_result->fetch_array()) {
            while ($odr_detail_row = $odr_detail_result->fetch_assoc()) {
                if ($odr_detail_row["odr_detail_remark"] != "") {
                    $mitem = $odr_detail_row["odr_detail_amount"] . "<b>x</b> " . $odr_detail_row["mitem_name"] . " (" . $odr_detail_row["odr_detail_remark"] . ")";
                } else {
                    $mitem = $odr_detail_row["odr_detail_amount"]  . "<b>x</b> " . $odr_detail_row["mitem_name"];
                }
                $price = "RM " . $odr_detail_row['mitem_price'] * $odr_detail_row['odr_detail_amount'];
                $details .=
                    "<tr><td>$mitem</td><td class='alignright'>$price</td></tr>";
            }
            // $odr_query = "SELECT SUM(odr_detail_amount*odr_detail_price) AS total_price FROM odr_detail WHERE odr_id = {$odr_id}";
            // $odr_arr = $mysqli->query($odr_query)->fetch_array();
            $odr_query = $mysqli->prepare("SELECT SUM(odr_detail_amount*odr_detail_price) AS total_price FROM odr_detail WHERE odr_id =?");
            $odr_query->bind_param('i', $odr_id);
            $odr_query->execute();
            $odr_arr = $odr_query->get_result()->fetch_assoc();
            $total_price = "RM " . $odr_arr['total_price'];
            $page = "
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>Digital Receipt</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-size: 14px;
    }
    
    img {
        max-width: 100%;
    }
    
    body {
        -webkit-font-smoothing: antialiased;
        -webkit-text-size-adjust: none;
        width: 100% !important;
        height: 100%;
        line-height: 1.6;
    }
    
    table td {
        vertical-align: top;
    }
    
    body {
        background-color: #f6f6f6;
    }
    
    .body-wrap {
        background-color: #f6f6f6;
        width: 100%;
    }
    
    .container {
        display: block !important;
        max-width: 600px !important;
        margin: 0 auto !important;
        /* makes it centered */
        clear: both !important;
    }
    
    .content {
        max-width: 600px;
        margin: 0 auto;
        display: block;
        padding: 20px;
    }
    
    .main {
        background: #fff;
        border: 1px solid #e9e9e9;
        border-radius: 3px;
    }
    
    .content-wrap {
        padding: 20px;
    }
    
    .content-block {
        padding: 0 0 20px;
    }
    
    .header {
        width: 100%;
        margin-bottom: 20px;
    }
    
    .footer {
        width: 100%;
        clear: both;
        color: #999;
        padding: 20px;
    }
    .footer a {
        color: #999;
    }
    .footer p, .footer a, .footer unsubscribe, .footer td {
        font-size: 12px;
    }
    
    h1, h2, h3 {
        color: #000;
        margin: 40px 0 0;
        line-height: 1.2;
        font-weight: 400;
    }
    
    h1 {
        font-size: 32px;
        font-weight: 500;
    }
    
    h2 {
        font-size: 24px;
    }
    
    h3 {
        font-size: 18px;
    }
    
    h4 {
        font-size: 14px;
        font-weight: 600;
    }
    
    p, ul, ol {
        margin-bottom: 10px;
        font-weight: normal;
    }
    p li, ul li, ol li {
        margin-left: 5px;
        list-style-position: inside;
    }
    
    a {
        color: #1ab394;
        text-decoration: underline;
    }
    
    .btn-primary {
        text-decoration: none;
        color: #FFF;
        background-color: #1ab394;
        border: solid #1ab394;
        border-width: 5px 10px;
        line-height: 2;
        font-weight: bold;
        text-align: center;
        cursor: pointer;
        display: inline-block;
        border-radius: 5px;
        text-transform: capitalize;
    }
    
    .last {
        margin-bottom: 0;
    }
    
    .first {
        margin-top: 0;
    }
    
    .aligncenter {
        text-align: center;
    }
    
    .alignright {
        text-align: right;
    }
    
    .alignleft {
        text-align: left;
    }
    
    .clear {
        clear: both;
    }
    
    .alert {
        font-size: 16px;
        color: #fff;
        font-weight: 500;
        padding: 20px;
        text-align: center;
        border-radius: 3px 3px 0 0;
    }
    .alert a {
        color: #fff;
        text-decoration: none;
        font-weight: 500;
        font-size: 16px;
    }
    .alert.alert-warning {
        background: #f8ac59;
    }
    .alert.alert-bad {
        background: #ed5565;
    }
    .alert.alert-good {
        background: #1ab394;
    }
    
    /* -------------------------------------
        INVOICE
        Styles for the billing table
    ------------------------------------- */
    .invoice {
        margin: 40px auto;
        text-align: left;
        width: 80%;
    }
    .invoice td {
        padding: 5px 0;
    }
    .invoice .invoice-items {
        width: 100%;
    }
    .invoice .invoice-items td {
        border-top: #eee 1px solid;
    }
    .invoice .invoice-items .total td {
        border-top: 2px solid #333;
        border-bottom: 2px solid #333;
        font-weight: 700;
    }

    @media only screen and (max-width: 640px) {
        h1, h2, h3, h4 {
            font-weight: 600 !important;
            margin: 20px 0 5px !important;
        }
    
        h1 {
            font-size: 22px !important;
        }
    
        h2 {
            font-size: 18px !important;
        }
    
        h3 {
            font-size: 16px !important;
        }
    
        .container {
            width: 100% !important;
        }
    
        .content, .content-wrap {
            padding: 10px !important;
        }
    
        .invoice {
            width: 100% !important;
        }
    }
    </style>
</head>

<body>
    <table class='body-wrap'>
        <tbody>
            <tr>
                <td>
                </td>
                <td class='container' width='600'>
                    <div class='content'>
                        <table class='main' width='100%' cellpadding='0' cellspacing='0'>
                            <tbody>
                                <tr>
                                    <td class='content-wrap aligncenter'>
                                        <table width='100%' cellpadding='0' cellspacing='0'>
                                            <tbody>
                                                <tr>
                                                    <td class='content-block'>
                                                        <h2>Thanks for your purchase</h2>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class='content-block'>
                                                        <table class='invoice'>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Store: $store_name<br>Order Ref: $odr_ref<br>Date: $date</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <table class='invoice-items' cellpadding='0' cellspacing='0'>
                                                                            <tbody>
                                                                                $details
                                                                                <tr class='total'>
                                                                                    <td class='alignright' width='80%'>Total:</td>
                                                                                    <td class='alignright'>$total_price</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
</body>
</html>
";

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = "tls";
            $mail->Port = "587";
            $mail->Username = "ucastest2000@gmail.com";
            $mail->Password = "ffsjqqsmpluwfrep";
            $mail->Subject = "Digital Receipt: " . $odr_ref;
            $mail->setFrom('ucastest2000@gmail.com');
            $mail->isHTML(true);
            $mail->Body = $page;
            $mail->addAddress($user_email);
            if ($mail->Send()) {
                $mail_status = 1;
            }
            unset($_SESSION['server_status']);
        }
    }
}
