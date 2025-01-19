<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateAdmin($connection);
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
            <option value="cat">Cat</option>
            <option value="dog">Dog</option>
            <option value="other">Other</option>
        </select>
        <div class="form-group" style="display: none;" id="other-species-group">
            <input type="text" name="other-species" id="other-species" placeholder=" ">
            <label for="other-species">Species</label>
        </div>
        <select name="select-breed" id="select-breed">
            <option value="" disabled selected>-- Select Breed --</option>
        </select>
        <div class="radio-button-container">
            <div class="radio-button">
                <input type="radio" id="female" name="radio-button" value="Female">
                <label for="female">Female</label>
            </div>
            <div class="radio-button">
                <input type="radio" id="male" name="radio-button" value="Male">
                <label for="male">Male</label>
            </div>
        </div>
        <div class="form-group">
            <input type="number" name="adoption-fee" id="adoption-fee" placeholder=" " required>
            <label for="adoption-fee">Adoption Fee</label>
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
            age + (year | years | month | months) + old (optional)
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
        $adoptionFee = $_POST['adoption-fee'];
        $description = $_POST['description'];
        $breed = $_POST['select-breed'] ?? null;
        $gender = $_POST['radio-button'] ?? null;
        $otherSpecies = $_POST['other-species'];

        $errors = [];

        if(empty($name) || empty($age) || empty($species) || empty($description) || empty($gender)){
            $errors[] = "All fields are required";
        }

        if($species != "other" && empty($breed)){
            $errors[] = "Breed is required";
        }

        if($species == "other" && empty($otherSpecies)){
            $errors[] = "Species is required";
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
        } else {
            $errors[] = "You must provide a picture of the pet.";
        }

        if(empty($errors)){
            $user_id = $_SESSION['id'];
            $created_at = date("Y-m-d H:i:s", time());
            if($species == "other") {
                $species = $otherSpecies;
                $breed = $otherSpecies;
            }

            $sql = "INSERT INTO pets (name, age, species, breed, gender, description, adoption_fee, added_by, created_at, pet_picture) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ssssssiiss", $name, $age, $species, $breed, $gender, $description, $fee, $user_id, $created_at, $targetFile);
            if (!$stmt->execute()) {
                $errors[] = "Database error occurred!";
            }
            $stmt->close();
        }

        if(empty($errors)){
            echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                      <p style='color: antiquewhite; font-weight: bolder;'>Pet registered successfully!</p>
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
<script src="getBreeds.js"></script>
<script src="uploadPicture.js"></script>
<script src="validations.js"></script>
<script src="addPetValidations.js"></script>
</body>
</html>