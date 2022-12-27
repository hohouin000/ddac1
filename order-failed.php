<!DOCTYPE html>
<html lang="en">

<head>
    <?php session_start();
    include("conn_db.php");
    include('head.php');
    if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] != "CUST")) {
        header("location: login.php");
        exit(1);
    }
    ?>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/semantic.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/semantic.min.js"></script>
    <link href="css/fail.css" rel="stylesheet" />
    <title>Cart</title>
</head>

<body>
    <div class="container" style="margin-top:5%">
        <div class="ui middle aligned center aligned grid">
            <div class="ui eight wide column">

                <form class="ui large form">

                    <div class="ui icon negative message">
                        <i class="warning icon"></i>
                        <div class="content">
                            <div class="header">
                                Oops! Something went wrong.
                            </div>
                            <p>Transaction Failed !</p>
                        </div>

                    </div>

                    <a href="cart.php" span class="ui large teal submit fluid button">Try again</a></span>

                </form>
            </div>
        </div>
    </div>
</body>

</html>