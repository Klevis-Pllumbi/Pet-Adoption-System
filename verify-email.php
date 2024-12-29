<div class="error-container">
<?php
/** @var mysqli $connection */
require "connection.php";
session_start();

if(isset($_GET["token"])) {
    $token = $_GET["token"];
    if(!isset($_SESSION["email"])){
        header("location: signup.php");
    } else {
        $email = $_SESSION["email"];
        $sql = "SELECT verification_token, is_verified FROM users WHERE verification_token = '$token' AND email = '$email'";
        $result = mysqli_query($connection, $sql);
        if(mysqli_num_rows($result) < 0) {
            echo "No verification code found";
        } else {
            $row = mysqli_fetch_assoc($result);
            if($row["verification_token"] == $token) {
                if($row["is_verified"] == 0) {
                    $sql = "UPDATE users SET is_verified = 1 WHERE verification_token = '$token' AND email = '$email'";
                    $result = mysqli_query($connection, $sql);
                    if(!$result) {
                        echo "An error happened. Please try again.";
                    } else {
                        header("location: login.php");
                    }
                } else {
                    header("location: login.php");
                }
            } else {
                echo "Invalid verification code";
            }
        }
        session_destroy();
    }
}
?>
</div>