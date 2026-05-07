<?php
// ============================================================
// admin_get_users.php
// Purpose : Returns a list of all registered users on the platform.
// Method  : GET
// Access  : Admin only
// Filters : ?search=name_or_email  &  ?role=student|instructor|admin
// Returns : JSON { success, data: [ ...users ] }
// Note    : Password is never returned for security.
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

// Step 3: Read optional filter parameters from the GET request.
// 'search' filters by first name, last name, or email.
// 'role'   filters by user role (student / instructor / admin).
// mysqli_real_escape_string is used to sanitize both values.
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$role   = isset($_GET['role'])   ? mysqli_real_escape_string($conn, trim($_GET['role']))   : '';

// Step 4: Build the WHERE clause dynamically based on which filters were provided.
// Each condition is added to the $where array, then joined with AND.
$where = [];

if ($search !== '') {
    // Match against first name, last name, or email (partial match)
    $where[] = "(fname LIKE '%$search%' OR lname LIKE '%$search%' OR email LIKE '%$search%')";
}

if ($role !== '' && $role !== 'all') {
    // Match exact role — 'all' means no role filter
    $where[] = "role = '$role'";
}

// Combine conditions into a WHERE clause, or use empty string if no filters
$whereClause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';

// Step 5: Run the SELECT query.
// We select every column except 'password' for security.
$query  = "SELECT id, fname, lname, email, age, role, status FROM users $whereClause ORDER BY id ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    exit;
}

// Step 6: Collect all rows into an array and return as JSON.
$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

echo json_encode(['success' => true, 'data' => $users]);
?>