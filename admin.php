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
    <title>Admin Dashboard</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script>
        document.write("<link rel='stylesheet' href='tables.css?v=" + Date.now() + "'><\/link>");
    </script>
    <script>
        document.write("<script type='text/javascript' src='tables.js?v=" + Date.now() + "'><\/script>");
    </script>
    <script>
        document.write("<script type='text/javascript' src='search.js?v=" + Date.now() + "'><\/script>");
    </script>
    <script>
        document.write("<link rel='stylesheet' href='postsTable.css?v=" + Date.now() + "'><\/link>");
    </script>
</head>
<body>
    <div class="table-container">
        <h1>Table of All Users</h1>
        <table id="classTable">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
        <div class="pagination" id="userPagination"></div>
    </div>

    <div class="table-container">
        <h1>Table of All Posts</h1>
        <div class="search-container">
            <form action="search.php" id="searchForm" method="GET">
                <input type="text" id="searchQuery" name="query" placeholder="Search posts...">
                <button type="submit">Search</button>
            </form>   
        </div>
        <table id="classTable2">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Timestamp</th>
                    <th>Options</th>
                </tr>
            </thead>
            <tbody>
        
            </tbody>
        </table>
        <div class="pagination" id="postsPagination"></div>
    </div>

    <div class="table-container">
        <h1>Table of User Submitted Contacts</h1>
        <table id="classTable3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
        
            </tbody>
        </table>
        <div class="pagination" id="commsPagination"></div>
    </div>
</body>
</html>