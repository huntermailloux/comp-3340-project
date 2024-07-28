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