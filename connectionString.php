<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

session_start();
$db_host = $_ENV['DB_HOST']; 
$db_username = $_ENV['DB_USERNAME']; 
$db_password = $_ENV['DB_PASSWORD']; 
$db_name = $_ENV['DB_NAME']; 


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>