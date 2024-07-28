<?php
session_start();
require 'connectionString.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['userId'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    $stmt = $conn->prepare("INSERT INTO Posts (userId, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $title, $content);

    if ($stmt->execute()) {
        echo "Post created successfully!";
        // Redirect to a different page if needed
        header('Location: /project/public_html/createPost.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>