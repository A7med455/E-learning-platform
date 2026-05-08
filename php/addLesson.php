<?php
include 'db.php';
include 'session_guard.php';

// only instructors can add lessons
if ($_SESSION['role'] !== 'instructor') {
    header('Location: ../login.html');   // CHANGED
    exit;
}

$course_id = $_POST['course_id'];
$title = mysqli_real_escape_string($conn, $_POST['title']);
$type = $_POST['type'];

$video_url = '';
$video_name = '';

// if url selected
if ($type == 'url') {
    if (empty($_POST['video_url'])) {   // FIXED: was 'videp_url'
        header('Location: ../instructor/add-lesson.html?error=url_required');   // CHANGED
        exit;
    }
    $video_url = mysqli_real_escape_string($conn, $_POST['video_url']);
}

// if file selected
if ($type == 'file') {
    if (empty($_FILES['video_file']['name'])) {
        header('Location: ../instructor/add-lesson.html?error=file_required');   // CHANGED
        exit;
    }
    $file_name = $_FILES['video_file']['name'];
    $tmp_name = $_FILES['video_file']['tmp_name'];
    $target = "../uploads/" . $file_name;

    if (move_uploaded_file($tmp_name, $target)) {
        $video_name = $file_name;
    } else {
        header('Location: ../instructor/add-lesson.html?error=upload_failed');   // CHANGED
        exit;
    }
}

// insert lesson
$query = "INSERT INTO lessons (course_id, title, video_url, video_name)
          VALUES ('$course_id', '$title', '$video_url', '$video_name')";

if (mysqli_query($conn, $query)) {
    header('Location: ../instructor/dashboard.html?msg=lesson_added');   // CHANGED: was "Database error" message
} else {
    header('Location: ../instructor/add-lesson.html?error=failed');   // CHANGED
}
exit;
?>