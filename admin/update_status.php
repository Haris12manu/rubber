<?php
require '../api/db_connect.php';

if (isset($_GET['user_id']) && isset($_GET['status'])) {
    $user_id = $_GET['user_id'];
    $status = $_GET['status'];

    // อัพเดทสถานะในฐานข้อมูล
    $sql = "UPDATE users SET status = ? WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $user_id);

    if ($stmt->execute()) {
        $stmt->close();
        // เก็บข้อความแจ้งเตือนใน session
        session_start();
        $_SESSION['message'] = "อัพเดทสถานะเรียบร้อย";
        $_SESSION['msg_type'] = "success"; // หรือ "danger" สำหรับข้อผิดพลาด
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}

?>
