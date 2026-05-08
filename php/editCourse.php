<?php
include 'db.php';
include 'session_guard.php';

if ($_SESSION['role'] !== 'instructor') {
    header('Location: ../login.html');
    exit;
}

$course_id   = (int) ($_POST['course_id'] ?? 0);
$title       = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
$description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
$price       = trim($_POST['price'] ?? '');
$category    = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
$image_url   = mysqli_real_escape_string($conn, trim($_POST['image_url'] ?? ''));
$instructor_id = $_SESSION['user_id'];

if ($course_id === 0 || empty($title) || empty($description) || empty($category) || $price === '') {
    header('Location: ../editcourse.html?id=' . $course_id . '&error=fields');
    exit;
}

if (!is_numeric($price) || $price < 0) {
    header('Location: ../editcourse.html?id=' . $course_id . '&error=price');
    exit;
}

$price = (float) $price;

$check = mysqli_query($conn, "SELECT id FROM courses WHERE id = $course_id AND instructor_id = $instructor_id");
if (!$check || mysqli_num_rows($check) === 0) {
    header('Location: ../Instructor_Dashboard.html?error=not_found');
    exit;
}

$sql = "UPDATE courses
        SET title = '$title', description = '$description', price = $price,
            image_url = '$image_url', category = '$category'
        WHERE id = $course_id AND instructor_id = $instructor_id";

if (mysqli_query($conn, $sql)) {
    header('Location: ../Instructor_Dashboard.html?msg=updated');
} else {
    header('Location: ../editcourse.html?id=' . $course_id . '&error=failed');
}
exit;
?>