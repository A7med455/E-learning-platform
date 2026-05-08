<?php
$required_role = 'admin';
include '../php/session_guard.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - All Courses</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/sign.css">
</head>
<body>

    <h2>All Courses</h2>

    <nav>
        <a href="../Admin_Dashboard.html">Dashboard</a> |
        <a href="../users.html">Users</a> |
        <a href="../adminpending.html">Pending</a> |
        <a href="../php/logout.php">Logout</a>
    </nav>

    <hr>

    <div id="courses-container">
        Loading...
    </div>

    <script src="../js/admin.js"></script>
</body>
</html>