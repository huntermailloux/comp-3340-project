<?php
session_start();
require 'connectionString.php';

$response = ['success' => false, 'message' => '', 'title' => '', 'content' => ''];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $userId = $_SESSION['userId'];
    $postId = $_POST['post_id'];
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Verify that the user owns the post
    $query = "SELECT * FROM Posts WHERE id = ? AND userId = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $postId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // The user owns the post, proceed with update
            $updateQuery = "UPDATE Posts SET title = ?, content = ? WHERE id = ?";
            if ($updateStmt = $conn->prepare($updateQuery)) {
                $updateStmt->bind_param("ssi", $title, $content, $postId);
                if ($updateStmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Post updated successfully.";
                    $response['title'] = $title; // Include the updated title in the response
                    $response['content'] = $content; // Include the updated content in the response
                } else {
                    $response['message'] = "Error updating post.";
                }
                $updateStmt->close();
            } else {
                $response['message'] = "Update statement prepare failed.";
            }
        } else {
            $response['message'] = "You are not authorized to edit this post.";
        }
        $stmt->close();
    } else {
        $response['message'] = "Post selection failed.";
    }
} else {
    $response['message'] = "You must be logged in to edit posts.";
}

echo json_encode($response);
?>
