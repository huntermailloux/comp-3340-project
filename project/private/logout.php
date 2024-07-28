<?php
require 'connectionString.php';

$_SESSION = [];
session_unset();
session_destroy();
header("Location: /project/public_html/login.php");
?>