<?php
// FIXED: include paths
include 'db.php';
include 'session_guard.php';

$role      = $_SESSION['role'];
$user_id   = $_SESSION['user_id'];
$course_id = (int) ($_POST['course_id'] ?? 0);

// only instructors and admin can delete
if ($role !== 'instructor' && $role !== 'admin') {
    header('Location: ../login.html');   // CHANGED
    exit;
}

if ($course_id === 0) {
    header('Location: ../index.html');   // CHANGED
    exit;
}

if ($role === 'instructor') {
    $check = mysqli_query($conn, "SELECT id FROM courses WHERE id = $course_id AND instructor_id = $user_id");
    if (!$check || mysqli_num_rows($check) === 0) {
        header('Location: ../instructor/dashboard.html?error=not_found');   // CHANGED
        exit;
    }
}

// delete related data first
mysqli_query($conn, "DELETE FROM lessons WHERE course_id = $course_id");
mysqli_query($conn, "DELETE FROM enrollments WHERE course_id = $course_id");

if (mysqli_query($conn, "DELETE FROM courses WHERE id = $course_id")) {
    // redirect based on role
    if ($role === 'admin') header('Location: ../admin/courses.html?msg=deleted');   // CHANGED
    else header('Location: ../instructor/dashboard.html?msg=deleted');   // CHANGED
} else {
    if ($role === 'admin') header('Location: ../admin/courses.html?error=failed');   // CHANGED
    else header('Location: ../instructor/dashboard.html?error=failed');   // CHANGED
}
exit;
?>