<?php
/*to use in ur file you just do:
<?php
include '../php/db.php';
include '../php/session_guard.php';

rest of ur code
?>
*/

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit;
}

?>
