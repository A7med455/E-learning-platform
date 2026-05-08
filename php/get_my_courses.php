<?php
include 'db.php';
include 'session_guard.php';

header("Content-Type: application/json");

$user_id = $_SESSION['user_id'];

$sql = "SELECT courses.id, courses.title, courses.image_url, courses.category
        FROM enrollments
        JOIN courses ON enrollments.course_id = courses.id
        WHERE enrollments.user_id = '$user_id'";

$result = mysqli_query($conn, $sql);

$courses = [];
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row;
}

// FIXED: success was false — changed to true
echo json_encode([
    'success' => true,
    'data' => $courses
]);
?>