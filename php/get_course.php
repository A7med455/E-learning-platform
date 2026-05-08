<?php
// FIXED: include path (file is in php/ folder)
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid course ID.']);
    exit;
}

$id = (int) $_GET['id'];

$sql = "SELECT id, title, description, price, image_url, category, instructor_id
        FROM courses
        WHERE id = $id AND status = 'approved'";

$result = mysqli_query($conn, $sql);

if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['success' => false, 'message' => 'Course not found.']);
    exit;
}

$course = mysqli_fetch_assoc($result);

echo json_encode(['success' => true, 'data' => $course]);
?>