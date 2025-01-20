<?php
/** @var mysqli $connection */
require "connection.php";
require "functions.php";
session_start();
authenticateAdmin($connection);

$filter = $_POST['filter'] ?? 'all';

$sql = "
    SELECT 
        reports.id,
        reports.description,
        reports.reported_at,
        reports.location,
        reports.report_picture,
        reports.status,
        users.name,
        users.surname,
        users.email
    FROM 
        reports
    INNER JOIN 
        users ON reports.user_id = users.id
";

if ($filter === 'solved') {
    $sql .= " WHERE reports.status = 1";
} elseif ($filter === 'unsolved') {
    $sql .= " WHERE reports.status = 0";
}

if ($result = mysqli_query($connection, $sql)) {
    if (mysqli_num_rows($result) > 0) {
        while ($report = mysqli_fetch_assoc($result)) {
            ?>
            <div class="card">
            <img src="<?php echo empty($report['report_picture']) ? 'logo.png' : htmlspecialchars($report['report_picture']) ?>" alt="<?php echo $report['id'] . '_report_picture' ?>">
            <div class="details">
                <p>Report by: <a><a href="mailto:<?php echo htmlspecialchars($report['email']) ?>"><span><?php echo htmlspecialchars($report['name']) . " " . htmlspecialchars($report['surname']) ?></span></a></p>
                <p>Location: <span><?php echo htmlspecialchars($report['location']) ?></span></p>
            </div>
            <div class="buttons">
                <button type="button" class="details-button" data-report-id="<?php echo htmlspecialchars($report['id']) ?>">Details</button>
                <?php if($report['status'] == 0) { ?>
                <button type="button" class="solved-button" data-report-id="<?php echo htmlspecialchars($report['id']) ?>">Solved</button>
                <?php } ?>
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