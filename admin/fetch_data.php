<?php
require '../api/db_connect.php'; // เชื่อมต่อฐานข้อมูล

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตรวจสอบว่ามีการส่งคำสั่งอัปเดตหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    
    if (isset($_POST['approve'])) {
        // อัปเดตสถานะเป็น active
        $sql = "UPDATE users SET status = 'active' WHERE id = ?";
    } elseif (isset($_POST['disapprove'])) {
        // อัปเดตสถานะเป็น pending approval
        $sql = "UPDATE users SET status = 'pending approval' WHERE id = ?";
    }

    if (isset($sql)) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id); // ป้องกัน SQL Injection
        $stmt->execute();
        $stmt->close();
    }
}

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล โดยกรองเฉพาะ Purchasing, Store, Seller และไม่รวม Admin
$sql = "SELECT user_id, username, email, phone_number, user_type, status FROM users WHERE user_type IN ('Purchasing Store', 'Seller') AND user_type != 'Admin'";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

$conn->close();
?>
