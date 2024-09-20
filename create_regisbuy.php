<?php
include_once('./api/db_connect.php');

// รับข้อมูลจากฟอร์ม
$username = $_POST['username'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // เข้ารหัสรหัสผ่าน
$user_type = 'Purchasing Store'; // กำหนด user_type เป็น seller
$created_at = date('Y-m-d H:i:s'); // กำหนดค่า created_at

// ตรวจสอบว่าผู้ใช้มีอยู่แล้วหรือไม่
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die('Prepare failed: ' . htmlspecialchars($conn->error));
}
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<script>alert('ผู้ใช้นี้มีอยู่แล้ว'); window.location.href='registe.php';</script>";
} else {
    // เพิ่มผู้ใช้ใหม่ในฐานข้อมูล
    $sql = "INSERT INTO users (username, email, phone_number, password, user_type, created_at) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("ssssss", $username, $email, $phone_number, $password, $user_type, $created_at);

    if ($stmt->execute()) {
        $user_id = $stmt->insert_id;

        // เพิ่มข้อมูลลงในตาราง sellers ถ้า user_type เป็น 'seller'
        if ($user_type === 'Purchasing Store') {
            // ตรวจสอบ seller_id ล่าสุด
            $sql = "SELECT MAX(store_id) AS max_store_id FROM `purchasing_stores`";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $last_seller_id = $row['max_store_id'] ?? 0;
            $new_seller_id = max(3, $last_seller_id + 1); // เริ่มนับจาก 3

            // เพิ่มข้อมูลลงในตาราง sellers
            $sql = "INSERT INTO purchasing_stores (store_id, user_id, store_name) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                die('Prepare failed: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("iis", $new_store_id, $user_id, $username);

            if ($stmt->execute()) {
                echo "<script>alert('ลงทะเบียนสำเร็จ! กรุณารอการอนุมัติจากแอดมิน'); window.location.href='login.php';</script>";
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            echo "<script>alert('ลงทะเบียนสำเร็จ! กรุณารอการอนุมัติจากแอดมิน'); window.location.href='login.php';</script>";
            exit();
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
