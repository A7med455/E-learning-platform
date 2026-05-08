<?php
session_start();
include '../php/db.php';
include '../php/session_guard.php';

$user_id = $_SESSION['user_id'];

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$age = intval($_POST['age']);
$password = $_POST['password'];

// validation
if (empty($fname) || empty($lname) || empty($email) || $age <= 0) {
    header('Location: ../profile.html?error=fields');   // CHANGED
    exit;
}

if (!empty($password) && strlen($password) < 6) {
    header('Location: ../profile.html?error=password');   // CHANGED
    exit;
}

// update query
if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET fname='$fname', lname='$lname', email='$email', age=$age, password='$hashed_password' WHERE id=$user_id";
} else {
    $query = "UPDATE users SET fname='$fname', lname='$lname', email='$email', age=$age WHERE id=$user_id";
}

if (mysqli_query($conn, $query)) {
    $_SESSION['name'] = $fname . ' ' . $lname;
    header('Location: ../profile.html?msg=updated');   // CHANGED
} else {
    header('Location: ../profile.html?error=failed');   // CHANGED
}
exit;
?>