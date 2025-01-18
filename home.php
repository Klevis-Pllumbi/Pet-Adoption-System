<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateUser($connection);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Home</title>
</head>
<body>

    <?php require 'navbar.php' ?>

    <div class="search-bar">
        <p>Filter : </p>
        <button type="button" data-filter="all" class="filter-btn">All</button>
        <button type="button" data-filter="cat" class="filter-btn">Cats</button>
        <button type="button" data-filter="dog" class="filter-btn">Dogs</button>
        <button type="button" data-filter="other" class="filter-btn">Others</button>
    </div>

    <div class="card-container" id="card-container"></div>
    <div class="error-container">
        <?php
        if(!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='errors show'><p>$error</p></div>";
            }
        }
        mysqli_close($connection);
        ?>
    </div>
    <script>
        const filterButtons = document.querySelectorAll('.filter-btn');
        const cardContainer = document.getElementById('card-container');

        async function fetchPets(filter) {
            try {
                const response = await fetch('filter-pets.php', {
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
        document.querySelectorAll('.adopt-button').forEach(button => {
            button.addEventListener('click', async () => {
                const petId = button.getAttribute('id');
                const response = await fetch('create-order.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ petId }),
                });

                const { approvalUrl } = await response.json();
                if (approvalUrl) {
                    window.location.href = approvalUrl;
                } else {
                    alert("Payment initialization failed.");
                }
            });
        });
    </script>
    <script>
        const elementsToHide = document.getElementsByClassName("show");
        setTimeout(() => {
            Array.from(elementsToHide).forEach((el) => el.classList.remove("show"))
        }, 5500);
    </script>
    <script>
        document.getElementById('card-container').addEventListener('click', function (e) {
            console.log('Clicked:', e.target);
            if (e.target && e.target.classList.contains('details-button')) {
                const petId = e.target.getAttribute('data-pet-id');
                window.location.href = `details.php?pet_id=${petId}`;
            }
        });
    </script>
</body>
</html>