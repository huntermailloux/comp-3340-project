<?php
require 'connectionString.php';

header('Content-Type: application/json');

try {
    $stmt = $conn->prepare("SELECT id, name, email, message, timestamp FROM UserCommunications");
    $stmt->execute();
    $result = $stmt->get_result();
    $comms = [];

    while ($row = $result->fetch_assoc()) {
        $comms[] = $row;
    }

    echo json_encode($comms);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

$conn->close();
?>
