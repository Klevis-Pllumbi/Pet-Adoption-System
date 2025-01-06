<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
function sendVerificationEmail(string $email, string $verification_token): bool {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();

        $mail->Username = $_ENV['SMTP_USER'];
        $mail->Password = $_ENV['SMTP_PASS'];
        $mail->SMTPAuth = true;
        $mail->Host = $_ENV['SMTP_HOST'];
        $mail->Port = $_ENV['SMTP_PORT'];
        $mail->SMTPSecure = $_ENV['SMTP_ENCRYPTION'];

        $mail->setFrom($_ENV['SMTP_USER'], "FurEver Home");
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Verification Email';
        $template = "<h2>You have been signed up!</h2>
                     <p>Verify your email with the link below.</p>
                     <br>
                     <a href='http://localhost/Pet%20Adoption%20System/verify-email.php?token=$verification_token'>Click me</a>";

        $mail->Body = $template;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function authenticateUser($connection): bool {

    if(isset($_SESSION['id'])) {
        return true;
    }

    if(isset($_COOKIE['remember_me'])) {
        $token = $_COOKIE['remember_me'];
        $sql = "SELECT * FROM `users` WHERE `remember_token` = '$token'";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['id'] = $user['id'];

            return true;
        }
    }

    header("Location: login.php");
    exit();

}
