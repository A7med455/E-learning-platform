<?php
session_start();
include 'db.php';

$email    = $_POST['email'];
$password = $_POST['password'];

// search for user with this email AND password
$sql    = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($conn, $sql);
$user   = mysqli_fetch_assoc($result);

// if no user found
if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Wrong email or password.']);
    exit;
}

// save in session
$_SESSION['user_id'] = $user['id'];
$_SESSION['name']    = $user['fname'] . ' ' . $user['lname'];
$_SESSION['role']    = $user['role'];

// send back to JS
echo json_encode(['success' => true, 'role' => $user['role']]);
?>