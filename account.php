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
    <title>FurEver Home | Account</title>
</head>
<body>

<?php require 'navbar.php' ?>

<form action="account.php" method="post" enctype="multipart/form-data" style="gap: 20px; box-shadow: none; padding: 0;">
    <div class="container">
        <?php
        $id = $_SESSION['id'];
        $sql = "SELECT name, surname, profile_picture FROM users WHERE id = '$id'";
        $result = mysqli_query($connection, $sql);
        $user = mysqli_fetch_assoc($result);
        ?>
        <div class="photo-container">
            <img src="<?php echo empty($user['profile_picture']) ? 'logo.png' : htmlspecialchars($user['profile_picture']) ?>" alt="<?php echo $user['name'] . '_profile_picture' ?>" id="profile-picture"></img>
            <input type="file" name="file-input" id="file-input" accept="image/*" style="display: none">
            <button type="button" id="change-photo" name="change-photo">Change Photo</button>
        </div>
        <div class="profile-details">
            <div class="update-details">
                <p>Name: <span><?php echo htmlspecialchars($user['name'])?></span></p>
                <button type="button" name="change-name" id="change-name-btn" onclick="show('change-name')">Change Name</button>
                <div class="form-group" id="change-name">
                    <input type="text" name="name" id="name" placeholder=" ">
                    <label for="name">Name</label>
                </div>
            </div>
            <div class="update-details">
                <p>Surname: <span><?php echo htmlspecialchars($user['surname'])?></span></p>
                <button type="button" name="change-surname" id="change-surname-btn" onclick="show('change-surname')">Change Surname</button>
                <div class="form-group" id="change-surname">
                    <input type="text" name="surname" id="surname" placeholder=" ">
                    <label for="surname">Surname</label>
                </div>
            </div>
            <div class="update-details">
                <button type="button" id="change-password" name="change-password" onclick="redirectTo('forgotten-password.php')">Change Password</button>
            </div>
        </div>
    </div>
    <button type="submit" name="submit" id="submit" style="width: 200px">Save Changes</button>
</form>
<div class="error-container">
    <?php
    $errors = [];
    if(isset($_POST["submit"])){
        $name = $_POST["name"];
        $surname = $_POST["surname"];

        if(isset($_FILES['file-input']) && !empty($_FILES['file-input']['name'])){
            $targetDir = "uploads/";
            $targetFile = $targetDir . uniqid('user_', true) . ".jpg";

            if(getimagesize($_FILES['file-input']['tmp_name'])){
                if (!move_uploaded_file($_FILES["file-input"]["tmp_name"], $targetFile)) {
                    $errors[] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $errors[] = "File is not an image.";
            }
        }

        if(empty($name) && empty($surname) && empty($_FILES['file-input']['name'])){
            $errors[] = "You didn't make any change on your profile information!";
        }

        if (!empty($name) && !preg_match('/^[a-zA-Z ]+$/', $name)) {
            $errors[] = "Only letters and white space allowed in name.";
        } else if (empty($name)) {
            $name = $user['name'];
        }

        if (!empty($surname) && !preg_match('/^[a-zA-Z ]+$/', $surname)) {
            $errors[] = "Only letters and white space allowed in surname.";
        } else if (empty($surname)) {
            $surname = $user['surname'];
        }

        if(empty($errors)){
            if(isset($_FILES['file-input']) && !empty($_FILES['file-input']['name'])) {
                $sql = "UPDATE users SET name = '$name', surname = '$surname', profile_picture = '$targetFile' WHERE id = '$id'";
                if(!mysqli_query($connection, $sql)){
                    $errors[] = "Database error occurred!";
                }
            } else {
                $sql = "UPDATE users SET name = '$name', surname = '$surname' WHERE id = '$id'";
                if(!mysqli_query($connection, $sql)){
                    $errors[] = "Database error occurred!";
                }
            }
        }

        if(empty($errors)){
            echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                      <p style='color: antiquewhite; font-weight: bolder;'>Changes saved successfully!</p>
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
    function redirectTo(page) {
        window.location.href = page;
    }

    function show(element) {
        element = document.getElementById(element);
        element.style.display==='block' ? element.style.display='none' : element.style.display='block';

        const changeName = document.getElementById('change-name-btn');
        const changeSurname = document.getElementById('change-surname-btn');

        if(element.id === 'change-name') {
            element.style.display==='none' ? changeName.textContent='Change Name' : changeName.textContent='Leave Unchanged';
        } else {
            element.style.display==='none' ? changeSurname.textContent='Change Surname' : changeSurname.textContent='Leave Unchanged';
        }

    }
</script>
<script>
    const elementsToHide = document.getElementsByClassName("show");
    setTimeout(() => {
        Array.from(elementsToHide).forEach((el) => el.classList.remove("show"))
    }, 4500);
</script>
<script src="uploadPicture.js"></script>
</body>
</html>