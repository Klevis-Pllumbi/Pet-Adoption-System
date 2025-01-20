<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateAdmin($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Admin Home</title>
    <style>
        .details > p:last-child {
            grid-column: 1 / -1;
        }
        .details > p:nth-child(3) {
            grid-column: 1 / -1;
        }
    </style>
</head>
<body>

<?php require 'admin-navbar.php' ?>

<div class="search-bar">
    <p>Filter : </p>
    <button type="button" data-filter="all" class="filter-btn">All</button>
    <button type="button" data-filter="cat" class="filter-btn">Cats</button>
    <button type="button" data-filter="dog" class="filter-btn">Dogs</button>
    <button type="button" data-filter="other" class="filter-btn">Others</button>
</div>

<div class="card-container" id="card-container"></div>

<?php mysqli_close($connection); ?>

<script>
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cardContainer = document.getElementById('card-container');

    async function fetchPets(filter) {
        try {
            const response = await fetch('filter-adoptions.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `filter=${encodeURIComponent(filter)}`,
            });

            cardContainer.innerHTML = await response.text();
        } catch (error) {
            console.error('Error fetching pets:', error);
            cardContainer.innerHTML = '<div class="errors show"><p>An error occurred while fetching data.</p></div>';
        }
    }

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            fetchPets(filter);
        });
    });

    fetchPets('all');
</script>
<script>
    document.getElementById('card-container').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('img-btn')) {
            const petId = e.target.getAttribute('data-pet-id');
            window.location.href = `details.php?pet_id=${petId}`;
        }
    });

    document.getElementById('card-container').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('approval-btn')) {
            const petId = e.target.getAttribute('id');
            const requesterId = e.target.getAttribute('data-requester-id');
            const adoptionId = e.target.getAttribute('data-adoption-id');
            window.location.href = `send-email.php?pet_id=${petId}&requester_id=${requesterId}&adoption_id=${adoptionId}`;
        }
    });
</script>
</body>
</html>