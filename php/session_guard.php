<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

if (isset($required_role) && $_SESSION['role'] !== $required_role) {
    if ($_SESSION['role'] == 'student')    header('Location: ../home.html');
    if ($_SESSION['role'] == 'instructor') header('Location: ../Instructor_Dashboard.html');
    if ($_SESSION['role'] == 'admin')      header('Location: ../Admin_Dashboard.html');
    exit;
}
?>