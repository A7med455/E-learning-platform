<?php
include '../php/db.php';
include '../php/session_guard.php';

$role      = $_SESSION['role'];
$user_id   = $_SESSION['user_id'];
$course_id = (int) ($_POST['course_id'] ?? 0);

//only instructors and admincan delete
if ($role !== 'instructor' && $role !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}
if ($course_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid course ID.']);
    exit;
}
if ($role === 'instructor') {
    $check = mysqli_query($conn, "SELECT id FROM courses WHERE id = $course_id AND instructor_id = $user_id");
    if (!$check || mysqli_num_rows($check) === 0) {
        echo json_encode(['success' => false, 'message' => 'Course not found or access denied.']);
        exit;
    }
}
mysqli_query($conn, "DELETE FROM lessons WHERE course_id = $course_id");
mysqli_query($conn, "DELETE FROM enrollments WHERE course_id = $course_id");
if (mysqli_query($conn, "DELETE FROM courses WHERE id = $course_id")) {
    echo json_encode(['success' => true, 'message' => 'Course deleted.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete course.']);
}
