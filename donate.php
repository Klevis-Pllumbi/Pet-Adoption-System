<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateUser($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Donate</title>
</head>
<body>

<?php require 'navbar.php' ?>

<form action="donate.php" method="post" id="donate-form">
    <h3><span>Donate</span></h3>
    <p>We greatly appreciate your decision to help us in our mission.<br>
        Protecting our furry friends is a noble duty, a duty that you are fulfilling with your help.<br>
        Best wishes!
    </p>
    <div class="form-group">
        <input type="number" name="amount" id="amount" placeholder=" " required>
        <label for="amount">Amount ($)</label>
    </div>
    <button type="submit" name="submit" id="submit">Donate</button>
</form>
<div class="error-container">
    <?php

    require 'vendor/autoload.php';
    require 'PayPalClient.php';

    use PayPalCheckoutSdk\Orders\OrdersCreateRequest;

    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
        $amount = $_POST['amount'];

        if (!is_numeric($amount) || $amount <= 0) {
            $errors[] = "Invalid donation amount.";
        }

        try {
            $client = PayPalClient::client();
            $request = new OrdersCreateRequest();
            $request->prefer('return=representation');
            $request->body = [
                "intent" => "CAPTURE",
                "purchase_units" => [[
                    "amount" => [
                        "value" => number_format($amount, 2, '.', ''),
                        "currency_code" => "USD"
                    ],
                    "description" => "Donation for Pet Adoption Center"
                ]],
                "application_context" => [
                    "return_url" => "http://localhost/Pet%20Adoption%20System/donation-success.php",
                    "cancel_url" => "http://localhost/Pet%20Adoption%20System/donation-cancel.php"
                ]
            ];

            $response = $client->execute($request);

            foreach ($response->result->links as $link) {
                if ($link->rel === 'approve') {
                    header("Location: " . $link->href);
                    exit();
                }
            }

            $errors[] = "Unexpected error. No approval link found.";

        } catch (Exception $e) {
            $errors[] = "Error creating PayPal order: " . $e->getMessage();
        }
    }

    if(!empty($errors)) {
        foreach ($errors as $error) {
            echo "<div class='errors show'><p>$error</p></div>";
        }
    }
    mysqli_close($connection);
    ?>
</div>
</body>
</html>