<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateAdmin($connection);
require 'vendor/autoload.php';
require 'PayPalClient.php';

use PayPalCheckoutSdk\Payments\CapturesRefundRequest;

if (isset($_POST['refund'])) {
    $transactionId = $_POST['transaction_id'];
    $refundAmount = $_POST['refund_amount'];
    $petId = $_POST['pet_id'];

    if($refundAmount > 0){
        try {
            $client = PayPalClient::client();
            $request = new CapturesRefundRequest($transactionId);
            $request->prefer('return=representation');

            $request->body = [
                "amount" => [
                    "value" => number_format($refundAmount, 2, '.', ''),
                    "currency_code" => "USD"
                ]
            ];

            $response = $client->execute($request);

            $sql = "UPDATE adoptions SET status = 'REFUNDED' WHERE transaction_id = '$transactionId'";
            if(!mysqli_query($connection, $sql)) {
                echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
                require 'links.php';
                echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
                require 'admin-navbar.php';
                echo "<div class='errors show'>
                          <p>Database error occurred!</p>
                      </div>
                      </body>
                      </html>";
            }

            $sql = "UPDATE pets SET status = 'available' WHERE id = '$petId'";
            if(!mysqli_query($connection, $sql)) {
                echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
                require 'links.php';
                echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
                require 'admin-navbar.php';
                echo "<div class='errors show'>
                          <p>Database error occurred!</p>
                      </div>
                      </body>
                      </html>";
            }

            echo "<!DOCTYPE html>
                    <html lang='en'>
                    <head>";
                    require 'links.php';
                    echo "<title>FurEver Home | Message</title>
                    </head>
                    <body>";
                       require 'admin-navbar.php';
                        echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                        <p style='color: antiquewhite;'>Refund successful for Transaction ID: $transactionId</p>
                        </div>
                        </body>
                        </html>";

            $sql = "SELECT users.email 
                    FROM adoptions 
                    INNER JOIN users ON adoptions.user_id = users.id 
                    WHERE adoptions.pet_id = '$petId'";
            if($result  = mysqli_query($connection, $sql)) {}
            $requesterEmail = mysqli_fetch_assoc($result)['email'];

            $subject = 'Reject Email';
            $body = "<h2>Your request for adoption has not been approved!</h2>
                     <p>For further information or if you have any question don't hesitate to ask as replying this email.</p>";

            if(!sendEmail($requesterEmail, $subject, $body)) {
                echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
                require 'links.php';
                echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
                require 'admin-navbar.php';
                echo "<div class='errors show'>
                      <p>Something went wrong sending email.</p>
                   </div>
                   </body>
                   </html>";
            } else {
                echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
                require 'links.php';
                echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
                require 'admin-navbar.php';
                echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                      <p style='color: antiquewhite;'>Email sent successfully.</p>
                  </div>
                  </body>
                  </html>";
            }
        } catch (Exception $e) {
            die("Error processing refund: " . $e->getMessage());
        }
    } else {
        $sql = "UPDATE pets SET status = 'available' WHERE id = '$petId'";
        if(!mysqli_query($connection, $sql)) {
            echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
            require 'links.php';
            echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
            require 'admin-navbar.php';
            echo "<div class='errors show'>
                          <p>Database error occurred!</p>
                      </div>
                      </body>
                      </html>";
        }
        $sql = "SELECT users.email 
                    FROM adoptions 
                    INNER JOIN users ON adoptions.user_id = users.id 
                    WHERE adoptions.pet_id = '$petId'";
        if($result  = mysqli_query($connection, $sql)) {}
        $requesterEmail = mysqli_fetch_assoc($result)['email'];

        $subject = 'Reject Email';
        $body = "<h2>Your request has not been approved!</h2>
                     <p>For further information or if you have any question don't hesitate to ask as replying this email.</p>";

        if(!sendEmail($requesterEmail, $subject, $body)) {
            echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
            require 'links.php';
            echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
            require 'admin-navbar.php';
            echo "<div class='errors show'>
                      <p>Something went wrong sending email.</p>
                   </div>
                   </body>
                   </html>";
        } else {
            echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
            require 'links.php';
            echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
            require 'admin-navbar.php';
            echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                      <p style='color: antiquewhite;'>Email sent successfully.</p>
                  </div>
                  </body>
                  </html>";
        }
    }
} else {
    die("No capture ID provided.");
    echo "<!DOCTYPE html>
                  <html lang='en'>
                  <head>";
    require 'links.php';
    echo "<title>FurEver Home | Message</title>
                  </head>
                  <body>";
    require 'admin-navbar.php';
    echo "<div class='errors show'>
                      <p>No capture ID provided.</p>
                      </div>
                      </body>
                      </html>";

}

mysqli_close($connection);