<?php
session_start();
include '../php/db.php';
include '../php/session_guard.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];

$query = "SELECT id, fname, lname, email, age, role FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 1) {
    $user = mysqli_fetch_assoc($result);
    echo json_encode(['success' => true, 'data' => $user]);
} else {
    echo json_encode(['success' => false, 'message' => 'User not found']);
    // FIXED: missing closing brace was here — now it's below
}
?>