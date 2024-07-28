<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Connect to database
require '../private/connectionString.php';

$isAdmin = $_SESSION['isAdmin'] ?? 0;

// SQL query to get posts with the number of likes from most likes to least likes
$query = "
    SELECT p.id, p.title, p.content, p.timestamp, COUNT(l.id) AS likes
    FROM Posts p
    LEFT JOIN Likes l ON p.id = l.postId
    GROUP BY p.id
    ORDER BY likes DESC, p.timestamp DESC
";

// Execute the query
$result = $conn->query($query);

if ($result === FALSE) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Popular Posts</title>
    <link rel="stylesheet" href="popular.css">
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
    
    <main>
        <div class="container">
            <h1 class="popular-title">Popular Posts</h1>
            <div class="posts">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <article class="post">
                            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p class="author">Posted on <?php echo htmlspecialchars($row['timestamp']); ?></p>
                            <p class="likes">Likes: <?php echo htmlspecialchars($row['likes']); ?></p>
                            <p class="content"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No posts available.</p>
                <?php endif; ?>
            </section>
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
