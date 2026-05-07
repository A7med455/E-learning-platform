<?php
// ============================================================
// admin_delete_user.php
// Purpose : Deletes a user from the platform along with all
//           their related data (courses, enrollments, wallet, etc.)
// Method  : POST
// Access  : Admin only
// Returns : JSON { success, message }
// ============================================================

// Step 1: Include the database connection and session guard.
include 'php/db.php';
include 'php/session_guard.php';

// Tell the browser this response is JSON
header('Content-Type: application/json');

// Step 2: Make sure the logged-in user is an admin.
// If not, block the request immediately.
if ($_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

// Step 3: Read the user_id from the POST request.
// Cast to int to prevent SQL injection.
$user_id = (int) ($_POST['user_id'] ?? 0);

// Step 4: Validate — user_id must be a positive integer.
if ($user_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID.']);
    exit;
}

// Step 5: Prevent the admin from deleting their own account.
// Comparing the target user_id against the logged-in admin's session id.
if ($user_id === (int) $_SESSION['user_id']) {
    echo json_encode(['success' => false, 'message' => 'You cannot delete your own account.']);
    exit;
}

// Step 6: Check that the user actually exists in the database.
// We also fetch their role so we know what related data to clean up.
$check = mysqli_query($conn, "SELECT id, role FROM users WHERE id = $user_id");
if (!$check || mysqli_num_rows($check) === 0) {
    echo json_encode(['success' => false, 'message' => 'User not found.']);
    exit;
}

$user = mysqli_fetch_assoc($check);

// Step 7: If the user is an instructor, delete all their courses first.
// We must delete lessons and enrollments linked to each course before
// deleting the course itself (to avoid foreign key / orphan data issues).
if ($user['role'] === 'instructor') {
    // Get all courses created by this instructor
    $courses = mysqli_query($conn, "SELECT id FROM courses WHERE instructor_id = $user_id");
    while ($course = mysqli_fetch_assoc($courses)) {
        $cid = $course['id'];
        // Delete all lessons belonging to this course
        mysqli_query($conn, "DELETE FROM lessons     WHERE course_id = $cid");
        // Delete all student enrollments for this course
        mysqli_query($conn, "DELETE FROM enrollments WHERE course_id = $cid");
    }
    // Now safe to delete the courses themselves
    mysqli_query($conn, "DELETE FROM courses WHERE instructor_id = $user_id");
}

// Step 8: If the user is a student, delete their personal data.
// This includes their enrollment records, wallet balance, and saved cards.
if ($user['role'] === 'student') {
    mysqli_query($conn, "DELETE FROM enrollments WHERE user_id = $user_id");
    mysqli_query($conn, "DELETE FROM wallets     WHERE user_id = $user_id");
    mysqli_query($conn, "DELETE FROM cards       WHERE user_id = $user_id");
}

// Step 9: Finally, delete the user record itself from the users table.
if (mysqli_query($conn, "DELETE FROM users WHERE id = $user_id")) {
    echo json_encode(['success' => true, 'message' => 'User deleted successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}
?>