<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateAdmin($connection);

$filter = $_POST['filter'] ?? 'all';

$whereClause = "adoptions.adoption_date IS NULL AND adoptions.status != 'REFUNDED'";

if ($filter === 'cat') {
    $whereClause .= " AND pets.species = 'cat'";
} elseif ($filter === 'dog') {
    $whereClause .= " AND pets.species = 'dog'";
} elseif ($filter === 'other') {
    $whereClause .= " AND pets.species NOT IN ('cat', 'dog')";
}

$sql = "
    SELECT 
        adoptions.id,
        adoptions.requested_at, 
        adoptions.transaction_id, 
        adoptions.amount, 
        users.name AS user_name, 
        users.surname, 
        users.email, 
        users.id AS user_id,
        pets.name AS pet_name,
        pets.pet_picture,
        pets.id AS pet_id
    FROM 
        adoptions
    INNER JOIN 
        users ON adoptions.user_id = users.id
    INNER JOIN 
        pets ON adoptions.pet_id = pets.id
    WHERE $whereClause
";

if ($result = mysqli_query($connection, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($adoption = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card">
                <img src="<?php echo empty($adoption['pet_picture']) ? 'logo.png' : htmlspecialchars($adoption['pet_picture']) ?>" alt="<?php echo $adoption['pet_name'] . '_profile_picture' ?>" class="img-btn" data-pet-id="<?php echo htmlspecialchars($adoption['pet_id'])?>" style="cursor: pointer;">
                <div class="details">
                    <p>Name: <span><?php echo htmlspecialchars($adoption['pet_name']) ?></span></p>
                    <p>Fee: <span>$<?php echo htmlspecialchars($adoption['amount']) ?></span></p>
                    <p>Requested by: <a><a href="mailto:<?php echo htmlspecialchars($adoption['email']) ?>"><span><?php echo htmlspecialchars($adoption['user_name']) . " " . htmlspecialchars($adoption['surname']) ?></span></a></p>
                    <p>Requested at: <span><?php echo htmlspecialchars($adoption['requested_at']) ?></span></p>
                </div>
                <div class="buttons">
                    <button type="button" class="approval-btn" id="<?php echo htmlspecialchars($adoption['pet_id'])?>" data-requester-id="<?php echo htmlspecialchars($adoption['user_id'])?>" data-adoption-id="<?php echo htmlspecialchars($adoption['id'])?>">Approve</button>
                    <form action='refund.php' method='POST' style="gap: 0; box-shadow: none; padding: 0;">
                        <input type='hidden' name='transaction_id' value='<?php echo htmlspecialchars($adoption['transaction_id']) ?>'>
                        <input type='hidden' name='refund_amount' value='<?php echo htmlspecialchars($adoption['amount']) ?>'>
                        <input type='hidden' name='pet_id' value='<?php echo htmlspecialchars($adoption['pet_id']) ?>'>
                        <button type='submit' name="refund">Reject</button>
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