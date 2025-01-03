<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Report</title>
</head>
<body>

<?php require 'navbar.php' ?>

<form action="report.php" method="post" id="report-form">
    <div class="photo-container">
        <img src="logo.png" alt="profile" id="profile-picture"></img>
        <input type="file" name="file-input" id="file-input" accept="image/*" hidden required>
        <button type="button" id="change-photo" name="change-photo">Upload Photo</button>
    </div>
    <div class="pet-information">
        <h3><span>Report Information</span></h3>
        <p>Here you can report abuses or injured stray animals to let us know how to help them.<br>
            Give the information required please.<br>
            If you are not sharing the location directly in the place write it in the location field.
        </p>
        <button id="getLocation" style="width: 180px; border-radius: 20px;">Share Location</button>
        <p id="status"></p>
        <input type="hidden" name="latitude" id="latitude" required>
        <input type="hidden" name="longitude" id="longitude" required>
        <div class="form-group">
            <input type="text" name="location" id="location" placeholder=" ">
            <label for="location">Location</label>
        </div>
        <div class="form-group">
            <textarea name="description" id="description" placeholder="Other descriptions..." required></textarea>
        </div>
    </div>
    <button type="submit" name="submit" id="submit">Submit</button>
</form>
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
</body>
</html>