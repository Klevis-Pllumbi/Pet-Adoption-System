<?php
/** @var mysqli $connection */
require "connection.php";
include "functions.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Sign Up</title>
</head>
<body>
<img class="logo" src="logo.png" alt="Logo image" width="180" height="180"></img>
<form action="signup.php" method="post" id="signup-form">
    <div class="form-group">
        <input type="text" name="name" id="name" placeholder=" " required>
        <label for="name">Name</label>
    </div>
    <div class="form-group">
        <input type="text" name="surname" id="surname" placeholder=" " required>
        <label for="surname">Surname</label>
    </div>
    <div class="form-group">
        <input type="email" name="email" id="email" placeholder=" " required>
        <label for="email">Email</label>
    </div>
    <div class="form-group">
        <input type="password" name="password" id="password" placeholder=" " required>
        <label for="password" >Create password</label>
    </div>
    <div class="form-group">
        <input type="password" name="password-confirm" id="password-confirm" placeholder=" " required>
        <label for="password-confirm">Confirm password</label>
    </div>
    <button type="submit" name="submit" id="signup">Sign Up</button>
</form>
<p>Already have an account? <a href="login.php">Log In</a></p>
<div class="error-container">
    <div class="name-error errors" id="name-error">
        <p>The name can't contain numbers or special characters.</p>
    </div>
    <div class="surname-error errors" id="surname-error">
        <p>The surname can't contain numbers or special characters.</p>
    </div>
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

        $name = htmlspecialchars(trim($_POST['name']));
        $surname = htmlspecialchars(trim($_POST['surname']));
        $email = trim($_POST['email']);
        $password = htmlspecialchars(trim($_POST['password']));
        $passwordConfirm = htmlspecialchars(trim($_POST['password-confirm']));
        $verification_token = md5(uniqid(rand(), true));

        $errors = [];

        // Validations
        if(empty($name) || empty($surname) || empty($email) || empty($password) || empty($passwordConfirm)) {
            $errors[] = "All fields are required.";
        }
        if (!preg_match('/^[a-zA-Z ]+$/', $name)) {
            $errors[] = "Only letters and white space allowed in name.";
        }
        if (!preg_match('/^[a-zA-Z ]+$/', $surname)) {
            $errors[] = "Only letters and white space allowed in surname.";
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
        if(mysqli_num_rows($result) > 0) {
            $errors[] = "That email already exists.";
        }

        if (empty($errors)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (name, surname, email, password, verification_token) 
                      VALUES ('$name', '$surname', '$email', '$hashedPassword', '$verification_token')";
            $result = mysqli_query($connection, $query);
            if(!$result) {
                $errors[] = "Something went wrong.";
            }

        }

        if(empty($errors)) {
            if(!sendVerificationEmail($email, $verification_token)) {
                $errors[] = "Something went wrong sending email verification link.";
            } else {
                $errors[] = "Please check your email for a verification link.";
            }
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
<script src="signupValidations.js"></script>
</body>
</html>