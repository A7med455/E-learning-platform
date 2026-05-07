<?php
include '../php/db.php';
include '../php/session_guard.php';
//instructors only//
if ($_SESSION['role'] !== 'instructor') {
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
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
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if (!is_numeric($price) || $price < 0) {
    echo json_encode(['success' => false, 'message' => 'Price must be a valid number.']);
    exit;
}
$price = (float) $price;
$check = mysqli_query($conn, "SELECT id FROM courses WHERE id = $course_id AND instructor_id = $instructor_id");
if (!$check || mysqli_num_rows($check) === 0) {
    echo json_encode(['success' => false, 'message' => 'Course not found or access denied.']);
    exit;
}
$sql = "UPDATE courses 
        SET title = '$title', description = '$description', price = $price, 
            image_url = '$image_url', category = '$category'
        WHERE id = $course_id AND instructor_id = $instructor_id";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true, 'message' => 'Course updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update course.']);
}