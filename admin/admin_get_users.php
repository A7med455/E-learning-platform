<?php
// ============================================================
// admin_get_users.php
// Purpose : Returns a list of all registered users.
// Method  : GET
// Access  : Admin only
// Returns : JSON { success, data: [ ...users ] }
// ============================================================

// FIXED: include paths
include '../php/db.php';
include '../php/session_guard.php';

header('Content-Type: application/json');

if ($_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Access denied.']);
    exit;
}

$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
$role   = isset($_GET['role'])   ? mysqli_real_escape_string($conn, trim($_GET['role']))   : '';

$where = [];

if ($search !== '') {
    $where[] = "(fname LIKE '%$search%' OR lname LIKE '%$search%' OR email LIKE '%$search%')";
}

if ($role !== '' && $role !== 'all') {
    $where[] = "role = '$role'";
}

$whereClause = count($where) > 0 ? 'WHERE ' . implode(' AND ', $where) : '';

$query  = "SELECT id, fname, lname, email, age, role, status FROM users $whereClause ORDER BY id ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($conn)]);
    exit;
}

$users = [];
while ($row = mysqli_fetch_assoc($result)) {
    $users[] = $row;
}

echo json_encode(['success' => true, 'data' => $users]);
?>