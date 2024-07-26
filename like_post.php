<?php
session_start();
require 'connectionString.php';

$response = ['success' => false, 'message' => '', 'like_count' => 0];

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    if (isset($_POST['post_id']) && isset($_POST['action'])) {
        $post_id = $_POST['post_id'];
        $user_id = $_SESSION['userId'];
        $action = $_POST['action'];

        if ($action === 'like') {
            // Like the post
            $query = "INSERT INTO Likes (postId, userId) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                $response['message'] = 'Prepare failed: ' . $conn->error;
            } else {
                $stmt->bind_param("ii", $post_id, $user_id);
            }
        } else if ($action === 'unlike') {
            // Unlike the post
            $query = "DELETE FROM Likes WHERE postId = ? AND userId = ?";
            $stmt = $conn->prepare($query);
            if (!$stmt) {
                $response['message'] = 'Prepare failed: ' . $conn->error;
            } else {
                $stmt->bind_param("ii", $post_id, $user_id);
            }
        } else {
            $response['message'] = 'Invalid action.';
            echo json_encode($response);
            exit;
        }

        if ($stmt->execute()) {
            // Get the updated like count
            $count_query = "SELECT COUNT(*) AS like_count FROM Likes WHERE postId = ?";
            $count_stmt = $conn->prepare($count_query);
            if ($count_stmt) {
                $count_stmt->bind_param("i", $post_id);
                $count_stmt->execute();
                $count_result = $count_stmt->get_result();
                $count_row = $count_result->fetch_assoc();

                $response['success'] = true;
                $response['like_count'] = $count_row['like_count'];
                $response['message'] = ($action === 'like') ? 'Post liked successfully.' : 'Post unliked successfully.';
            } else {
                $response['message'] = 'Count query failed: ' . $conn->error;
            }
        } else {
            $response['message'] = 'Execute failed: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Invalid post ID or action.';
    }
} else {
    $response['message'] = 'You must be logged in to like or unlike posts.';
}

echo json_encode($response);
?>
