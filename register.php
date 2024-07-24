<?php 
    require 'connectionString.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEHA2024</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="create-account-container" id="create-account-form">
        <form class="create-account-form" action="create_account.php" method="post">
            <h2>Create Account</h2>
            <?php if (isset($_GET['error']) && $_GET['error'] == 'username_already_exists'): ?>
                <div class="error">The username already exists. Please choose a different username.</div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] == 'password_mismatch'): ?>
                <div class="error">There was a password mismatch. Please try again.</div>
            <?php endif; ?>
            <?php if (isset($_GET['error']) && $_GET['error'] == 'missing_data'): ?>
                <div class="error">There was some missing data. Please make sure to fill out all sections.</div>
            <?php endif; ?>
            <div class="form-group">
                <label for="FirstName">First Name</label>
                <input type="text" name="FirstName" id="FirstName" required>
            </div>
            <div class="form-group">
                <label for="LastName">Last Name</label>
                <input type="text" name="LastName" id="LastName" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password"required>
            </div>
            <button type="submit" class="submit-btn">Create Account</button>
        </form>
    </div>
</body>
</html>