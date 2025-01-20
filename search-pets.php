<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateAdmin($connection);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])){
    $query = $_POST['query'];

    $stmt = $connection->prepare("SELECT * FROM pets WHERE name LIKE CONCAT('%', ?, '%') AND status = 'available'");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        while($pet = $result->fetch_assoc()){
            echo "<div class='card'>" .
                "<img alt='Profile Picture' src='" . (empty($pet['profile_picture']) ? 'logo.png' : htmlspecialchars($pet['profile_picture'])) . "'>" .
                "<div class='details'>
                             <p>Name: <span>" . htmlspecialchars($pet['name']) . "</span></p>
                             <p>Gender: <span>" . htmlspecialchars($pet['gender']) . "</span></p>
                             <p>Breed: <span>" . htmlspecialchars($pet['breed']) . "</span></p>
                             <p>Age: <span>" . htmlspecialchars($pet['age']) . "</span></p>
                          </div>
                          <div class='buttons'>
                              <button type='button' class='details-button' data-pet-id='" . htmlspecialchars($pet['id']) . "'>Details</button>
                              <button type='button' class='delete-button' data-pet-id='" . htmlspecialchars($pet['id']) . "'>Delete</button>
                          </div>"
                . "</div>";
        }
    } else {
        $errors[] = "No results found";
    }
}

if(!empty($errors)) {
    foreach ($errors as $error) {
        echo "<div class='errors show'><p>$error</p></div>";
    }
}
mysqli_close($connection);