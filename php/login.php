<?php
session_start();
include 'db.php';

$email    = $_POST['email'];
$password = $_POST['password'];

// search for user with this email AND password
$sql    = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($conn, $sql);
$user   = mysqli_fetch_assoc($result);

// if no user found — go back to login with error
if (!$user) {
    header('Location: ../login.html?error=wrong');
    exit;
}

// save in session
$_SESSION['user_id'] = $user['id'];
$_SESSION['name']    = $user['fname'] . ' ' . $user['lname'];
$_SESSION['role']    = $user['role'];

// redirect based on role
if ($user['role'] == 'student')    header('Location: ../home.html');
if ($user['role'] == 'instructor') header('Location: ../instructor/dashboard.html');
if ($user['role'] == 'admin')      header('Location: ../admin/dashboard.html');
exit;
?>