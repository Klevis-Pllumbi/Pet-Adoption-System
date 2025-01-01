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
    <title>FurEver Home | Profile</title>
</head>
<body>

<div class="navbar">
    <div class="logo">
        <img src="icon.png" alt="logo">
        <h1>FurEver Home</h1>
    </div>
    <div class="menu">
        <a href="home.php" class="nav">Home</a>
        <a href="profile.php" class="nav">Profile</a>
        <a href="donate.php" class="nav">Donate</a>
        <a href="give-up-for-adoption.php" class="nav">Give Up For Adoption</a>
        <a href="report.php" class="nav">Report</a>
        <a href="index.php" class="nav">Contact</a>
    </div>
</div>

<div class="profile-container">
    <div class="profile-image">
        <img src="logo.png" alt="profile"></img>
        <button type="button" id="change-photo" name="change-photo">Change Photo</button>
    </div>
    <div class="profile-details">
        <div class="change">
            <p>Name: <span>Klevis</span></p>
            <button type="button" name="change-name" onclick="show('change-name')">Change Name</button>
            <div class="form-group" id="change-name">
                <input type="text" name="name" id="name" placeholder=" " required>
                <label for="name">Name</label>
            </div>
        </div>
        <div class="change">
            <p>Surname: <span>Pllumbi</span></p>
            <button type="button" name="change-surname" onclick="show('change-surname')">Change Surname</button>
            <div class="form-group" id="change-surname">
                <input type="text" name="surname" id="surname" placeholder=" " required>
                <label for="surname">Surname</label>
            </div>
        </div>
        <div class="change">
            <p>Email: <span>pllumbiklevis1@gmail.com</span></p>
            <button type="button" name="change-email" onclick="show('change-email')">Change Email</button>
            <div class="form-group" id="change-email">
                <input type="email" name="email" id="email" placeholder=" " required>
                <label for="email">Email</label>
            </div>
        </div>
        <div class="change">
            <button type="button" id="change-password" name="change-password" onclick="redirectTo('forgotten-password.php')">Change Password</button>
        </div>
    </div>
</div>

<script>
    function redirectTo(page) {
        window.location.href = page;
    }

    function show(element) {
        element = document.getElementById(element);
        element.style.display==='block' ? element.style.display='none' : element.style.display='block';
    }
</script>
</body>
</html>