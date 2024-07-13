<?php
require 'ConnectionString.php';

// Function to create tables
function createTables($conn) {
    $messages = [];

    // SQL for creating Users table
    $createUsersTable = "
        CREATE TABLE IF NOT EXISTS Users (
            id INT PRIMARY KEY AUTO_INCREMENT,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(100) NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            is_admin BOOLEAN DEFAULT FALSE
        )
    ";

    // SQL for creating Posts table
    $createPostsTable = "
        CREATE TABLE IF NOT EXISTS Posts (
            id INT PRIMARY KEY AUTO_INCREMENT,
            userId INT NOT NULL,
            content TEXT NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (userId) REFERENCES Users(id) ON DELETE CASCADE
        )
    ";

    // SQL for creating Comments table
    $createCommentsTable = "
        CREATE TABLE IF NOT EXISTS Comments (
            id INT PRIMARY KEY AUTO_INCREMENT,
            postId INT NOT NULL,
            userId INT NOT NULL,
            content TEXT NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (postId) REFERENCES Posts(id) ON DELETE CASCADE,
            FOREIGN KEY (userId) REFERENCES Users(id) ON DELETE CASCADE
        )
    ";

    // SQL for creating UserCommunications table
    $createUserCommunicationsTable = "
        CREATE TABLE IF NOT EXISTS UserCommunications (
            id INT PRIMARY KEY AUTO_INCREMENT,
            name VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL,
            message TEXT NOT NULL,
            timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ";

    // Execute each table creation query and collect messages
    if ($conn->query($createUsersTable) === TRUE) {
        $messages[] = "Users table created successfully.";
    } 
    else {
        $messages[] = "Error creating Users table: " . $conn->error;
    }

    if ($conn->query($createPostsTable) === TRUE) {
        $messages[] = "Posts table created successfully.";
    } 
    else {
        $messages[] = "Error creating Posts table: " . $conn->error;
    }

    if ($conn->query($createCommentsTable) === TRUE) {
        $messages[] = "Comments table created successfully.";
    } 
    else {
        $messages[] = "Error creating Comments table: " . $conn->error;
    }

    if ($conn->query($createUserCommunicationsTable) === TRUE) {
        $messages[] = "UserCommunications table created successfully.";
    } 
    else {
        $messages[] = "Error creating UserCommunications table: " . $conn->error;
    }

    return implode("<br>", $messages);
}

// Check if AJAX request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['createTables'])) {
    $conn = getConnection();

    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error;
    } else {
        $messages = createTables($conn);
        echo $messages;
        $conn->close();
    }
}
?>