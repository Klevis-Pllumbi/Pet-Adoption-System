<?php
/** @var mysqli $connection */
require "connection.php";

if(isset($_GET["token"])) {
    $token = $_GET["token"];
    $sql = "SELECT verification_token, is_verified FROM users WHERE verification_token = '$token'";
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);
    if(!$row) {
        echo "Invalid verification code";
    } else {
        if($row["verification_token"] == $token) {
            if($row["is_verified"] == 0) {
                $sql = "UPDATE users SET is_verified = 1 WHERE verification_token = '$token'";
                $result = mysqli_query($connection, $sql);
                if(!$result) {
                    echo "An error happened. Please try again.";
                } else {
                    header("location: login.php");
                }
            } else {
                header("location: index.php");
            }
        } else {
            echo "Invalid verification code";
        }
    }
}
?>