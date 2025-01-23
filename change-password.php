<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
checkSessionTimeout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Change Password</title>
</head>
<body>
<img class="logo" src="logo.png" alt="Logo image" width="180" height="180"></img>
<form action="change-password.php" method="post" id="forgotten-password-form">
    <div class="form-group">
        <input type="email" name="email" id="email" placeholder=" " required>
        <label for="email">Email</label>
    </div>
    <div class="form-group">
        <input type="password" name="password" id="password" placeholder=" " required>
        <label for="password">New Password</label>
    </div>
    <div class="form-group">
        <input type="password" name="password-confirm" id="password-confirm" placeholder=" " required>
        <label for="password-confirm">Confirm New Password</label>
    </div>
    <button type="submit" name="submit" id="change">Change</button>
</form>
<div class="error-container">
    <div class="email-error errors" id="email-error">
        <p>The email provided is not a valid email.</p>
    </div>
    <div class="password-error errors" id="password-error">
        <p><strong>Password Criteria</strong><br>
            At least 8 characters.<br>
            Contains at least one uppercase letter.<br>
            Contains at least one lowercase letter.<br>
            Contains at least one digit.<br>
            Contains at least one special character (e.g., @, #, $, etc.).</p>
    </div>
    <div class="password-confirm-error errors" id="password-confirm-error">
        <p>The confirmed password is not the same as the first.</p>
    </div>
    <?php

    if (isset($_POST['submit'])) {

        $email = trim($_POST['email']);
        $password = htmlspecialchars(trim($_POST['password']));
        $passwordConfirm = htmlspecialchars(trim($_POST['password-confirm']));
        $verification_token = md5(uniqid(rand(), true));

        $errors = [];

        if(empty($email) || empty($password) || empty($passwordConfirm)) {
            $errors[] = "All fields are required.";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email address.";
        }
        if ($password !== $passwordConfirm) {
            $errors[] = "Passwords do not match.";
        }
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $errors[] = "Password does not meet criteria.";
        }

        $result = mysqli_query($connection, "SELECT * FROM users WHERE email = '$email'");
        $user = mysqli_fetch_assoc($result);
        if(!$user) {
            $errors[] = "That email does not exist.";
        } else {
            $subject = 'Verification Email';
            $body = "<h2>You have been signed up!</h2>
                     <p>Verify your email with the link below.</p>
                     <br>
                     <a href='http://localhost/Pet%20Adoption%20System/verify-email.php?token=$verification_token'>Click me</a>";

            if(!sendEmail($email, $subject, $body)) {
                $errors[] = "Something went wrong sending email verification link.";
            }
        }

        if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = "UPDATE users SET password = '$hashedPassword', is_verified = 0, verification_token = '$verification_token' WHERE email = '$email'";
            $result = mysqli_query($connection, $query);
            if(!$result) {
                $errors[] = "Something went wrong.";
            }

            echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                      <p style='color: antiquewhite;'>Please check your email for a verification link.</p>
                  </div>";
        }

        if(!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='errors show'><p>$error</p></div>";
            }
        }

        mysqli_close($connection);
    }
    ?>
</div>
<script>
    const elementsToHide = document.getElementsByClassName("show");
    setTimeout(() => {
        Array.from(elementsToHide).forEach((el) => el.classList.remove("show"))
    }, 4500);
</script>
<script src="validations.js"></script>
<script src="forgottenPasswordValidations.js"></script>
<script src="inactivity.js"></script>
</body>
</html>
