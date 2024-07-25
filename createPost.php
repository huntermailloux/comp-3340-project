<?php
session_start();
require 'connectionString.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="createPost.css">
</head>
<body>
    <div class="container">
        <div class="post-form-container">
            <h2>Create a New Post</h2>
            <form action="create_new_post.php" method="post">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" required>
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea name="content" id="content" rows="5" required></textarea>
                </div>
                <button type="submit" class="submit-btn">Post</button>
            </form>
        </div>
    </div>
</body>
</html>