<?php
include 'db.php';
include 'session_guard.php';

header("Content-Type: application/json");

$user_id = $_SESSION['user_id'];

$sql = "SELECT balance FROM wallets WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {
    echo json_encode([
        'success' => true,
        'balance' => $row['balance']
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Wallet not found'
    ]);
}
?>