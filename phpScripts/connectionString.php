<?php

session_start();
$db_host = 'localhost'; 
$db_username = 'maillo51_comp3340'; 
$db_password = 'seha2024'; 
$db_name = 'maillo51_comp3340'; 


$conn = new mysqli($db_host, $db_username, $db_password, $db_name);


if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}
?>