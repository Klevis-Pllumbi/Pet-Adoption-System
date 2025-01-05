<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Pets</title>
</head>
<body>

<?php require 'admin-navbar.php' ?>

<form action="add-pet.php" method="post" id="pets-form">
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
        <div class="form-group">
            <input type="number" name="price" id="price" placeholder=" " required>
            <label for="price">Price</label>
        </div>
        <div class="form-group">
            <textarea name="description" id="description" placeholder="Other descriptions..." required></textarea>
        </div>
    </div>
    <button type="submit" name="submit" id="submit">Add</button>
</form>

</body>
</html>