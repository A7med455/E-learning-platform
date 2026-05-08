<?php
// ============================================================
// admin_approve.php
// Purpose : Allows the admin to approve or reject a pending course.
// Access  : Admin only
// ============================================================

include '../php/db.php';
include '../php/session_guard.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login.html');
    exit;
}

$course_id = (int) ($_POST['course_id'] ?? 0);
$status    = $_POST['status'] ?? '';

if ($course_id === 0) {
    header('Location: ../adminpending.html?error=invalid');
    exit;
}

if ($status !== 'approved' && $status !== 'rejected') {
    header('Location: ../adminpending.html?error=status');
    exit;
}

$check = mysqli_query($conn, "SELECT id FROM courses WHERE id = $course_id AND status = 'pending'");
if (!$check || mysqli_num_rows($check) === 0) {
    header('Location: ../adminpending.html?error=notfound');
    exit;
}

$query = "UPDATE courses SET status = '$status' WHERE id = $course_id";
if (mysqli_query($conn, $query)) {
    header('Location: ../adminpending.html?msg=' . $status);
} else {
    header('Location: ../adminpending.html?error=failed');
}
exit;
?>