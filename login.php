<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
checkSessionTimeout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Log In</title>
</head>
<body>
    <img class="logo" src="logo.png" alt="Logo image" width="180" height="180"></img>
    <form action="login.php" method="post" id="login-form">
        <div class="form-group">
            <input type="email" name="email" id="email" placeholder=" " required>
            <label for="email">Email</label>
        </div>
        <div class="form-group">
            <input type="password" name="password" id="password" placeholder=" " required>
            <label for="password">Password</label>
        </div>
        <div class="checkbox-container">
            <input type="checkbox" value="remember_me" id="remember_me" name="remember_me">
            <label for="remember_me">Remember me</label>
        </div>
        <button type="submit" name="submit" id="login">Log In</button>
    </form>
    <a href="forgotten-password.php">Forgotten Password</a>
    <p>Do not have an account? <a href="signup.php">Sign Up</a></p>
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
        <?php

        if(isset($_POST["submit"])){

            $email = $_POST["email"];
            $password = $_POST["password"];
            $rememberMe = isset($_POST["remember_me"]);

            $errors = [];

            if(empty($email) || empty($password)){
                $errors[] = "All fields are required";
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email address";
            }

            if(empty($errors)){
                $sql = "SELECT * FROM users WHERE email = '$email'";
                $result = mysqli_query($connection, $sql);
                if(mysqli_num_rows($result) <= 0) {
                    $errors[] = "No account found with that email.";
                } else {
                    $user = mysqli_fetch_assoc($result);

                    if($user["is_verified"] == 0) {
                        $errors[] = "You need to verify your email first. Check your email.";
                    } else {
                        if($user["failed_attempts"] >= 7 && strtotime($user["lock_time"]) > time()) {
                            $errors[] = "Account has been locked.";
                        } else {
                            if(!password_verify($password, $user["password"])) {
                                $errors[] = "Wrong password.";
                                $failed_attempts = $user["failed_attempts"] + 1;
                                if($failed_attempts >= 7) {
                                    $lock_time = date("Y-m-d H:i:s", time() + 1800);
                                    $sql = "UPDATE users SET failed_attempts = '$failed_attempts', lock_time='$lock_time' WHERE email = '$email'";
                                    if(!mysqli_query($connection, $sql)) {
                                        $errors[] = "Database error happened.";
                                    }
                                } else {
                                    $sql = "UPDATE users SET failed_attempts = '$failed_attempts' WHERE email = '$email'";
                                    if(!mysqli_query($connection, $sql)) {
                                        $errors[] = "Database error happened.";
                                    }
                                }
                            } else {
                                $sql = "UPDATE users SET failed_attempts = '0', lock_time = NULL WHERE email = '$email'";
                                if(!mysqli_query($connection, $sql)) {
                                    $errors[] = "Database error happened.";
                                }
                                $_SESSION["id"] = $user["id"];
                                if($rememberMe) {
                                    $rememberToken = bin2hex(random_bytes(32));
                                    $sql = "UPDATE users SET remember_token = '$rememberToken' WHERE email = '$email'";
                                    if(!mysqli_query($connection, $sql)) {
                                        $errors[] = "Database error happened.";
                                        session_unset();
                                        session_destroy();
                                    }
                                    setcookie('remember_me', $rememberToken, time() + (86400 * 30), "/");
                                }
                                if($user["role"] == "user") {
                                    header("Location: home.php");
                                } else {
                                    header("Location: admin-home.php");
                                }
                            }
                        }
                    }
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
    <script src="loginValidations.js"></script>
    <script src="inactivity.js"></script>
</body>
</html>