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
    <title>FurEver Home | Details</title>
    <style>
        img {
            border-radius: 10px;
            width: 400px;
            height: 400px;
            object-fit: cover;
            box-shadow: 0 0 20px rgba(205, 133, 63, 0.2);
        }

        .other-details {
            padding-top: 5px;
            padding-bottom: 5px;
        }
    </style>
</head>
<body>

<?php require 'navbar.php' ?>

<div class="container">
    <?php
    $errors = [];
    if (isset($_GET['user_id'])) {
        $id = $_GET['user_id'];
        $sql = "SELECT * FROM users WHERE id = '$id'";
        if($result = mysqli_query($connection, $sql)) {
            $user = mysqli_fetch_assoc($result); ?>
            <img src="<?php echo empty($user['profile_picture']) ? 'logo.png' : htmlspecialchars($user['profile_picture']) ?>" alt="<?php echo $user['name'] . '_profile_picture' ?>" id="profile-picture">
            <div class="details-container" style="gap: 15px;">
                <div class="other-details">
                    <p>Name: <span><?php echo htmlspecialchars($user['name']) ?></span></p>
                </div>
                <div class="other-details">
                    <p>Surname: <span><?php echo htmlspecialchars($user['surname']) ?></span></p>
                </div>
                <div class="other-details">
                    <p>Email: <a href="mailto:<?php echo htmlspecialchars($user['email']) ?>"><span><?php echo htmlspecialchars($user['email']) ?></span></a></p>
                </div>
                <div class="other-details">
                    <p>Adopted: <span><?php echo htmlspecialchars($user['adopted']) ?></span></p>
                </div>
                <div class="other-details">
                    <p>Surrendered: <span><?php echo htmlspecialchars($user['surrendered']) ?></span></p>
                </div>
                <div class="other-details">
                    <p>Has <span><?php echo $user['is_verified'] ? '' : 'not' ?></span> verified email.</p>
                </div>
            </div>
        <?php } else {
            $errors = "Database error occurred";
        }
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
</body>
</html>