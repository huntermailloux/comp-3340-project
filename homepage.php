<?php
session_start();
require 'connectionString.php';

// Fetch the most recent posts along with the number of comments for each post
$query = "
    SELECT p.id AS post_id, p.title, p.content, u.username, p.timestamp,
           (SELECT COUNT(*) FROM Comments c WHERE c.postId = p.id) AS comment_count
    FROM Posts p
    JOIN Users u ON p.userId = u.id
    ORDER BY p.timestamp DESC
    LIMIT 10";
$result = $conn->query($query);
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
                <a href="#">Home</a>
                <a href="#">Popular</a>
                <a href="#">Rising</a>
                <a href="#">Activity</a>
                <a href="contact.html">Contact</a>
                <div class="profile">
                    <img src="profile-icon.png" alt="Profile" class="profile-img">
                    <div class="profile-dropdown">
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                            <a href="logout.php">Logout</a>
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
            <div class="main-content">
                <section class="posts">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <article class="post">
                            <h2><a href="post-info.php?post_id=<?php echo $row['post_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h2>
                            <p class="author">Posted by <?php echo htmlspecialchars($row['username']); ?> on <?php echo htmlspecialchars($row['timestamp']); ?></p>
                            <p class="content"><?php echo htmlspecialchars($row['content']); ?></p>
                            <p class="comments"><?php echo $row['comment_count']; ?> comments</p>
                        </article>
                    <?php endwhile; ?>
                </section>
                
                <aside class="sidebar">
                    <section class="sidebar-item">
                        <h3>Activity</h3>
                        <p>Latest activities and updates from the community.</p>
                    </section>
                    <section class="sidebar-item">
                        <h3>Rising Posts</h3>
                        <ul>
                            <li><a href="#">Post Title 5</a></li>
                            <li><a href="#">Post Title 6</a></li>
                            <li><a href="#">Post Title 7</a></li>
                            <li><a href="#">Post Title 8</a></li>
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
</body>
</html>
