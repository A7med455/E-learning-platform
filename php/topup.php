<?php
include 'db.php';
include 'session_guard.php';

$user_id = $_SESSION['user_id'];
$amount = $_POST['amount'] ?? 0;
$card_number = $_POST['card_number'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$expiry = $_POST['expiry'] ?? '';   // FIXED: was missing default value

// validations
if ($amount <= 0 || empty($card_number) || empty($cvv) || empty($expiry)) {
    header('Location: ../wallet.html?error=fields');   // CHANGED
    exit;
}

// extract the last 4 digits
$last_four = substr($card_number, -4);

// split expiry like actual cards
$parts = explode('-', $expiry);
$year = $parts[0];
$month = $parts[1];

// save card
mysqli_query($conn, "
    INSERT INTO cards (user_id, last_four, expiry_month, expiry_year, cvv)
    VALUES ('$user_id', '$last_four', '$month', '$year', '$cvv')
");

// check wallet
$check = mysqli_query($conn, "SELECT * FROM wallets WHERE user_id = '$user_id'");

if (mysqli_num_rows($check) == 0) {
    // create wallet if doesn't exist
    mysqli_query($conn, "INSERT INTO wallets (user_id, balance)
        VALUES ('$user_id', '$amount')");
} else {
    // add money to existing wallet
    mysqli_query($conn, "UPDATE wallets
        SET balance = balance + '$amount'
        WHERE user_id = '$user_id'");
}

// CHANGED: redirect back to wallet page
header('Location: ../wallet.html?msg=topup_success');
exit;
?>