<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Give up for Adoption</title>
</head>
<body>

<?php require 'navbar.php' ?>

<form action="give-up-for-adoption.php" method="post" id="give-up-for-adoption-form">
    <div class="photo-container">
        <img src="logo.png" alt="profile" id="profile-picture"></img>
        <input type="file" name="file-input" id="file-input" accept="image/*" style="display: none" required>
        <button type="button" id="change-photo" name="change-photo">Upload Photo</button>
    </div>
    <div class="pet-information">
        <h3><span>Pet Information</span></h3>
        <div class="form-group">
            <input type="text" name="name" id="name" placeholder=" " required>
            <label for="name">Name</label>
        </div>
        <div class="form-group">
            <input type="text" name="age" id="age" placeholder=" " required>
            <label for="age">Age</label>
        </div>
        <select name="select-specie" id="select-specie" required>
            <option value="" disabled selected>Species</option>
            <option value="1">Cat</option>
            <option value="2">Dog</option>
            <option value="3">Other</option>
        </select>
        <div class="form-group" style="display: none;">
            <input type="text" name="species" id="species" placeholder=" " required>
            <label for="species">Species</label>
        </div>
        <select name="select-breed" id="select-breed" required>
            <option value="" disabled selected>Breed</option>
            <option value="1">Test 1</option>
            <option value="2">Test 2</option>
            <option value="3">Test 3</option>
        </select>
        <div class="radio-button-container">
            <div class="radio-button">
                <input type="radio" id="female" name="radio-button" value="female">
                <label for="female">Female</label>
            </div>
            <div class="radio-button">
                <input type="radio" id="male" name="radio-button" value="male">
                <label for="male">Male</label>
            </div>
        </div>
        <div class="form-group">
            <textarea name="description" id="description" placeholder="Other descriptions..." required></textarea>
        </div>
        <div class="checkbox-container" style="margin-left: 0;">
            <input type="checkbox" value="surrender" id="surrender" name="surrender">
            <label for="surrender">Surrender</label>
        </div>
    </div>
    <button type="submit" name="submit" id="submit">Submit</button>
</form>

</body>
</html>