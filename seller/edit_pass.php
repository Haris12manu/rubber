<?php
// เริ่มต้น session
session_start();
require '../api/db_connect.php';  // เชื่อมต่อกับฐานข้อมูล

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // รับค่า token เพื่อป้องกัน CSRF
    if (!isset($_POST['_token']) || $_POST['_token'] !== $_SESSION['_token']) {
        die('Invalid CSRF token');
    }

    // รับค่าจากฟอร์ม
    $current_password = $_POST['current_password'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['password_confirmation'];

    // ตรวจสอบผู้ใช้ที่ล็อกอินอยู่
    $user_id = $_SESSION['user_id'];  // สมมติว่าเราเก็บ user_id ใน session
    $query = "SELECT password, user_type FROM users WHERE user_id = ?";  // เปลี่ยนจาก id เป็น user_id
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($db_password, $user_type);
    $stmt->fetch();

    // ตรวจสอบว่าผู้ใช้เป็น 'Seller'
    if ($user_type !== 'Seller') {
        die('คุณไม่มีสิทธิ์เปลี่ยนรหัสผ่าน');
    }

    // ตรวจสอบว่ารหัสผ่านเดิมถูกต้องหรือไม่
    if (!password_verify($current_password, $db_password)) {
        die('รหัสผ่านเดิมไม่ถูกต้อง');
    }

    // ตรวจสอบความสอดคล้องของรหัสผ่านใหม่
    if ($new_password !== $confirm_password) {
        die('รหัสผ่านใหม่ไม่ตรงกัน');
    }

    // แฮชรหัสผ่านใหม่
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // อัปเดตรหัสผ่านในฐานข้อมูล
    $update_query = "UPDATE users SET password = ? WHERE user_id = ?";  // เปลี่ยนจาก id เป็น user_id
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("si", $hashed_password, $user_id);

    if ($update_stmt->execute()) {
        // แจ้งเตือนด้วย JavaScript
        echo "<script>
                alert('เปลี่ยนรหัสผ่านเรียบร้อยแล้ว!');
                window.location.href = 'index.php?page=reset_pass.php'; // เปลี่ยนเส้นทางไปยัง reset_pass.php
              </script>";
    } else {
        echo "เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน";
    }
}
?>
