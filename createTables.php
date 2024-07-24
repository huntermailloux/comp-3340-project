<?php
include 'connectionString.php';

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read SQL script file
$sqlScript = file_get_contents('db-setup.sql');

// Execute SQL script
if ($conn->multi_query($sqlScript) === TRUE) {
    echo "Tables created successfully";
} else {
    echo "Error creating tables: " . $conn->error;
}

$conn->close();
?>
