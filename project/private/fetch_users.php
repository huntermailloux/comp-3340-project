<?php
require 'connectionString.php';

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT id, username, first_name, last_name, is_admin FROM Users");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
