<?php
include 'db.php';
include 'session_guard.php';

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'];
$course_id = $_GET['course_id'] ?? 0;

// CHECK IF STUDENT IS ENROLLED
$check = mysqli_query($conn,
    "SELECT * FROM enrollments
     WHERE user_id = '$user_id'
     AND course_id = '$course_id'"
);

if (mysqli_num_rows($check) == 0) {
    echo json_encode([
        'success' => false,
        'message' => 'You are not enrolled in this course'
    ]);
    exit();
}

// GET LESSONS
$result = mysqli_query($conn,
    "SELECT * FROM lessons
     WHERE course_id = '$course_id'"
);

$lessons = [];
while ($row = mysqli_fetch_assoc($result)) {
    $lessons[] = $row;
}

// RETURN JSON RESPONSE
echo json_encode([
    'success' => true,
    'data' => $lessons
]);
?>