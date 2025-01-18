<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateUser($connection);

$filter = $_POST['filter'] ?? 'all';
$whereClause = "status='available'";

if ($filter === 'cat') {
    $whereClause .= " AND species='cat'";
} elseif ($filter === 'dog') {
    $whereClause .= " AND species='dog'";
} elseif ($filter === 'other') {
    $whereClause .= " AND species NOT IN ('cat', 'dog')";
}

$sql = "SELECT * FROM `pets` WHERE $whereClause";

if ($result = mysqli_query($connection, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($pet = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card">
                <img src="<?php echo empty($pet['pet_picture']) ? 'logo.png' : htmlspecialchars($pet['pet_picture']) ?>" alt="<?php echo $pet['name'] . '_profile_picture' ?>">
                <div class="details">
                    <p>Name: <span><?php echo htmlspecialchars($pet['name']) ?></span></p>
                    <p>Gender: <span><?php echo htmlspecialchars($pet['gender']) ?></span></p>
                    <p>Age: <span><?php echo htmlspecialchars($pet['age']) ?></span></p>
                    <p>Fee: <span>$<?php echo htmlspecialchars($pet['adoption_fee']) ?></span></p>
                </div>
                <div class="buttons">
                    <button type="button" class="details-button" data-pet-id="<?php echo htmlspecialchars($pet['id']) ?>" name="details-<?php echo htmlspecialchars($pet['id']) ?>">Details</button>
                    <form action="create-order.php" method="post" style="gap: 0; box-shadow: none; padding: 0;">
                        <input type="hidden" name="pet_id" value="<?= $pet['id'] ?>">
                        <input type="hidden" name="fee" value="<?= $pet['adoption_fee'] ?>">
                        <button type="submit">Adopt Me</button>
                    </form>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<div class='errors show'><p>No results found.</p></div>";
    }
} else {
    echo "<div class='errors show'><p>Database error occurred.</p></div>";
}

mysqli_close($connection);