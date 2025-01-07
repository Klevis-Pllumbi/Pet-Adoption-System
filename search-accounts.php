<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateUser($connection);

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['query'])){
    $query = $_POST['query'];
    $id = $_SESSION["id"];

    $stmt = $connection->prepare("SELECT * FROM users WHERE name LIKE CONCAT('%', ?, '%') AND id != '$id'");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        while($user = $result->fetch_assoc()){
            echo "<div class='card'>" .
                "<img alt='Profile Picture' src='" . (empty($user['profile_picture']) ? 'logo.png' : htmlspecialchars($user['profile_picture'])) . "'>" .
                "<div class='details'>
                             <p>Name: <span>" . htmlspecialchars($user['name'] . " " . $user['surname']) . "</span></p>
                             <p>Adopted: <span>" . htmlspecialchars($user['adopted']) . "</span></p>
                             <p>Surrenders: <span>" . htmlspecialchars($user['surrendered']) . "</span></p>
                             <p>Email: <span>" . htmlspecialchars($user['email']) . "</span></p>
                          </div>
                          <div class='buttons'>
                              <button type='button' class='details-button' data-user-id='" . htmlspecialchars($user['id']) . "' name='delete-" . htmlspecialchars($user['id']) . "'>Details</button>
                              <button type='button' class='delete-button' data-user-id='" . htmlspecialchars($user['id']) . "' name='delete-" . htmlspecialchars($user['id']) . "'>Delete</button>
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
session_unset();
session_destroy();
mysqli_close($connection);