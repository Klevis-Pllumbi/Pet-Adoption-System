<?php
/** @var mysqli $connection */
require "connection.php";
session_start();

require 'vendor/autoload.php';
require 'PayPalClient.php';

use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

if (isset($_GET['token'])) {
    $orderId = $_GET['token'];

    try {
        $client = PayPalClient::client();
        $request = new OrdersCaptureRequest($orderId);
        $request->prefer('return=representation');

        $response = $client->execute($request);

        // Extract transaction details
        $transactionId = $response->result->id;
        $status = $response->result->status;
        $amount = $response->result->purchase_units[0]->payments->captures[0]->amount->value;
        $userId = $_SESSION['id'];
        $donatedAt = date("Y-m-d H:i:s", time());

        $sql = "INSERT INTO donations (user_id, amount, status, donated_at, transaction_id) 
                VALUES ('$userId', '$amount', '$status', '$donatedAt', '$transactionId')";
        mysqli_query($connection, $sql);

        echo "<!DOCTYPE html>
<html lang='en'>
<head>";
        require 'links.php';
        echo "<title>FurEver Home | Message</title>
</head>
<body>";
        require 'navbar.php';
        echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
<p style='color: antiquewhite;'>Thank you for your donation of $$amount!</p>
<p style='color: antiquewhite;'>Transaction ID: $transactionId</p>
</div>
</body>
</html>";
    } catch (Exception $e) {
        echo "<!DOCTYPE html>
<html lang='en'>
<head>";
        require 'links.php';
        echo "<title>FurEver Home | Message</title>
</head>
<body>";
        require 'navbar.php';
        echo "<div class='errors show'>
<p>Error capturing payment: " . $e->getMessage() . "</p>
</div>
</body>
</html>";
        exit();
    }
} else {
    echo "<!DOCTYPE html>
<html lang='en'>
<head>";
    require 'links.php';
    echo "<title>FurEver Home | Message</title>
</head>
<body>";
    require 'navbar.php';
    echo "<div class='errors show'>
<p>No token provided. Unable to process payment.</p>
</div>
</body>
</html>";
    exit();
}