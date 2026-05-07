<?php
// ============================================================
// admin_approve.php
// Purpose : Allows the admin to approve or reject a pending course.
// Access  : Admin only
// Returns : JSON { success, message }
// ============================================================

// Step 1: Include the database connection and session guard.
// session_guard.php will redirect anyone who is not logged in.
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

// Step 3: Read course_id and status from the POST request.
// Cast course_id to int to prevent SQL injection.
$course_id = (int) ($_POST['course_id'] ?? 0);
$status    = $_POST['status'] ?? '';

// Step 4: Validate course_id — must be a positive integer.
if ($course_id === 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid course ID.']);
    exit;
}

// Step 5: Validate status — only 'approved' or 'rejected' are allowed.
// Any other value is rejected to prevent bad data in the database.
if ($status !== 'approved' && $status !== 'rejected') {
    echo json_encode(['success' => false, 'message' => 'Invalid status. Must be approved or rejected.']);
    exit;
}

// Step 6: Check that the course exists and is still 'pending'.
// A course that was already approved or rejected should not be changed again.
$check = mysqli_query($conn, "SELECT id FROM courses WHERE id = $course_id AND status = 'pending'");
if (!$check || mysqli_num_rows($check) === 0) {
    echo json_encode(['success' => false, 'message' => 'Course not found or already reviewed.']);
    exit;
}

// Step 7: Update the course status in the database.
// This either sets it to 'approved' (visible to students) or 'rejected'.
$query = "UPDATE courses SET status = '$status' WHERE id = $course_id";
if (mysqli_query($conn, $query)) {
    echo json_encode(['success' => true, 'message' => "Course $status successfully."]);
} else {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
}
?>