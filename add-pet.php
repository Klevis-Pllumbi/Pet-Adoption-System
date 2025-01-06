<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateUser($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Add Pet</title>
</head>
<body>

<?php require 'admin-navbar.php' ?>

<form action="add-pet.php" method="post" id="add-pet-form" enctype="multipart/form-data">
    <div class="photo-container">
        <img src="logo.png" alt="profile" id="profile-picture"></img>
        <input type="file" name="file-input" id="file-input" accept="image/*" style="display: none">
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
        <select name="select-species" id="select-species" required>
            <option value="" disabled selected>-- Select Species --</option>
            <option value="cat_breeds">Cat</option>
            <option value="dog_breeds">Dog</option>
            <option value="other">Other</option>
        </select>
        <select name="select-breed" id="select-breed">
            <option value="" disabled selected>-- Select Breed --</option>
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
<div class="error-container">
    <div class="age-error errors" id="age-error">
        <p>The age of the pet should be in the format:<br>
            age + (year|years|month|months) + old (optional)
        </p>
    </div>
    <div class="image-error errors" id="image-error">
        <p>You must provide a picture of the pet.</p>
    </div>
    <div class="breed-error errors" id="breed-error">
        <p>You must provide the breed of your pet.</p>
    </div>
    <?php
    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $age = $_POST['age'];
        $species = $_POST['select-species'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $breed = $_POST['select-breed'];

        $errors = [];

        if(empty($name) || empty($age) || empty($species) || empty($description)){
            $errors[] = "All fields are required";
        }

        if($species != "other" && empty($breed)){
            $errors[] = "Breed is required";
        }

        if(isset($_FILES['file-input']) && !empty($_FILES['file-input']['name'])){
            $targetDir = "uploads/";
            $targetFile = $targetDir . uniqid('pet_', true) . ".jpg";

            if(getimagesize($_FILES['file-input']['tmp_name'])){
                if (!move_uploaded_file($_FILES["file-input"]["tmp_name"], $targetFile)) {
                    $errors[] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $errors[] = "File is not an image.";
            }
        }

        if(empty($errors)){
            $user_id = $_SESSION['id'];
            $created_at = date("Y-m-d H:i:s", time());
            $sql = "INSERT INTO pets (name, age, species, breed, description, price, added_by, created_at, pet_picture) 
                    VALUES ('$name', '$age', '$species', '$breed', '$description', '$price', '$user_id', '$created_at', '$targetFile')";
            if(!mysqli_query($connection, $sql)){
                $errors[] = "Database error occurred!";
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
    }, 5500);
</script>
<script src="getBreeds.js"></script>
<script src="uploadPicture.js"></script>
<script src="validations.js"></script>
<script src="addPetValidations.js"></script>
</body>
</html>