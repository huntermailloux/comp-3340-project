<?php
require 'connectionString.php';

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT id, userId, title, content, timestamp FROM Posts");
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = [];

    while ($row = $result->fetch_assoc()) {
        $posts[] = $row;
    }

    echo json_encode($posts);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>
