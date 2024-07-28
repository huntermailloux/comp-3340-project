<?php
session_start();
require 'connectionString.php';

$response = ['success' => false, 'message' => ''];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $userId = $_SESSION['userId'];
    $commentId = $_POST['comment_id'];
    $content = $_POST['content'];

    // Verify that the user owns the comment
    $query = "SELECT * FROM Comments WHERE id = ? AND userId = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $commentId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // The user owns the comment, proceed with update
            $updateQuery = "UPDATE Comments SET content = ? WHERE id = ?";
            if ($updateStmt = $conn->prepare($updateQuery)) {
                $updateStmt->bind_param("si", $content, $commentId);
                if ($updateStmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Comment updated successfully.";
                } else {
                    $response['message'] = "Error updating comment.";
                }
                $updateStmt->close();
            } else {
                $response['message'] = "Update statement prepare failed.";
            }
        } else {
            $response['message'] = "You are not authorized to edit this comment.";
        }
        $stmt->close();
    } else {
        $response['message'] = "Comment selection failed.";
    }
} else {
    $response['message'] = "You must be logged in to edit comments.";
}

echo json_encode($response);
?>
