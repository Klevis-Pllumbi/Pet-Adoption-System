<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateUser($connection);

if(isset($_GET["user_id"])){
    $id = $_GET["user_id"];
    $sql = "DELETE FROM users WHERE id='$id'";
    if(mysqli_query($connection, $sql)) {
        header("Location: accounts.php");
    } else {
        echo "Error deleting record. Try again.";
    }

}