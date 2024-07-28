<?php
// Include database connection file
require 'ConnectionString.php';

// Fetch posts from the database
$sql = "SELECT Posts.*, Users.username, 
        (SELECT COUNT(*) FROM Likes WHERE Likes.postId = Posts.id) AS like_count, 
        (SELECT COUNT(*) FROM Comments WHERE Comments.postId = Posts.id) AS comment_count 
        FROM Posts 
        JOIN Users ON Posts.userId = Users.id 
        ORDER BY Posts.timestamp DESC";
$result = $conn->query($sql);

// Error handling for SQL query
if (!$result) {
    die('Query failed: ' . $conn->error);
}

// Fetch rising posts from the database
$rising_sql = "SELECT Posts.*, Users.username, 
              (SELECT COUNT(*) FROM Likes WHERE Likes.postId = Posts.id) AS like_count 
              FROM Posts 
              JOIN Users ON Posts.userId = Users.id 
              ORDER BY like_count DESC, Posts.timestamp DESC 
              LIMIT 5";
$rising_result = $conn->query($rising_sql);

// Error handling for SQL query
if (!$rising_result) {
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
    <style>
        .post-content {
            display: none;
        }
        .post-content.visible {
            display: block;
        }
    </style>
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
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <article class="post">
                            <h2 onclick="togglePostContent(<?php echo $row['id']; ?>)"><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p class="author">Posted by <?php echo htmlspecialchars($row['username']); ?> on <?php echo htmlspecialchars($row['timestamp']); ?></p>
                            <div id="post-content-<?php echo $row['id']; ?>" class="post-content">
                                <p class="content"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                                <p class="likes">Likes: <?php echo htmlspecialchars($row['like_count']); ?></p>
                                <p class="comments">Comments: <?php echo htmlspecialchars($row['comment_count']); ?></p>
                            </div>
                        </article>
                    <?php endwhile; ?>
                </section>
                
                <aside class="sidebar">
                    <section class="sidebar-item">
                        <h3>Rising Posts</h3>
                        <ul>
                            <?php while ($rising_row = $rising_result->fetch_assoc()): ?>
                                <li>
                                    <a href="#" onclick="togglePostContent(<?php echo $rising_row['id']; ?>)"><?php echo htmlspecialchars($rising_row['title']); ?></a>
                                </li>
                            <?php endwhile; ?>
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
    function togglePostContent(postId) {
        var postContent = document.getElementById('post-content-' + postId);
        if (postContent) {
            postContent.classList.toggle('visible');
        }
    }

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
