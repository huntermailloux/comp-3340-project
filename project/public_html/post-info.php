<?php
session_start();
require '../private/connectionString.php';

// Check if post_id is set in the URL
if (isset($_GET['post_id'])) {
    $post_id = $_GET['post_id'];

    // Fetch the post details
    $post_query = "SELECT p.title, p.content, u.username, p.timestamp FROM Posts p JOIN Users u ON p.userId = u.id WHERE p.id = ?";
    $post_stmt = $conn->prepare($post_query);
    $post_stmt->bind_param("i", $post_id);
    $post_stmt->execute();
    $post_result = $post_stmt->get_result();
    $post = $post_result->fetch_assoc();

    // Fetch the comments for this post
    $comments_query = "SELECT c.id AS comment_id, c.content, u.username, c.timestamp, c.userId FROM Comments c JOIN Users u ON c.userId = u.id WHERE c.postId = ? ORDER BY c.timestamp ASC";
    $comments_stmt = $conn->prepare($comments_query);
    $comments_stmt->bind_param("i", $post_id);
    $comments_stmt->execute();
    $comments_result = $comments_stmt->get_result();
} else {
    // Redirect to homepage if no post_id is provided
    header('Location: homepage.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" href="style_homepage.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.delete-comment-btn').click(function() {
                var commentId = $(this).data('comment-id');
                if (confirm('Are you sure you want to delete this comment?')) {
                    $.post('../private/delete_comment.php', { comment_id: commentId }, function(response) {
                        if (response.success) {
                            $('#comment-' + commentId).remove();
                        } else {
                            alert(response.message);
                        }
                    }, 'json');
                }
            });

            $('.edit-btn').click(function() {
                var commentId = $(this).data('comment-id');
                $('#edit-form-' + commentId).toggle(); // Toggle visibility of the edit form
            });

            $('.save-edit-btn').click(function() {
                var commentId = $(this).data('comment-id');
                var content = $('#edit-comment-content-' + commentId).val();

                $.post('../private/edit_comment.php', { comment_id: commentId, content: content }, function(response) {
                    if (response.success) {
                        $('#comment-content-' + commentId).text(content); // Update the content in the DOM
                        $('#edit-form-' + commentId).hide(); // Hide the edit form after saving
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
                            <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == 1): ?>
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
                    <article class="post">
                        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
                        <p class="author">Posted by <?php echo htmlspecialchars($post['username']); ?> on <?php echo htmlspecialchars($post['timestamp']); ?></p>
                        <div class="content">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>
                    </article>
                </section>
                
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                    <div class="comment-form">
                        <h3>Leave a Comment</h3>
                        <form action="../private/submit_comment.php" method="post">
                            <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                            <div class="form-group">
                                <textarea name="comment_content" rows="5" required placeholder="Write your comment here..."></textarea>
                            </div>
                            <button type="submit" class="submit-btn">Submit Comment</button>
                        </form>
                    </div>
                <?php else: ?>
                    <p><a href="login.php">Login</a> to leave a comment.</p>
                <?php endif; ?>
            </div>

            <div class="comments-section">
                <h3>Comments</h3>
                <?php while ($comment = $comments_result->fetch_assoc()): ?>
                    <div class="comment" id="comment-<?php echo $comment['comment_id']; ?>">
                        <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong> at <?php echo htmlspecialchars($comment['timestamp']); ?></p>
                        <p id="comment-content-<?php echo $comment['comment_id']; ?>"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                        <?php if (isset($_SESSION['userId']) && $_SESSION['userId'] == $comment['userId']): ?>
                            <button class="delete-comment-btn" data-comment-id="<?php echo $comment['comment_id']; ?>">Delete</button>
                            <button class="edit-btn" data-comment-id="<?php echo $comment['comment_id']; ?>">Edit</button>
                            <div class="edit-form" id="edit-form-<?php echo $comment['comment_id']; ?>" style="display: none;">
                                <textarea id="edit-comment-content-<?php echo $comment['comment_id']; ?>" rows="3"><?php echo htmlspecialchars($comment['content']); ?></textarea>
                                <button class="save-edit-btn" data-comment-id="<?php echo $comment['comment_id']; ?>">Save</button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

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
    <footer>
        <div class="container">
            <p>&copy; 2024 COMP3340 Project. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
