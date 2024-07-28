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
    <header>
        <div class="header-container">
            <div class="logo">
                <img src="logo.png" alt="Logo" class="logo-img">
                <h1>COMP3340 Project</h1>
            </div>
            <nav>
                <a href="homepage.php">Home</a>
                <a href="popular.php">Popular</a>
                <a href="rising.php">Rising</a>
                <a href="my-posts.php">My Posts</a>
                <a href="contact.html">Contact</a>
                <div class="profile">
                    <img src="profile-icon.png" alt="Profile" class="profile-img">
                    <div class="profile-dropdown">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                            <a href="logout.php">Logout</a>
                            <?php if ($isAdmin == 1): ?>
                                <a href="admin.php">Admin</a>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="login.php">Login</a>
                        <?php endif; ?>
                    </div>
                </div>
            </nav>
        </div>
    </header>
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

    <footer>
        <div class="container">
            <p>&copy; 2024 COMP3340 Project. All rights reserved.</p>
        </div>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
        const profileImg = document.querySelector('.profile-img');
        const profileDropdown = document.querySelector('.profile-dropdown');

        profileImg.addEventListener('click', () => {
            profileDropdown.style.display = profileDropdown.style.display === 'none' || profileDropdown.style.display === '' ? 'block' : 'none';
        });

        // Close the dropdown if the user clicks outside of it
        document.addEventListener('click', (event) => {
            if (!profileImg.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.style.display = 'none';
            }
        });
    });
    </script>
</body>
</html>