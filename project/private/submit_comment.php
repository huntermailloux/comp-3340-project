<?php
session_start();
require 'connectionString.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
        $user_id = $_SESSION['userId'];
        $post_id = $_POST['post_id'];
        $comment_content = $_POST['comment_content'];

        // Insert the comment into the database
        $comment_query = "INSERT INTO Comments (postId, userId, content) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($comment_query);
        $stmt->bind_param("iis", $post_id, $user_id, $comment_content);

        if ($stmt->execute()) {
            // Redirect back to the post page
            header("Location: /project/public_html/post-info.php?post_id=" . $post_id);
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        header('Location: /project/public_html/login.php');
        exit;
    }
}
?>
