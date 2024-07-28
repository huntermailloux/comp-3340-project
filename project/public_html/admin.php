<?php
    require '../private/connectionString.php';
    $userId = $_SESSION['userId'];
    $isAdmin = $_SESSION['isAdmin'];

    if ($isAdmin == false) {
        header("Location: login.php?error=unauthenticated");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        document.write("<link rel='stylesheet' href='tables.css?v=" + Date.now() + "'><\/link>");
    </script>
    <script>
        document.write("<script type='text/javascript' src='../private/tables.js?v=" + Date.now() + "'><\/script>");
    </script>
    <script>
        document.write("<script type='text/javascript' src='../private/search.js?v=" + Date.now() + "'><\/script>");
    </script>
    <script>
        document.write("<link rel='stylesheet' href='postsTable.css?v=" + Date.now() + "'><\/link>");
    </script>
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
                            <a href="../private/logout.php">Logout</a>
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
    <div class="table-container">
        <h1>Table of All Users</h1>
        <table id="classTable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        <div class="pagination" id="userPagination"></div>
    </div>

    <div class="table-container">
        <h1>Table of All Posts</h1>
        <div class="search-container">
            <form action="../private/search.php" id="searchForm" method="GET">
                <input type="text" id="searchQuery" name="query" placeholder="Search posts...">
                <button type="submit">Search</button>
            </form>   
        </div>
        <table id="classTable2">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Timestamp</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
        
            </tbody>
        </table>
        <div class="pagination" id="postsPagination"></div>
    </div>

    <div class="table-container">
        <h1>Table of User Submitted Contacts</h1>
        <table id="classTable3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
        
            </tbody>
        </table>
        <div class="pagination" id="commsPagination"></div>
    </div>
    <footer>
        <div class="header-container">
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