<?php
// ============================================================
// admin_delete_user.php
// Purpose : Deletes a user and all their related data.
// Method  : POST
// Access  : Admin only
// ============================================================

include '../php/db.php';
include '../php/session_guard.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login.html');
    exit;
}

$user_id = (int) ($_POST['user_id'] ?? 0);

if ($user_id === 0) {
    header('Location: ../users.html?error=invalid');
    exit;
}

if ($user_id === (int) $_SESSION['user_id']) {
    header('Location: ../users.html?error=self');
    exit;
}

$check = mysqli_query($conn, "SELECT id, role FROM users WHERE id = $user_id");
if (!$check || mysqli_num_rows($check) === 0) {
    header('Location: ../users.html?error=notfound');
    exit;
}

$user = mysqli_fetch_assoc($check);

if ($user['role'] === 'instructor') {
    $courses = mysqli_query($conn, "SELECT id FROM courses WHERE instructor_id = $user_id");
    while ($course = mysqli_fetch_assoc($courses)) {
        $cid = $course['id'];
        mysqli_query($conn, "DELETE FROM lessons     WHERE course_id = $cid");
        mysqli_query($conn, "DELETE FROM enrollments WHERE course_id = $cid");
    }
    mysqli_query($conn, "DELETE FROM courses WHERE instructor_id = $user_id");
}

if ($user['role'] === 'student') {
    mysqli_query($conn, "DELETE FROM enrollments WHERE user_id = $user_id");
    mysqli_query($conn, "DELETE FROM wallets     WHERE user_id = $user_id");
    mysqli_query($conn, "DELETE FROM cards       WHERE user_id = $user_id");
}

if (mysqli_query($conn, "DELETE FROM users WHERE id = $user_id")) {
    header('Location: ../users.html?msg=deleted');
} else {
    header('Location: ../users.html?error=failed');
}
exit;
?>