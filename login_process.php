<?php
require 'connectionString.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = "SELECT * FROM Users WHERE username = ? AND password = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['first_name'] = $row['first_name'];
    $_SESSION['last_name'] = $row['last_name'];
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $username;
    $_SESSION['userId'] = $row['id'];
    $_SESSION['isAdmin'] = $row['is_admin'];
    $username = $_SESSION['username'];
    header("Location: admin.php");
    exit;
    
} else {
    header('Location: login.php?error=1');
    exit;
}
?>