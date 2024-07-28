<?php
session_start();
require '../private/connectionString.php';

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
    echo "<p>Please <a href='login.php'>login</a> to see your posts.</p>";
    exit;
}

$userId = $_SESSION['userId'] ?? 0; // Get the logged-in user's ID

$query = "
    SELECT p.id AS post_id, p.title, p.content, u.username, p.timestamp,
           (SELECT COUNT(*) FROM Likes l WHERE l.postId = p.id) AS like_count,
           (SELECT COUNT(*) FROM Likes l WHERE l.postId = p.id AND l.userId = ?) AS user_liked,
           (SELECT COUNT(*) FROM Comments c WHERE c.postId = p.id) AS comment_count
    FROM Posts p
    JOIN Users u ON p.userId = u.id
    WHERE p.userId = ?
    ORDER BY p.timestamp DESC";

if ($stmt = $conn->prepare($query)) {
    $stmt->bind_param("ii", $userId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "Prepare failed: (" . $conn->errno . ") " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts - COMP3340 Project</title>
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

            $('.delete-btn').click(function() {
                var postId = $(this).data('post-id');
                if (confirm('Are you sure you want to delete this post?')) {
                    $.post('../private/delete_post.php', { post_id: postId }, function(response) {
                        if (response.success) {
                            $('#post-' + postId).remove();
                        } else {
                            alert(response.message);
                        }
                    }, 'json');
                }
            });

            $('.edit-btn').click(function() {
                var postId = $(this).data('post-id');
                $('#edit-form-' + postId).toggle(); // Toggle visibility of the edit form
            });

            $('.save-edit-btn').click(function() {
                var postId = $(this).data('post-id');
                var title = $('#edit-title-' + postId).val();
                var content = $('#edit-content-' + postId).val();

                $.post('../private/edit_post.php', { post_id: postId, title: title, content: content }, function(response) {
                    if (response.success) {
                        $('#post-title-' + postId).text(title); // Update the title in the DOM
                        $('#post-content-' + postId).text(content); // Update the content in the DOM
                        $('#edit-form-' + postId).hide(); // Hide the edit form after saving
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
                            <?php if ($_SESSION['isAdmin']): ?>
                                <a href="admin.php">Admin Panel</a>
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
                    <h2>My Posts</h2>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <article class="post" id="post-<?php echo $row['post_id']; ?>">
                            <h2><a href="post-info.php?post_id=<?php echo $row['post_id']; ?>"><?php echo htmlspecialchars($row['title']); ?></a></h2>
                            <p class="author">Posted by <?php echo htmlspecialchars($row['username']); ?> on <?php echo htmlspecialchars($row['timestamp']); ?></p>
                            <p class="content" id="post-content-<?php echo $row['post_id']; ?>"><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                            <p class="comments">Comments: <?php echo $row['comment_count']; ?></p>
                            <p class="likes">Likes: <span id="like-count-<?php echo $row['post_id']; ?>"><?php echo $row['like_count']; ?></span></p>
                            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                                <button id="like-btn-<?php echo $row['post_id']; ?>" class="like-btn <?php echo ($row['user_liked'] > 0) ? 'liked' : ''; ?>" data-post-id="<?php echo $row['post_id']; ?>">
                                    <?php echo ($row['user_liked'] > 0) ? 'Unlike' : 'Like'; ?>
                                </button>
                                <button class="delete-btn" data-post-id="<?php echo $row['post_id']; ?>">Delete</button>
                                <button class="edit-btn" data-post-id="<?php echo $row['post_id']; ?>">Edit</button>
                                <div class="edit-form" id="edit-form-<?php echo $row['post_id']; ?>" style="display: none;">
                                    <input type="text" id="edit-title-<?php echo $row['post_id']; ?>" value="<?php echo htmlspecialchars($row['title']); ?>">
                                    <textarea id="edit-content-<?php echo $row['post_id']; ?>" rows="4"><?php echo htmlspecialchars($row['content']); ?></textarea>
                                    <button class="save-edit-btn" data-post-id="<?php echo $row['post_id']; ?>">Save</button>
                                </div>
                            <?php endif; ?>
                        </article>
                    <?php endwhile; ?>
                </section>
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
