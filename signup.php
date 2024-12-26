<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Righteous&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poiret+One&amp;family=Righteous&amp;display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="icon.png">
    <link rel="stylesheet" href="style.css">
    <title>Sign Up</title>
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
</div>
<script src="validations.js"></script>
<script src="signupValidations.js"></script>
</body>
</html>