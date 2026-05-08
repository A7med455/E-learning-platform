<?php
include 'db.php';

// ADDED: check if status filter is passed (for admin pending courses)
$statusFilter = isset($_GET['status']) ? $_GET['status'] : 'approved';

$sql = "SELECT id, title, description, price, image_url, category
        FROM courses
        WHERE status = '$statusFilter'
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Failed to load courses.']);
    exit;
}
$courses = [];
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row;
}
echo json_encode(['success' => true, 'data' => $courses]);
?>