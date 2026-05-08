<?php
session_start();
include 'db.php';

$fname    = $_POST['fname'];
$lname    = $_POST['lname'];
$age      = $_POST['age'];
$email    = $_POST['email'];
$password = $_POST['password'];
$role     = $_POST['role'];

// check if email already exists
$check  = "SELECT * FROM users WHERE email = '$email'";
$result = mysqli_query($conn, $check);

if (mysqli_num_rows($result) > 0) {
    // CHANGED: redirect back to signup with error
    header('Location: ../signup.html?error=email');
    exit;
}

// save user
$sql = "INSERT INTO users (fname, lname, age, email, password, role, status)
        VALUES ('$fname', '$lname', '$age', '$email', '$password', '$role', 1)";

mysqli_query($conn, $sql);

// if student create wallet
if ($role === 'student') {
    $userId = mysqli_insert_id($conn);
    mysqli_query($conn, "INSERT INTO wallets (user_id, balance) VALUES ('$userId', 0)");
}

// CHANGED: redirect to login page on success
header('Location: ../login.html?msg=signup_success');
exit;
?>