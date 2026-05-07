<?php
include '../php/db.php';
include '../php/session_guard.php';

if ($_SESSION['role'] !== 'instructor') {
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}
$title       = mysqli_real_escape_string($conn, trim($_POST['title'] ?? ''));
$description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
$price       = trim($_POST['price'] ?? '');
$category    = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
$image_url   = mysqli_real_escape_string($conn, trim($_POST['image_url'] ?? ''));
$instructor_id = $_SESSION['user_id'];
if (empty($title) || empty($description) || empty($category) || $price === '') {
    echo json_encode(['success' => false, 'message' => 'All fields are required.']);
    exit;
}
if (!is_numeric($price) || $price < 0) {
    echo json_encode(['success' => false, 'message' => 'Price must be a valid number.']);
    exit;
}
$price = (float) $price;
$sql = "INSERT INTO courses (title, description, price, image_url, category, instructor_id, status)
        VALUES ('$title', '$description', $price, '$image_url', '$category', $instructor_id, 'pending')";
if (mysqli_query($conn, $sql)) {
    echo json_encode(['success' => true, 'message' => 'Course submitted for review.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to create course.']);
}