<?php
include 'db.php';
include 'session_guard.php';

header('Content-Type : application/json');

$user_id=$_SESSION['user_id'];
$amount=$_POST['amount'] ?? 0;
$card_number =$_POST['card_number'] ?? '';
$cvv=$_POST['cvv'] ??'';
$expiry =$_POST['expiry'] ??;
//validations
if($amount<=0 || empty($card_number) || empty($cvv)||empty($expiry)){
    echo json_encode([
        'success'=> false,
        'message'=>' All fields are required'
    ]);
    exit;
}
// extract the last 4 digits
$last_four=substr($card_number,-4);

//split expiry like actual cards
$parts=explode('-',$expiry);
$year=$parts[0];
$month=$parts[1];
// save card
mysqli_query($conn,"
INSERT INTO cards  (user_id, last_four, expiry_month, expiry_year, cvv)
    VALUES ('$user_id', '$last_four', '$month', '$year', '$cvv')"
    );
    //check wallet
    $check=mysqli_query($conn,"SELECT * FROM wallets WHERE user_id = '$user_id'");
    if(mysqli_num_rows($check)==0){
        mysqli_query($conn,"INSERT INTO wallets (user_id, balance)
        VALUES ('$user_id', '$amount')");
    } else{
        mysqli_query($conn,"  UPDATE wallets
        SET balance = balance + '$amount'
        WHERE user_id = '$user_id'");
    }
    echo json_encode([
        'success'=>true,
        'message'=>'Wallet topped up successfully'
    ]);
