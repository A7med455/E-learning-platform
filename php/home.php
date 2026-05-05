<?php
$required_role = 'student';
include 'php/session_guard.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/sign.css">
</head>
<body>

    <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>

    <nav>
        <a href="courses.html">Browse Courses</a> |
        <a href="my-courses.php">My Courses</a>   |
        <a href="wallet.php">Wallet</a>            |
        <a href="profile.php">Profile</a>          |
        <a href="php/logout.php">Logout</a>
    </nav>

    <hr>

    <h3>Available Courses</h3>
    <div id="courses-container">
        Loading courses...
    </div>

    <script src="js/courses.js"></script>
</body>
</html>