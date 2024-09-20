<?php
include_once('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $status = $_POST['status'];

    $sql = "UPDATE users SET status = ?, updated_at = NOW() WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $user_id);

    if ($stmt->execute()) {
        echo "Success";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
