<?php
session_start();
require 'connectionString.php';

$response = ['success' => false, 'message' => ''];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    $userId = $_SESSION['userId'];
    $commentId = $_POST['comment_id'];

    // Verify that the user owns the comment
    $query = "SELECT * FROM Comments WHERE id = ? AND userId = ?";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("ii", $commentId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // The user owns the comment, proceed with deletion
            $deleteQuery = "DELETE FROM Comments WHERE id = ?";
            if ($deleteStmt = $conn->prepare($deleteQuery)) {
                $deleteStmt->bind_param("i", $commentId);
                if ($deleteStmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "Comment deleted successfully.";
                } else {
                    $response['message'] = "Error deleting comment.";
                }
                $deleteStmt->close();
            } else {
                $response['message'] = "Delete statement prepare failed.";
            }
        } else {
            $response['message'] = "You are not authorized to delete this comment.";
        }
        $stmt->close();
    } else {
        $response['message'] = "Comment selection failed.";
    }
} else {
    $response['message'] = "You must be logged in to delete comments.";
}

echo json_encode($response);
?>
