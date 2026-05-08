<?php
include 'db.php';
include 'session_guard.php';

if ($_SESSION['role'] !== 'instructor') {
    header('Location: ../login.html');
    exit;
}

$course_id = $_POST['course_id'];
$title = mysqli_real_escape_string($conn, $_POST['title']);
$type = $_POST['type'];

$video_url = '';
$video_name = '';

if ($type == 'url') {
    if (empty($_POST['video_url'])) {
        header('Location: ../addLesson.html?error=url_required');
        exit;
    }
    $video_url = mysqli_real_escape_string($conn, $_POST['video_url']);
}

if ($type == 'file') {
    if (empty($_FILES['video_file']['name'])) {
        header('Location: ../addLesson.html?error=file_required');
        exit;
    }
    $file_name = $_FILES['video_file']['name'];
    $tmp_name = $_FILES['video_file']['tmp_name'];
    $target = "../uploads/" . $file_name;

    if (move_uploaded_file($tmp_name, $target)) {
        $video_name = $file_name;
    } else {
        header('Location: ../addLesson.html?error=upload_failed');
        exit;
    }
}

$query = "INSERT INTO lessons (course_id, title, video_url, video_name)
          VALUES ('$course_id', '$title', '$video_url', '$video_name')";

if (mysqli_query($conn, $query)) {
    header('Location: ../Instructor_Dashboard.html?msg=lesson_added');
} else {
    header('Location: ../addLesson.html?error=failed');
}
exit;
?>