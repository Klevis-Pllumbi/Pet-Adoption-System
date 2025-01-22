<?php
/** @var mysqli $connection */
require "connection.php";
session_start();
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

    <?php
    $errors = [];
    if (isset($_GET['user_id'])) {

        require 'admin-navbar.php';

        $id = $_GET['user_id'];
        $sql = "SELECT * FROM users WHERE id = '$id'";
        if($result = mysqli_query($connection, $sql)) {
            $user = mysqli_fetch_assoc($result); ?>
            <div class="container">
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
                        <p>Has <span><?php echo $user['is_verified'] ? '' : 'not' ?></span> verified email.</p>
                    </div>
                </div>
            </div>
        <?php } else {
            $errors = "Database error occurred";
        }
    } else if(isset($_GET['pet_id'])) {

        if($_SESSION['id'] == 1) {
            require 'admin-navbar.php';
        } else {
            require 'navbar.php';
        }

        $id = $_GET['pet_id'];
        $sql = "SELECT pets.name, pets.gender, pets.age, pets.species, pets.breed, pets.pet_picture, pets.status, pets.adoption_fee, pets.description, users.name AS user_name, users.surname, users.email 
                FROM pets 
                INNER JOIN users ON pets.added_by = users.id 
                WHERE pets.id = '$id'";
        if($result = mysqli_query($connection, $sql)) {
            $report = mysqli_fetch_assoc($result); ?>
            <div class="container">
                <img src="<?php echo empty($report['pet_picture']) ? 'logo.png' : htmlspecialchars($report['pet_picture']) ?>" alt="<?php echo $report['name'] . '_profile_picture' ?>">
                <div class="details-container">
                    <div class="other-details">
                        <p>Name: <span><?php echo htmlspecialchars($report['name']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Gender: <span><?php echo htmlspecialchars($report['gender']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Age: <span><?php echo htmlspecialchars($report['age']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Species: <span><?php echo htmlspecialchars($report['species']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Breed: <span><?php echo htmlspecialchars($report['breed']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Status: <span><?php echo htmlspecialchars($report['status']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Adoption Fee: <span><?php echo htmlspecialchars($report['adoption_fee']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Other information: <span><?php echo htmlspecialchars($report['description']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Added by: <a href="mailto:<?php echo htmlspecialchars($report['email']) ?>"><span><?php echo htmlspecialchars($report['user_name']) . " " . htmlspecialchars($report['surname']) ?></span></a></p>
                    </div>
                </div>
            </div>
        <?php }
    } else if(isset($_GET['report_id'])) {

        require 'admin-navbar.php';

        $id = $_GET['report_id'];
        $sql = "
    SELECT 
        reports.description,
        reports.reported_at,
        reports.location,
        reports.report_picture,
        reports.status,
        users.name,
        users.surname,
        users.email
    FROM 
        reports
    INNER JOIN 
        users ON reports.user_id = users.id
    WHERE reports.id = '$id'
";
        if($result = mysqli_query($connection, $sql)) {
            $report = mysqli_fetch_assoc($result); ?>
            <div class="container">
                <img src="<?php echo empty($report['report_picture']) ? 'logo.png' : htmlspecialchars($report['report_picture']) ?>" alt="<?php echo $report['name'] . '_profile_picture' ?>">
                <div class="details-container">
                    <div class="other-details">
                        <p>Location: <span><?php echo htmlspecialchars($report['location']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Reported At: <span><?php echo htmlspecialchars($report['reported_at']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Description: <span><?php echo htmlspecialchars($report['description']) ?></span></p>
                    </div>
                    <div class="other-details">
                        <p>Reported by: <a href="mailto:<?php echo htmlspecialchars($report['email']) ?>"><span><?php echo htmlspecialchars($report['name']) . " " . htmlspecialchars($report['surname']) ?></span></a></p>
                    </div>
                    <div class="other-details">
                        <p><span><?php echo htmlspecialchars($report['status']) == 1 ? '' : 'UN' ?></span>SOLVED</p>
                    </div>
                </div>
            </div>
        <?php }
    }
    ?>
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