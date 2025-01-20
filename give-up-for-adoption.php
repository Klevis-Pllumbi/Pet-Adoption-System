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
    <title>FurEver Home | Give up for Adoption</title>
</head>
<body>

<?php require 'navbar.php' ?>

<form action="give-up-for-adoption.php" method="post" id="give-up-for-adoption-form" enctype="multipart/form-data">
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
            <textarea name="description" id="description" placeholder="Other descriptions..." required></textarea>
        </div>
        <div class="checkbox-container" style="margin-left: 0;">
            <input type="checkbox" value="surrender" id="surrender" name="surrender">
            <label for="surrender">Surrender</label>
        </div>
        <div class="form-group">
            <textarea name="reason" id="reason" placeholder="Reason..." style="display: none;"></textarea>
        </div>
    </div>
    <button type="submit" name="submit" id="submit">Submit</button>
</form>
<div class="error-container">
    <?php
    if(isset($_POST['submit'])){
        $name = $_POST['name'];
        $age = $_POST['age'];
        $species = $_POST['select-species'];
        $breed = $_POST['select-breed'] ?? null;
        $description = $_POST['description'];
        $gender = $_POST['radio-button'] ?? null;
        $otherSpecies = $_POST['other-species'] ?? null;

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

        if(!preg_match('/^(\d+)\s*(year|years|month|months)(\s*old)?\s*$/i', $age)) {
            $errors[] = "The age of the pet should be in the format:<br>
                         age + (year | years | month | months) + old (optional)";
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
            if($species == 'other') {
                $species = $otherSpecies;
                $breed = $otherSpecies;
            }
            $user_id = $_SESSION['id'];
            $created_at = date("Y-m-d H:i:s", time());
            $fee = 0;

            $sql = "INSERT INTO pets (name, age, species, breed, gender, description, adoption_fee, added_by, created_at, pet_picture) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("ssssssiiss", $name, $age, $species, $breed, $gender, $description, $fee, $user_id, $created_at, $targetFile);
            if (!$stmt->execute()) {
                $errors[] = "Database error occurred!";
            }
            $stmt->close();

            if(isset($_POST['surrender'])) {
                $reason = $_POST['reason'] ?? null;
                if(empty($reason)) {
                    $errors[] = "Reason is required";
                } else {
                    $pet_id = mysqli_insert_id($connection);
                    if(!empty($pet_id)) {
                        $sql = "INSERT INTO surrendered_pets (surrendered_by, pet_id, reason, requested_at)
                                VALUES (?, ?, ?, ?)";
                        $stmt = $connection->prepare($sql);
                        $stmt->bind_param("iiss", $user_id, $pet_id, $reason, $created_at);
                        if (!$stmt->execute()) {
                            $errors[] = "Database error occurred!";
                        }
                        $stmt->close();
                    } else {
                        $errors[] = "Something went wrong";
                    }
                }
            }
        }

        if(empty($errors)){
            if(isset($_POST['surrender'])) {
                echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                                     <p style='color: antiquewhite;'>Pet registered successfully!<br>
                                     You will get an email soon for your surrender request!</p>
                                  </div>";
            } else {
                echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                      <p style='color: antiquewhite;'>Pet registered successfully!</p>
                  </div>";
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
<script src="getBreeds.js"></script>
<script src="uploadPicture.js"></script>
</body>
</html>