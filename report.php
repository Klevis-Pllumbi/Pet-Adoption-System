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
    <title>FurEver Home | Report</title>
</head>
<body>

<?php require 'navbar.php' ?>

<form action="report.php" method="post" id="report-form" enctype="multipart/form-data">
    <div class="photo-container">
        <img src="logo.png" alt="profile" id="profile-picture"></img>
        <input type="file" name="file-input" id="file-input" accept="image/*" hidden>
        <button type="button" id="change-photo" name="change-photo">Upload Photo</button>
    </div>
    <div class="pet-information">
        <h3><span>Report Information</span></h3>
        <p>Here you can report abuses or injured stray animals to let us know how to help them.<br>
            Give the information required please.<br>
            If you are not sharing the location directly in the place write it in the location field.
        </p>
        <button type = "button" id="getLocation" style="width: 180px; border-radius: 20px;">Share Location</button>
        <p id="status"></p>
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
        <div class="form-group">
            <input type="text" name="location" id="location" placeholder=" ">
            <label for="location">Location</label>
        </div>
        <div class="form-group">
            <textarea name="description" id="description" placeholder="Other descriptions..." required></textarea>
        </div>
    </div>
    <button type="submit" name="submit" id="submit">Report</button>
</form>
<div class="error-container">
    <?php
    if(isset($_POST['submit'])){
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $location = $_POST['location'];
        $description = $_POST['description'];

        $errors = [];

        if((empty($latitude) || empty($longitude)) && empty($location)){
            $errors[] = "A location is required";
        }

        if(empty($description)){
            $errors[] = "A description is required";
        }

        if(isset($_FILES['file-input']) && !empty($_FILES['file-input']['name'])){
            $targetDir = "uploads/";
            $targetFile = $targetDir . uniqid('report_', true) . ".jpg";

            if(getimagesize($_FILES['file-input']['tmp_name'])){
                if (!move_uploaded_file($_FILES["file-input"]["tmp_name"], $targetFile)) {
                    $errors[] = "Sorry, there was an error uploading your file.";
                }
            } else {
                $errors[] = "File is not an image.";
            }
        } else {
            $errors[] = "You must provide a picture of the situation.";
        }

        if(empty($errors)){
            $user_id = $_SESSION['id'];
            $reportedAt = date("Y-m-d H:i:s", time());
            if(!empty($latitude) || !empty($longitude)){
                $location = $latitude . ", " . $longitude;
            }

            $sql = "INSERT INTO reports (user_id, description, reported_at, location, report_picture) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $connection->prepare($sql);
            $stmt->bind_param("issss", $user_id, $description, $reportedAt, $location, $targetFile);
            if (!$stmt->execute()) {
                $errors[] = "Database error occurred!";
            }
            $stmt->close();
        }

        if(empty($errors)){
            echo "<div class='errors show' style='background-color: rgba(131, 173, 68)'>
                      <p style='color: antiquewhite; font-weight: bolder;'>Reported successfully! Thank you for your care!</p>
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
    const getLocationButton = document.getElementById('getLocation');
    const status = document.getElementById('status');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    getLocationButton.addEventListener('click', () => {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const { latitude, longitude } = position.coords;
                    status.textContent = `Location captured: ${latitude}, ${longitude}`;
                    latitudeInput.value = latitude;
                    longitudeInput.value = longitude;
                },
                (error) => {
                    status.textContent = `Error: ${error.message}`;
                }
            );
        } else {
            status.textContent = "Geolocation is not supported by your browser.";
        }
    });
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