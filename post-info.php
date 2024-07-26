<?php
session_start();
require 'connectionString.php';

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
    $comments_query = "SELECT c.content, u.username, c.timestamp FROM Comments c JOIN Users u ON c.userId = u.id WHERE c.postId = ? ORDER BY c.timestamp ASC";
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
                        <form action="submit_comment.php" method="post">
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
                    <div class="comment">
                        <p><strong><?php echo htmlspecialchars($comment['username']); ?></strong> at <?php echo htmlspecialchars($comment['timestamp']); ?></p>
                        <p><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                    </div>
                <?php endwhile; ?>
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
