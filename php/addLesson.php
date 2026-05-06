<?php
include 'db.php';
include 'session_guard.php';

header('Content-Type : application/json');

$course_id=$_POST['course_id'];
$title =mysqli_real_escape_string($conn,$_POST['title']);
$type=$_POST['type'];

$video_url=''
$video_name = '';
// if url selected
if($type=='url'){
    if(empty($_POST['videp_url'])){
        echo json_encode([
            'success'=>false,
            'message'=>'video url is required'
        ]);
        exit;
    }
    $video_url=mysqli_real_escape_string($conn,$_POST['video_url']);
}
//if file selected
if($type=='file'){
    if(empty($_FILES['video_file']['name'])){
        echo json_encode([
            'success'=>false,
            'message'=>'please upload a file'
        ]);
        exit;
    }
    $file_name =$_FILES['video_file']['name'];
    $tmp_name =$_FILES['video_file']['tmp_name'];

    $target="../uploads/".$file_name;
    if(move_uploaded_file($tmp_name,$target)){
        $video_name=$file_name;
    } else {
        echo json_encode([
            'success'=>false,
            'message'=>' upload failed'
        ]);
        exit;
    }
}
$query=" INSERT INTO  lessons(course_id,title,video_url,video_name)
Values ('$course_id' , '$title','$video_url','$video_name')";

if(mysqli_query($conn,$query)){
    echo json_encode([
        'success'=>true,
        'message'=>'Database error'
    ]);
}