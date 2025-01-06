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
        <input type="text" name="name" id="name" placeholder=" " required>
        <label for="name">Search</label>
    </div>
</div>

<div class="card-container">
    <?php
    $errors = [];
    $sql = "SELECT id, name, surname, adopted, surrendered, email, profile_picture FROM `users`";
    if ($result = mysqli_query($connection, $sql)) {
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
            <button type="button" id="delete-<?php echo htmlspecialchars($user['id']) ?>" name="delete-<?php echo htmlspecialchars($user['id']) ?>">Delete</button>
        </div>
    </div>
        <?php }
    } else {
        $errors = "Database error occurred";
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
    document.addEventListener("DOMContentLoaded", function () {
        const detailButtons = document.querySelectorAll(".details-button");

        detailButtons.forEach(button => {
            button.addEventListener("click", function () {
                const userId = this.getAttribute("data-user-id");

                window.location.href = `details.php?user_id=${userId}`;
            });
        });
    });
</script>
</body>
</html>