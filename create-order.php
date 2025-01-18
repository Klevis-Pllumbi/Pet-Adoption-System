<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateUser($connection);

require 'PayPalClient.php';

use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $petId = $_POST['pet_id'];
    $fee = $_POST['fee'];
    $userId = $_SESSION['id'];
    $requestedAt = date("Y-m-d H:i:s", time());

    if($fee <= 0) {
        $sql = "INSERT INTO adoptions (pet_id, user_id, amount, status, requested_at, transaction_id) 
                VALUES ('$petId', '$userId', '0', 'COMPLETED', '$requestedAt', '0')";
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
<p style='color: antiquewhite; font-weight: bolder;'>Adoption successful!</p>
<p style='color: antiquewhite; font-weight: bolder;'>Thank you for adopting.</p>
</div>
</body>
</html>";
        exit();
    }

    $client = PayPalClient::client();
    $request = new OrdersCreateRequest();
    $request->prefer('return=representation');
    $request->body = [
        "intent" => "CAPTURE",
        "purchase_units" => [
            [
                "amount" => [
                    "currency_code" => "USD",
                    "value" => $fee
                ]
            ]
        ],
        "application_context" => [
            "return_url" => "http://localhost/Pet%20Adoption%20System/payment-success.php?pet_id=$petId",
            "cancel_url" => "http://localhost/Pet%20Adoption%20System/payment-cancel.php"
        ]
    ];

    try {
        $response = $client->execute($request);
        $approvalUrl = $response->result->links[1]->href;
        header("Location: $approvalUrl");
        exit();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
