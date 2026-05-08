<?php
include 'db.php';
include 'session_guard.php';

$user_id = $_SESSION['user_id'];

// check if course_id is passed
if (!isset($_POST['course_id'])) {
    header('Location: ../courses.html?error=missing');   // CHANGED: redirect instead of json
    exit;
}

$course_id = (int) $_POST['course_id'];   // CHANGED: cast to int instead of escape string

// check if already enrolled
$check_sql = "SELECT * FROM enrollments
              WHERE user_id = '$user_id'
              AND course_id = '$course_id'";
$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    header('Location: ../course-detail.html?id=' . $course_id . '&error=already_enrolled');   // CHANGED
    exit;
}

// get course price
$course_sql = "SELECT price FROM courses WHERE id = '$course_id'";
$course_result = mysqli_query($conn, $course_sql);

if (!$course = mysqli_fetch_assoc($course_result)) {
    header('Location: ../courses.html?error=not_found');   // CHANGED
    exit;
}

$price = $course['price'];

// get user balance
$wallet_sql = "SELECT balance FROM wallets WHERE user_id = '$user_id'";
$wallet_result = mysqli_query($conn, $wallet_sql);
$wallet = mysqli_fetch_assoc($wallet_result);
$balance = $wallet['balance'];

// check balance
if ($balance < $price) {
    header('Location: ../course-detail.html?id=' . $course_id . '&error=insufficient');   // CHANGED
    exit;
}

// deduct money
$new_balance = $balance - $price;
$update_wallet = "UPDATE wallets
                  SET balance = '$new_balance'
                  WHERE user_id = '$user_id'";
mysqli_query($conn, $update_wallet);

// enroll user
$enroll_sql = "INSERT INTO enrollments(user_id, course_id, enrolled_at)
               VALUES('$user_id', '$course_id', NOW())";
mysqli_query($conn, $enroll_sql);

// CHANGED: redirect to my-courses on success
header('Location: ../my-courses.html?msg=purchased');
exit;
?>