<?php
/** @var mysqli $connection */
require_once "functions.php";
authenticateAdmin($connection);
checkSessionTimeout();
?>
<div class="navbar">
    <div class="logo">
        <img src="icon.png" alt="logo">
        <h1>FurEver Home</h1>
    </div>
    <div class="menu">
        <a href="admin-home.php" class="nav">Home</a>
        <a href="add-pet.php" class="nav">Add Pet</a>
        <a href="reports.php" class="nav">Reports</a>
        <a href="surrender-requests.php" class="nav">Surrender Requests</a>
        <a href="payments_donations.php" class="nav">Donations & Payments</a>
        <a href="accounts.php" class="nav">Accounts</a>
        <a href="pets.php" class="nav">Pets</a>
        <a href="logout.php" class="nav">Log Out</a>
    </div>
</div>