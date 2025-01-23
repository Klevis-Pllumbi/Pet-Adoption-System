<?php
/** @var mysqli $connection */
require "connection.php";
session_start();

$donations_sql = "
    SELECT 
        users.name,
        users.surname,
        users.email,
        donations.amount,
        donations.donated_at,
        donations.transaction_id
    FROM donations
    INNER JOIN users ON donations.user_id = users.id
    ORDER BY donations.donated_at DESC
";

$donations_result = mysqli_query($connection, $donations_sql);
if (!$donations_result) {
    die("Error fetching donations: " . mysqli_error($connection));
}

$payments_sql = "
    SELECT 
        users.name AS user_name,
        users.surname,
        users.email,
        adoptions.amount,
        adoptions.transaction_id,
        adoptions.requested_at,
        adoptions.adoption_date,
        pets.name AS pet_name,
        pets.id AS pet_id
    FROM adoptions
    INNER JOIN users ON adoptions.user_id = users.id
    INNER JOIN pets ON adoptions.pet_id = pets.id
    WHERE adoptions.status != 'REFUNDED' AND adoptions.amount > 0
";

$payments_result = mysqli_query($connection, $payments_sql);
if (!$payments_result) {
    die("Error fetching payments: " . mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php'; ?>
    <title>FurEver Home | Payments & Donations</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: double peru;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: rgba(131, 173, 68, 0.4);
        }
        h2 {
            margin-top: 20px;
            font-family: "Righteous", sans-serif;
            color: peru;
        }
        .container {
            gap: 10px;
            position: absolute;
            top: -15px;
            flex-direction: column;
        }
        p {
            text-align: center;
            inset-inline: auto;
        }
    </style>
</head>
<body>

<?php require 'admin-navbar.php'; ?>

<div class="container">
    <h2>Donations</h2>
    <table>
        <thead>
        <tr>
            <th>User Name</th>
            <th>User Surname</th>
            <th>User Email</th>
            <th>Amount</th>
            <th>Donated At</th>
            <th>Transaction ID</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        while ($donation = mysqli_fetch_assoc($donations_result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($donation['name']); ?></td>
                <td><?php echo htmlspecialchars($donation['surname']); ?></td>
                <td><a href="mailto:<?php echo htmlspecialchars($donation['email']); ?>"><?php echo htmlspecialchars($donation['email']); ?></a></td>
                <td><?php echo htmlspecialchars($donation['amount']); $total += $donation['amount'];?></td>
                <td><?php echo htmlspecialchars($donation['donated_at']); ?></td>
                <td><?php echo htmlspecialchars($donation['transaction_id']); ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <p>Total: <span>$<?php echo $total ?></span></p>

    <h2>Adoption Payments</h2>
    <table>
        <thead>
        <tr>
            <th>User Name</th>
            <th>User Surname</th>
            <th>User Email</th>
            <th>Pet Id</th>
            <th>Pet name</th>
            <th>Amount</th>
            <th>Paid At</th>
            <th>Status</th>
            <th>Transaction ID</th>
        </tr>
        </thead>
        <tbody>
        <?php
        $total = 0;
        while ($payment = mysqli_fetch_assoc($payments_result)): ?>
            <tr>
                <td><?php echo htmlspecialchars($payment['user_name']); ?></td>
                <td><?php echo htmlspecialchars($payment['surname']); ?></td>
                <td><a href="mailto:<?php echo htmlspecialchars($payment['email']); ?>"><?php echo htmlspecialchars($payment['email']); ?></a></td>
                <td><?php echo htmlspecialchars($payment['pet_id']); ?></td>
                <td><?php echo htmlspecialchars($payment['pet_name']); ?></td>
                <td>$<?php echo htmlspecialchars($payment['amount']); $total += $payment['amount'];?></td>
                <td><?php echo htmlspecialchars($payment['requested_at']); ?></td>
                <td><?php echo htmlspecialchars($payment['adoption_date']) != "0000-00-00 00:00:00" ? "adopted" : "waiting"; ?></td>
                <td><?php echo htmlspecialchars($payment['transaction_id']); ?></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
    <p>Total: <span>$<?php echo $total ?></span></p>
</div>
<?php mysqli_close($connection) ?>
</body>
</html>