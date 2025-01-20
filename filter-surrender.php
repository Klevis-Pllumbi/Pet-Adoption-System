<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateAdmin($connection);

$filter = $_POST['filter'] ?? 'all';

$whereClause = "surrendered_pets.surrendered_at IS NULL";

if ($filter === 'cat') {
    $whereClause .= " AND pets.species = 'cat'";
} elseif ($filter === 'dog') {
    $whereClause .= " AND pets.species = 'dog'";
} elseif ($filter === 'other') {
    $whereClause .= " AND pets.species NOT IN ('cat', 'dog')";
}

$sql = "
    SELECT 
        surrendered_pets.id,
        surrendered_pets.requested_at, 
        users.name AS user_name, 
        users.surname, 
        users.email, 
        users.id AS user_id,
        pets.name AS pet_name,
        pets.gender,
        pets.age,
        pets.pet_picture,
        pets.id AS pet_id
    FROM 
        surrendered_pets
    INNER JOIN 
        users ON surrendered_pets.surrendered_by = users.id
    INNER JOIN 
        pets ON surrendered_pets.pet_id = pets.id
    WHERE $whereClause
";

if ($result = mysqli_query($connection, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($surrender = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card">
                <img src="<?php echo empty($surrender['pet_picture']) ? 'logo.png' : htmlspecialchars($surrender['pet_picture']) ?>" alt="<?php echo $surrender['pet_name'] . '_profile_picture' ?>" class="img-btn" data-pet-id="<?php echo htmlspecialchars($surrender['pet_id'])?>" style="cursor: pointer;">
                <div class="details">
                    <p>Name: <span><?php echo htmlspecialchars($surrender['pet_name']) ?></span></p>
                    <p>Gender: <span><?php echo htmlspecialchars($surrender['gender']) ?></span></p>
                    <p>Requested at: <span><?php echo htmlspecialchars($surrender['requested_at']) ?></span></p>
                    <p>Requested by: <a><a href="mailto:<?php echo htmlspecialchars($surrender['email']) ?>"><span><?php echo htmlspecialchars($surrender['user_name']) . " " . htmlspecialchars($surrender['surname']) ?></span></a></p>
                </div>
                <div class="buttons">
                    <button type="button" class="approval-btn" id="<?php echo htmlspecialchars($surrender['pet_id'])?>" data-requester-id="<?php echo htmlspecialchars($surrender['user_id'])?>" data-surrender-id="<?php echo htmlspecialchars($surrender['id'])?>">Approve</button>
                    <button type="button" class="reject-btn" id="<?php echo htmlspecialchars($surrender['pet_id'])?>" data-requester-id="<?php echo htmlspecialchars($surrender['user_id'])?>" data-surrender-id="<?php echo htmlspecialchars($surrender['id'])?>">Reject</button>
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