<?php
// FIXED: include paths
include 'db.php';
include 'session_guard.php';

// instructors only
if ($_SESSION['role'] !== 'instructor') {
    header('Location: ../login.html');   // CHANGED
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
    header('Location: ../instructor/edit-course.html?id=' . $course_id . '&error=fields');   // CHANGED
    exit;
}

if (!is_numeric($price) || $price < 0) {
    header('Location: ../instructor/edit-course.html?id=' . $course_id . '&error=price');   // CHANGED
    exit;
}

$price = (float) $price;

$check = mysqli_query($conn, "SELECT id FROM courses WHERE id = $course_id AND instructor_id = $instructor_id");
if (!$check || mysqli_num_rows($check) === 0) {
    header('Location: ../instructor/dashboard.html?error=not_found');   // CHANGED
    exit;
}

$sql = "UPDATE courses
        SET title = '$title', description = '$description', price = $price,
            image_url = '$image_url', category = '$category'
        WHERE id = $course_id AND instructor_id = $instructor_id";

if (mysqli_query($conn, $sql)) {
    header('Location: ../instructor/dashboard.html?msg=updated');   // CHANGED
} else {
    header('Location: ../instructor/edit-course.html?id=' . $course_id . '&error=failed');   // CHANGED
}
exit;
?>