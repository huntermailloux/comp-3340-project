<?php
require 'ConnectionString.php';

// Fetch posts from the last 24 hours sorted by most likes to least likes
$sql = "SELECT Posts.id, Posts.title, Posts.content, Users.username, COUNT(Likes.id) as like_count
        FROM Posts
        JOIN Users ON Posts.userId = Users.id
        LEFT JOIN Likes ON Posts.id = Likes.postId
        WHERE Posts.timestamp >= NOW() - INTERVAL 1 DAY
        GROUP BY Posts.id
        ORDER BY like_count DESC, Posts.timestamp DESC";

$result = $conn->query($sql);

$posts = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rising Posts</title>
    <link rel="stylesheet" href="rising.css">
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
        <h1 class="rising-title">Rising Posts</h1>
        <div class="posts">
            <?php foreach ($posts as $post): ?>
                <article class="post">
                    <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                    <p class="author">Posted by <?php echo htmlspecialchars($post['username']); ?></p>
                    <p class="content"><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    <p class="likes"><?php echo $post['like_count']; ?> likes</p>
                </article>
            <?php endforeach; ?>
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




    