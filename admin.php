<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Tables</title>
    <script src="databaseHandler.js"></script>
</head>
<body>
    <h1>Create Database Tables</h1>
    <button onclick="createTables()">Create tables</button>
    <?php
    if (isset($_GET['message'])) {
        echo '<p>' . htmlspecialchars($_GET['message']) . '</p>';
    }
    ?>
</body>
</html>
