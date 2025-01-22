<?php
/** @var mysqli $connection */
require "connection.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Pets</title>
</head>
<body>

<?php require 'admin-navbar.php' ?>

<div class="search-bar">
    <div class="form-group">
        <input type="text" name="search" id="search" placeholder=" " required>
        <label for="search">Search</label>
    </div>
</div>

<div class="card-container" id="card-container">
    <?php
    $errors = [];
    $sql = "SELECT id, name, age, gender, breed, pet_picture FROM `pets` WHERE status = 'available'";
    if ($result = mysqli_query($connection, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while($pet = mysqli_fetch_assoc($result)) { ?>
                <div class="card">
                    <img src="<?php echo empty($pet['pet_picture']) ? 'logo.png' : htmlspecialchars($pet['pet_picture']) ?>" alt="<?php echo $pet['name'] . '_pet_picture' ?>">
                    <div class="details">
                        <p>Name: <span><?php echo htmlspecialchars($pet['name']) ?></span></p>
                        <p>Gender: <span><?php echo htmlspecialchars($pet['gender']) ?></span></p>
                        <p>Breed: <span><?php echo htmlspecialchars($pet['breed']) ?></span></p>
                        <p>Age: <span><?php echo htmlspecialchars($pet['age']) ?></span></p>
                    </div>
                    <div class="buttons">
                        <button type="button" class="details-button" data-pet-id="<?php echo htmlspecialchars($pet['id']) ?>">Details</button>
                        <button type="button" class="delete-button" data-pet-id="<?php echo htmlspecialchars($pet['id']) ?>">Delete</button>
                    </div>
                </div>
            <?php }
        } else {
            $errors[] = "No results found";
        }
    } else {
        $errors[] = "Database error occurred";
    }
    ?>
</div>
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
    const elementsToHide = document.getElementsByClassName("show");
    setTimeout(() => {
        Array.from(elementsToHide).forEach((el) => el.classList.remove("show"))
    }, 5500);
</script>
<script>
    document.getElementById('card-container').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('details-button')) {
            const petId = e.target.getAttribute('data-pet-id');
            window.location.href = `details.php?pet_id=${petId}`;
        }
    });

    document.getElementById('card-container').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('delete-button')) {
            const petId = e.target.getAttribute('data-pet-id');
            window.location.href = `delete.php?pet_id=${petId}`;
        }
    });
</script>
<script>
    document.getElementById('search').addEventListener('input', function () {
        const query = this.value;

        fetch('search-pets.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'query=' + encodeURIComponent(query)
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById('card-container').innerHTML = data;
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    });
</script>
</body>
</html>