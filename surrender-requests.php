<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Surrender Requests</title>
    <style>
        .details > p:last-child {
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>

<?php require 'admin-navbar.php' ?>

<div class="search-bar">
    <p>Filter : </p>
    <button type="button" id="all" name="all">All</button>
    <button type="button" id="cat" name="cat">Cats</button>
    <button type="button" id="dog" name="dog">Dogs</button>
    <button type="button" id="other" name="other">Others</button>
</div>

<div class="card-container">
    <div class="card">
        <img src="background.jpg" alt="">
        <div class="details">
            <p>Name: <span>Bonnie</span></p>
            <p>Gender: <span>Female</span></p>
            <p>Age: <span>6 years</span></p>
            <p><a href=""><span>Other details</span></a></p>
            <p>Surrendered by: <span>User1</span></p>
        </div>
        <button type="button" id="id" name="id">Approve</button>
    </div>
    <div class="card">
        <img src="background.jpg" alt="">
        <div class="details">
            <p>Name: <span>Bonnie</span></p>
            <p>Gender: <span>Female</span></p>
            <p>Age: <span>6 years</span></p>
            <p><a href=""><span>Other details</span></a></p>
            <p>Surrendered by: <span>User1</span></p>
        </div>
        <button type="button" id="id" name="id">Approve</button>
    </div>
</div>
</body>
</html>