<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Reports</title>
    <style>
        .details > p {
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
            <p>Reported by: <a href="mailto:pllumbiklevis1@gmail.com"><span>pllumbiklevis1@gmail.com</span></a></p>
            <p>Location: <span>41.4973952, 20.0933376</span></p>
        </div>
        <button type="button" id="id" name="id">Details</button>
    </div>
    <div class="card">
        <img src="background.jpg" alt="">
        <div class="details">
            <p>Reported by: <a href="mailto:pllumbiklevis1@gmail.com"><span>pllumbiklevis1@gmail.com</span></a></p>
            <p>Location: <span>41.4973952, 20.0933376</span></p>
        </div>
        <button type="button" id="id" name="id">Details</button>
    </div>
    <div class="card">
        <img src="background.jpg" alt="">
        <div class="details">
            <p>Reported by: <a href="mailto:pllumbiklevis1@gmail.com"><span>pllumbiklevis1@gmail.com</span></a></p>
            <p>Location: <span>41.4973952, 20.0933376</span></p>
        </div>
        <button type="button" id="id" name="id">Details</button>
    </div>
</div>
</body>
</html>