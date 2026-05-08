<?php
include 'db.php';
include 'session_guard.php';

$role      = $_SESSION['role'];
$user_id   = $_SESSION['user_id'];
$course_id = (int) ($_POST['course_id'] ?? 0);

if ($role !== 'instructor' && $role !== 'admin') {
    header('Location: ../login.html');
    exit;
}

if ($course_id === 0) {
    header('Location: ../index.html');
    exit;
}

if ($role === 'instructor') {
    $check = mysqli_query($conn, "SELECT id FROM courses WHERE id = $course_id AND instructor_id = $user_id");
    if (!$check || mysqli_num_rows($check) === 0) {
        header('Location: ../Instructor_Dashboard.html?error=not_found');
        exit;
    }
}

mysqli_query($conn, "DELETE FROM lessons WHERE course_id = $course_id");
mysqli_query($conn, "DELETE FROM enrollments WHERE course_id = $course_id");

if (mysqli_query($conn, "DELETE FROM courses WHERE id = $course_id")) {
    if ($role === 'admin') header('Location: ../admin/courses.php?msg=deleted');
    else header('Location: ../Instructor_Dashboard.html?msg=deleted');
} else {
    if ($role === 'admin') header('Location: ../admin/courses.php?error=failed');
    else header('Location: ../Instructor_Dashboard.html?error=failed');
}
exit;
?>