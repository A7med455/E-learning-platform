<?php
session_start(); //starting the file
include '../php/db.php';
include '../php/session_guard.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

$fname = $_POST['fname'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$age = intval($_POST['age']); //ensures only numbers are inserted, otherwise it breaks
$password = $_POST['password'];

if (empty($fname) || empty($lname) || empty($email) || $age <= 0) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']); //validation
    exit;
}

if (!empty($password) && strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters']); //more validation
    exit;
}

if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $query = "UPDATE users SET fname='$fname', lname='$lname', email='$email', age=$age, password='$hashed_password' WHERE id=$user_id";
} else {
    $query = "UPDATE users SET fname='$fname', lname='$lname', email='$email', age=$age WHERE id=$user_id";
}

if (mysqli_query($conn, $query)) {
    $_SESSION['name'] = $fname . ' ' . $lname;
    echo json_encode(['success' => true, 'message' => 'Profile updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile']);
}