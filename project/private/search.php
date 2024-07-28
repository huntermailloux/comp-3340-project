<?php
header('Content-Type: application/json');

require 'connectionString.php';

if (isset($_GET['query'])) {
    $query = htmlspecialchars($_GET['query']);

    $sql = "
        SELECT
            Users.id AS userId,
            Users.username,
            Posts.id,
            Posts.title,
            Posts.content,
            Posts.timestamp
        FROM
            Posts
        INNER JOIN
            Users ON Posts.userId = Users.id
        WHERE
            Users.username LIKE ?
    ";

    if ($stmt = $conn->prepare($sql)) {
        $likeQuery = "%$query%";
        $stmt->bind_param('s', $likeQuery);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result === false) {
            echo json_encode(['error' => 'Query execution failed.']);
            exit;
        }

        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        echo json_encode($data);
        $stmt->close();

    } else {
        echo json_encode(['error' => 'Failed to prepare the SQL statement']);
    }
} else {
    echo json_encode(['error' => 'No query parameter provided']);
}

$conn->close();
?>