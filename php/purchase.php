<?php
include 'db.php';
include 'session_guard.php';

header("Content-Type: application/json");

$user_id = $_SESSION['user_id'];

if (!isset($_POST['course_id'])) {
    echo json_encode([
        'success' => false,
        'message' => "Course ID missing"
    ]);
    exit();
}
$course_id = mysqli_real_escape_string($conn,$_POST['course_id']);
//check if already enrolled
$check_sql="SELECT * FROM enrollments
              WHERE user_id = '$user_id'
              AND course_id = '$course_id'";
$check_result = mysqli_query($conn,$check_sql);
if(mysqli_num_rows($check_result)>0){
    echo json_encode([
        'success'=> false,
        'message'=>"you already bought this course"
    ]);
    exit();
}
//get course price
$course_sql="SELECT price FROM courses WHERE id = '$course_id'";
$course_result = mysqli_query($conn, $course_sql);
if(!$course =mysqli_fetch_assoc($course_result)){
    echo json_encode([
        'success'=> false,
        'message'=>"course not found"
    ]);
    exit();
}
$price=$course['price'];

// get user balance
$wallet_sql="SELECT balance FROM wallets WHERE user_id = '$user_id'";
$wallet_result= mysqli_query($conn,$wallet_sql);

$wallet=mysqli_fetch_assoc($wallet_result);
$balance=$wallet['balance'];

//check balance
if($balance<$price){
    echo json_encode([
        'success'=>false,
        'message'=>"Insufficient balance"
    ]);
    exit();
}
//deduct money
$new_balance= $balance - $price;
$update_wallet="UPDATE wallets
                  SET balance = '$new_balance'
                  WHERE user_id = '$user_id'";
mysqli_query($conn,$update_wallet);

//enroll user
$enroll_sql ="INSERT INTO enrollments(user_id, course_id, enrolled_at)
               VALUES('$user_id', '$course_id', NOW())";
mysqli_query($conn,$enroll_sql);
echo json_encode([
    'success'=> true,
    'message'=>"Course purchased successfully"
]);