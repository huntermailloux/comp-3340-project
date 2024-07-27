<?php
// Include database connection file
require 'ConnectionString.php';

// Fetch posts from the database
$sql = "SELECT Posts.*, Users.username, 
        (SELECT COUNT(*) FROM Likes WHERE Likes.postId = Posts.id) AS like_count 
        FROM Posts 
        JOIN Users ON Posts.userId = Users.id 
        ORDER BY Posts.timestamp DESC";
$result = $conn->query($sql);

// Error handling for SQL query
if (!$result) {
    die('Query failed: ' . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMP3340 Project</title>
    <link rel="stylesheet" href="style_homepage.css">
    
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <img src="logo.png" alt="Logo" class="logo-img">
                <h1>COMP3340 Project</h1>
            </div>
            <nav>
                <a href="homepage.php">Home</a>
                <a href="popular.php">Popular</a>
                <a href="rising.php">Rising</a>
                <a href="my_posts.php">My Posts</a>
                <a href="contact.html">Contact</a>
                <div class="profile">
                    <img src="profile-icon.png" alt="Profile" class="profile-img">
                    <div class="profile-dropdown">
                        <a href="login.html">Login</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    
    <main>
        <div class="container">
            <div class="main-content">
                <section class="posts">
                    <article class="post">
                        <h2>Post Title 1</h2>
                        <p class="author">Posted by User1</p>
                        <p class="content">This is the content of the first post. It contains interesting information about a topic.</p>
                    </article>
                    <article class="post">
                        <h2>Post Title 2</h2>
                        <p class="author">Posted by User2</p>
                        <p class="content">This is the content of the second post. It contains details about another interesting topic.</p>
                    </article>
                    <article class="post">
                        <h2>Post Title 3</h2>
                        <p class="author">Posted by User3</p>
                        <p class="content">This is the content of the third post. It offers insights on a different subject matter.</p>
                    </article>
                    <article class="post">
                        <h2>Post Title 4</h2>
                        <p class="author">Posted by User4</p>
                        <p class="content">This is the content of the fourth post. Here we discuss another fascinating topic.</p>
                    </article>
                    <!-- More posts can be added here -->
                </section>
                
                <aside class="sidebar">
                    <section class="sidebar-item">
                        <h3>Activity</h3>
                        <p>Latest activities and updates from the community.</p>
                    </section>
                    <section class="sidebar-item">
                        <h3>Rising Posts</h3>
                        <ul>
                            <li><a href="rising.php">Post Title 5</a></li>
                            <li><a href="rising.php">Post Title 6</a></li>
                            <li><a href="rising.php">Post Title 7</a></li>
                            <li><a href="rising.php">Post Title 8</a></li>
                            <!-- More rising posts can be added here -->
                        </ul>
                    </section>
                </aside>
            </div>
        </div>
    </main>

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
