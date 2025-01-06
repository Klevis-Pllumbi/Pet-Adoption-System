<?php
/** @var mysqli $connection */
require "connection.php";

if(isset($_GET["species"])){
    $species = $_GET["species"];

    $sql = "SELECT breed FROM $species";
    $result = mysqli_query($connection, $sql);

    $breeds = array();

    while($row = mysqli_fetch_assoc($result)){
        $breeds[] = $row['breed'];
    }

    mysqli_close($connection);

    echo json_encode($breeds);
}
