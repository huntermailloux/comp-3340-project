<?php
session_start();
require '../private/connectionString.php';

$userId = $_SESSION['userId'] ?? 0; // Default to 0 if userId is not set
$isAdmin = $_SESSION['isAdmin'] ?? 0;

// Debugging line to check user ID
if ($userId == 0) {
    echo "Warning: User not logged in or session not initialized.";
}

// Fetch recent posts
$query = "
    SELECT p.id AS post_id, p.title, p.content, u.username, p.timestamp,
           (SELECT COUNT(*) FROM Likes l WHERE l.postId = p.id) AS like_count,
           (SELECT COUNT(*) FROM Likes l WHERE l.postId = p.id AND l.userId = ?) AS user_liked,
           (SELECT COUNT(*) FROM Comments c WHERE c.postId = p.id) AS comment_count
    FROM Posts p
    JOIN Users u ON p.userId = u.id
    ORDER BY p.timestamp DESC";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
}

// Fetch top 5 most liked posts
$popularQuery = "
    SELECT p.id AS post_id, p.title, p.content, u.username, p.timestamp,
           (SELECT COUNT(*) FROM Likes l WHERE l.postId = p.id) AS like_count
    FROM Posts p
    JOIN Users u ON p.userId = u.id
    ORDER BY like_count DESC
    LIMIT 5";

$popularPosts = $conn->query($popularQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COMP3340 Project</title>
    <link rel="stylesheet" href="style_homepage.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.like-btn').click(function() {
                var postId = $(this).data('post-id');
                var action = $(this).hasClass('liked') ? 'unlike' : 'like';

                $.post('../private/like_post.php', { post_id: postId, action: action }, function(response) {
                    if (response.success) {
                        var likeCount = $('#like-count-' + postId);
                        likeCount.text(response.like_count);
                        
                        var likeButton = $('#like-btn-' + postId);
                        if (action === 'like') {
                            likeButton.addClass('liked').text('Unlike');
                        } else {
                            likeButton.removeClass('liked').text('Like');
                        }
                    } else {
                        alert(response.message);
                    }
                }, 'json');
            });
        });
    </script>
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
            <div class="main-content">
                <section class="posts">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <article class="post">
                            <h2><a href="post-info.php?post_id=<?php echo $row['post_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h2>
                            <p class="author">Posted by <?php echo htmlspecialchars($row['username']); ?> on <?php echo htmlspecialchars($row['timestamp']); ?></p>
                            <p class="content"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                            <p class="comments">Comments: <?php echo $row['comment_count']; ?></p>
                            <p class="likes">Likes:&nbsp; <span id="like-count-<?php echo $row['post_id']; ?>"><?php echo $row['like_count']; ?></span></p>
                            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                                <button id="like-btn-<?php echo $row['post_id']; ?>" class="like-btn <?php echo ($row['user_liked'] > 0) ? 'liked' : ''; ?>" data-post-id="<?php echo $row['post_id']; ?>">
                                    <?php echo ($row['user_liked'] > 0) ? 'Unlike' : 'Like'; ?>
                                </button>
                            <?php endif; ?>
                        </article>
                    <?php endwhile; ?>
                </section>
                
                <aside class="sidebar">
                    <section class="sidebar-item">
                        <h3>Popular Posts</h3>
                        <ul>
                            <?php
                            $rank = 1;
                            while ($popular = $popularPosts->fetch_assoc()): ?>
                                <li><a href="post-info.php?post_id=<?php echo $popular['post_id']; ?>"><?php echo "#" . $rank . ": " . htmlspecialchars($popular['title']); ?></a></li>
                                <?php $rank++; ?>
                            <?php endwhile; ?>
                        </ul>
                    </section>
                    <section class="sidebar-item">
                        <a href="createPost.php">
                            <button class="create-button">Create Post</button>
                        </a>
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
