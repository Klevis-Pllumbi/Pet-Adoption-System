<!DOCTYPE html>
<html lang="en">
<head>
    <?php require 'links.php' ?>
    <title>FurEver Home | Donate</title>
</head>
<body>

<?php require 'navbar.php' ?>

<form action="donate.php" method="post" id="donate-form">
    <h3><span>Donate</span></h3>
    <p>We greatly appreciate your decision to help us in our mission.<br>
        Protecting our furry friends is a noble duty, a duty that you are fulfilling with your help.<br>
        Best wishes!
    </p>
    <div class="form-group">
        <input type="number" name="amount" id="amount" placeholder=" " required>
        <label for="amount">Amount</label>
    </div>
    <button type="submit" name="submit" id="submit">Donate</button>
</form>

</body>
</html>