<?php 
    require 'connectionString.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEHA2024</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <div class="login-container">
            <form class="login-form" action="login_process.php" method="post">
                <h2>Login</h2>
                <?php if (isset($_GET['error'])): ?>
                    <div class="error">Invalid username or password.</div>
                <?php endif; ?>
                <?php if (isset($_GET['success']) && $_GET['success'] == 'account_created'): ?>
                    <div class="success">Account created successfully!</div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="submit-btn">Login</button>
            </form>
            <a href="register.php">
                <div class="register">
                        <button class="register-btn">New? Click here to sign up!</button>
                </div>
            </a>
        </div>
    </div>
</body>
</html>