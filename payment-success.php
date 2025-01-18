<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateUser($connection);
require 'PayPalClient.php';

use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

$petId = $_GET['pet_id'];

if (isset($_GET['token']) && isset($_GET['PayerID'])) {
    $token = $_GET['token'];

    $client = PayPalClient::client();
    $request = new OrdersCaptureRequest($token);
    $request->prefer('return=representation');

    try {
        $response = $client->execute($request);
        $status = $response->result->status;
        $amount = $response->result->purchase_units[0]->payments->captures[0]->amount->value;
        $transactionId = $response->result->purchase_units[0]->payments->captures[0]->id;

        // Update the database
        $userId = $_SESSION['id'];
        $requestedAt = date("Y-m-d H:i:s", time());

        $sql = "INSERT INTO adoptions (pet_id, user_id, amount, status, requested_at, transaction_id) 
                VALUES ('$petId', '$userId', '$amount', '$status', '$requestedAt', '$transactionId')";
        mysqli_query($connection, $sql);

        // Mark the pet as adopted
        $stmt = $connection->prepare("UPDATE pets SET status = 'adopted' WHERE id = ?");
        $stmt->bind_param("i", $petId);
        $stmt->execute();

        echo "<!DOCTYPE html>
<html lang='en'>
<head>";
        require 'links.php';
        echo "<title>FurEver Home | Message</title>
</head>
<body>";
        require 'navbar.php';
        echo "<div class='other-details' style='background-color: rgba(131, 173, 68, 0.8)'>
<p style='color: antiquewhite; font-weight: bolder;'>Payment successful!</p>
<p style='color: antiquewhite; font-weight: bolder;'>Thank you for adopting.</p>
</div>
</body>
</html>";
    } catch (Exception $e) {
        echo "Error capturing payment: " . $e->getMessage();
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
<p>Payment was not completed!</p>
</div>
</body>
</html>";
}