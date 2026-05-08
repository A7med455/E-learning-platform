<?php
// start the session so we can access it
session_start();

// delete everything saved in the session (user_id, name, role)
session_destroy();

// send the user back to the login page
header("Location: ../login.html");
exit;   // ADDED: stop script after redirect
?>