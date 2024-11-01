<?php
require '../api/db_connect.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // ดึงเบอร์โทรศัพท์จากฐานข้อมูล
    $sql = "SELECT phone_number FROM users WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($phone_number);
    $stmt->fetch();
    $stmt->close();

    if ($phone_number) {
        // ตั้งค่ารหัสผ่านใหม่เป็นเบอร์โทรศัพท์
        $new_password = $phone_number;
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // เข้ารหัสด้วย password_hash

        // อัพเดทรหัสผ่านในฐานข้อมูล
        $sql = "UPDATE users SET password = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashed_password, $user_id);

        if ($stmt->execute()) {
            echo "รีเซตรหัสผ่านเรียบร้อย รหัสผ่านใหม่คือ: $new_password";
            header("Location: " . $_SERVER['HTTP_REFERER']); // กลับไปยังหน้าก่อนหน้า
        } else {
            echo "เกิดข้อผิดพลาด: " . $conn->error;
        }

        $stmt->close();
    } else {
        echo "ไม่พบเบอร์โทรศัพท์สำหรับผู้ใช้นี้";
    }
}
?>
