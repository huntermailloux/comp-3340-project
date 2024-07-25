<!-- TODO: -->
<!-- Persistent admin session -->
<!-- Section to query the Posts table -->
<!--    be able to display all comments under said Post -->
<!-- View User Communications -->

<?php
    require 'connectionString.php';
    $userId = $_SESSION['userId'];
    $isAdmin = $_SESSION['isAdmin'];

    if ($isAdmin == false) {
        header("Location: login.php?=error=unauthenticated");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Create Tables</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        document.write("<script type='text/javascript' src='databaseHandler.js?v=" + Date.now() + "'><\/script>");
    </script>
    <script>
        document.write("<link rel='stylesheet' href='table.css?v=" + Date.now() + "'><\/link>");
    </script>
    <script>
        document.write("<script type='text/javascript' src='table.js?v=" + Date.now() + "'><\/script>");
    </script>
</head>
<body>
    <h1>Create Database Tables</h1>
    <button onclick="createTables()">Create tables</button>

    <div id="table-container">
        <table id="classTable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </div>
    <div class="pagination" id="pagination"></div>
</body>
</html>