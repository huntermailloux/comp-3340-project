<?php
session_start();
require 'connectionString.php';

$response = ['success' => false, 'message' => ''];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $userId = $_SESSION['userId'];
    $postId = $_POST['post_id'];

    // Verify that the user owns the post
    $query = "SELECT * FROM Posts WHERE id = ? AND userId = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // The user owns the post, proceed with deletion
            $deleteQuery = "DELETE FROM Posts WHERE id = ?";
            if ($deleteStmt = $conn->prepare($deleteQuery)) {
                $deleteStmt->bind_param("i", $postId);
                if ($deleteStmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Post deleted successfully.";
                } else {
                    $response['message'] = "Error deleting post.";
                }
                $deleteStmt->close();
            } else {
                $response['message'] = "Delete statement prepare failed.";
            }
        } else {
            $response['message'] = "You are not authorized to delete this post.";
        }
        $stmt->close();
    } else {
        $response['message'] = "Post selection failed.";
    }
} else {
    $response['message'] = "You must be logged in to delete posts.";
}

echo json_encode($response);
?>
