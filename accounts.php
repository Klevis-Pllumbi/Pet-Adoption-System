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
    <title>FurEver Home | Accounts</title>
</head>
<style>
    .details > p:last-child {
        grid-column: 1 / -1;
    }
    .details > p:first-child {
        grid-column: 1 / -1;
    }
</style>
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
    $id = $_SESSION['id'];
    $sql = "SELECT id, name, surname, adopted, surrendered, email, profile_picture FROM `users` WHERE id != '$id'";
    if ($result = mysqli_query($connection, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while($user = mysqli_fetch_assoc($result)) { ?>
                <div class="card">
                    <img src="<?php echo empty($user['profile_picture']) ? 'logo.png' : htmlspecialchars($user['profile_picture']) ?>" alt="<?php echo $user['name'] . '_profile_picture' ?>">
                    <div class="details">
                        <p>Name: <span><?php echo htmlspecialchars($user['name'] . " " . $user['surname']) ?></span></p>
                        <p>Adopted: <span><?php echo htmlspecialchars($user['adopted']) ?></span></p>
                        <p>Surrendered: <span><?php echo htmlspecialchars($user['surrendered']) ?></span></p>
                        <p>Email: <a href="mailto:<?php echo htmlspecialchars($user['email']) ?>"><span><?php echo htmlspecialchars($user['email']) ?></span></a></p>
                    </div>
                    <div class="buttons">
                        <button type="button" class="details-button" data-user-id="<?php echo htmlspecialchars($user['id']) ?>" name="details-<?php echo htmlspecialchars($user['id']) ?>">Details</button>
                        <button type="button" class="delete-button" data-user-id="<?php echo htmlspecialchars($user['id']) ?>" name="delete-<?php echo htmlspecialchars($user['id']) ?>">Delete</button>
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
    session_unset();
    session_destroy();
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
            const userId = e.target.getAttribute('data-user-id');
            window.location.href = `details.php?user_id=${userId}`;
        }
    });

    document.getElementById('card-container').addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('delete-button')) {
            const userId = e.target.getAttribute('data-user-id');
            window.location.href = `delete.php?user_id=${userId}`;
        }
    });
</script>
<script>
    document.getElementById('search').addEventListener('input', function () {
        const query = this.value;

        fetch('search-accounts.php', {
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
                console.log('Response from server:', data);
                document.getElementById('card-container').innerHTML = data;
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });
    });
</script>
</body>
</html>