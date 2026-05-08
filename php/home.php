<?php
// Only students can access this page
$required_role = 'student';
include 'php/session_guard.php';

// Student is logged in — show home.html
header('Location: home.html');
exit;
?>