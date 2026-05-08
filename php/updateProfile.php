<?php
session_start();
// FIXED: include paths
include 'db.php';
include 'session_guard.php';

$user_id = $_SESSION['user_id'];

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$age = intval($_POST['age']);
$password = $_POST['password'];

if (empty($fname) || empty($lname) || empty($email) || $age <= 0) {
    header('Location: ../profile.html?error=fields');
    exit;
}

if (!empty($password) && strlen($password) < 6) {
    header('Location: ../profile.html?error=password');
    exit;
}

if (!empty($password)) {
    $query = "UPDATE users SET fname='$fname', lname='$lname', email='$email', age=$age, password='$password' WHERE id=$user_id";
} else {
    $query = "UPDATE users SET fname='$fname', lname='$lname', email='$email', age=$age WHERE id=$user_id";
}

if (mysqli_query($conn, $query)) {
    $_SESSION['name'] = $fname . ' ' . $lname;
    header('Location: ../profile.html?msg=updated');
} else {
    header('Location: ../profile.html?error=failed');
}
exit;
?>