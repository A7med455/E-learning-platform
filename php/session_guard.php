<?php
/*
HOW TO USE IN YOUR FILE:
<?php
include '../php/db.php';
include '../php/session_guard.php';
// rest of your code
?>

FOR ROLE-SPECIFIC PAGES:
Set $required_role BEFORE including this file:
<?php
$required_role = 'student';   // only students allowed
include '../php/session_guard.php';
?>
*/

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

// ADDED: Check role if a specific role is required
if (isset($required_role) && $_SESSION['role'] !== $required_role) {
    // wrong role — send to their correct dashboard
    if ($_SESSION['role'] == 'student')    header('Location: ../home.html');
    if ($_SESSION['role'] == 'instructor') header('Location: ../instructor/dashboard.html');
    if ($_SESSION['role'] == 'admin')      header('Location: ../admin/dashboard.html');
    exit;
}
?>