<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Account</title>
</head>
<body>

<?php require 'navbar.php' ?>

<div class="container">
    <div class="photo-container">
        <img src="logo.png" alt="profile" id="profile-picture"></img>
        <input type="file" name="file-input" id="file-input" accept="image/*" style="display: none">
        <button type="button" id="change-photo" name="change-photo">Change Photo</button>
    </div>
    <div class="profile-details">
        <div class="update-details">
            <p>Name: <span>Klevis</span></p>
            <button type="button" name="change-name" onclick="show('change-name')">Change Name</button>
            <div class="form-group" id="change-name">
                <input type="text" name="name" id="name" placeholder=" " required>
                <label for="name">Name</label>
            </div>
        </div>
        <div class="update-details">
            <p>Surname: <span>Pllumbi</span></p>
            <button type="button" name="change-surname" onclick="show('change-surname')">Change Surname</button>
            <div class="form-group" id="change-surname">
                <input type="text" name="surname" id="surname" placeholder=" " required>
                <label for="surname">Surname</label>
            </div>
        </div>
        <div class="update-details">
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