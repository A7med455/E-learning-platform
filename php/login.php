<?php
session_start();
include 'db.php';

$email    = $_POST['email'];
$password = $_POST['password'];

$sql    = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($conn, $sql);
$user   = mysqli_fetch_assoc($result);

if (!$user) {
    header('Location: ../login.html?error=wrong');
    exit;
}

$_SESSION['user_id'] = $user['id'];
$_SESSION['name']    = $user['fname'] . ' ' . $user['lname'];
$_SESSION['role']    = $user['role'];

if ($user['role'] == 'student')    header('Location: ../home.html');
if ($user['role'] == 'instructor') header('Location: ../Instructor_Dashboard.html');
if ($user['role'] == 'admin')      header('Location: ../Admin_Dashboard.html');
exit;
?>