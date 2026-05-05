<?php
// connect to MySQL
// parameter must be changed
$conn = mysqli_connect("localhost", "root", "", "elearning_db");

// if connection failed, stop everything and show error
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>