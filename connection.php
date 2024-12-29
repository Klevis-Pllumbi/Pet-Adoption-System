<?php

$host = "localhost";
$user = "root";
$password = "";
$database = "pet_adoption_system";
$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die("<p>Connection failed</p>");
}
