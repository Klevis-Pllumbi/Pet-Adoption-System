
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
    <title>Log In</title>
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
    </div>
    <script src="validations.js"></script>
    <script src="loginValidations.js"></script>
</body>
</html>