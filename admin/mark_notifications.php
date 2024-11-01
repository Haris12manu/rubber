<?php
include_once('../api/db_connect.php');

if (isset($_POST['mark_as_read'])) {
    $sql_update = "UPDATE users SET is_read = 1 WHERE is_read = 0";
    $conn->query($sql_update);
}

header("Location: your_notifications_page.php");
exit();
?>
