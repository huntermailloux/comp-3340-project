<?php
session_start();
require 'ConnectionString.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if the user is not logged in
    header("Location: login.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Debugging: check if session variable is set
echo "User ID: " . $user_id . "<br>";

// Fetch posts made by the logged-in user
$sql = "SELECT Posts.*, Users.username, 
        (SELECT COUNT(*) FROM Likes WHERE Likes.postId = Posts.id) AS like_count 
        FROM Posts 
        JOIN Users ON Posts.userId = Users.id 
        WHERE Posts.userId = ?
        ORDER BY timestamp DESC";
$stmt = $conn->prepare($sql);

// Debugging: check if SQL statement preparation is successful
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Debugging: check if SQL execution and result retrieval are successful
if (!$result) {
    die("Execute failed: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <link rel="stylesheet" href="my_posts.css">
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
            <h2>My Posts</h2>
            <section class="posts">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<article class='post'>";
                        echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
                        echo "<p class='author'>Posted by " . htmlspecialchars($row['username']) . " | " . htmlspecialchars($row['timestamp']) . "</p>";
                        echo "<p class='content'>" . htmlspecialchars($row['content']) . "</p>";
                        echo "<p class='likes'>Likes: " . htmlspecialchars($row['like_count']) . "</p>";
                        echo "</article>";
                    }
                } else {
                    echo "<p>You have not made any posts yet.</p>";
                }
                ?>
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
