<?php
// FIXED: include paths (file is in php/ folder, not a subfolder)
include 'db.php';
include 'session_guard.php';

// instructors only
if ($_SESSION['role'] !== 'instructor') {
    header('Location: ../login.html');   // CHANGED: redirect instead of json
    exit;
}

$title       = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
$description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
$price       = trim($_POST['price'] ?? '');
$category    = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
$image_url   = mysqli_real_escape_string($conn, trim($_POST['image_url'] ?? ''));
$instructor_id = $_SESSION['user_id'];

if (empty($title) || empty($description) || empty($category) || $price === '') {
    header('Location: ../instructor/add-course.html?error=fields');   // CHANGED
    exit;
}

if (!is_numeric($price) || $price < 0) {
    header('Location: ../instructor/add-course.html?error=price');   // CHANGED
    exit;
}

$price = (float) $price;

$sql = "INSERT INTO courses (title, description, price, image_url, category, instructor_id, status)
        VALUES ('$title', '$description', $price, '$image_url', '$category', $instructor_id, 'pending')";

if (mysqli_query($conn, $sql)) {
    header('Location: ../instructor/dashboard.html?msg=added');   // CHANGED
} else {
    header('Location: ../instructor/add-course.html?error=failed');   // CHANGED
}
exit;
?>