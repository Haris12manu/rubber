<?php
session_start(); // เริ่มต้น session

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php"); // ถ้าไม่ได้ล็อกอินให้กลับไปที่หน้า login
    exit();
}

// เชื่อมต่อกับฐานข้อมูล
include_once('../api/db_connect.php');

// ดึงค่า user_id และ seller_id จาก session
$user_id = $_SESSION['user_id'];

// ดึง seller_id จาก user_id ใน session
$sql_seller = "SELECT seller_id FROM sellers WHERE user_id = ?";
$stmt_seller = $conn->prepare($sql_seller);
$stmt_seller->bind_param("i", $user_id);
$stmt_seller->execute();
$result_seller = $stmt_seller->get_result();
$seller = $result_seller->fetch_assoc();
$seller_id = $seller['seller_id'];

// รับค่าจากฟอร์ม
$name = $_POST['name'];
$phone_number = $_POST['phone_number'];
$commission_percentage = $_POST['commission_percentage'];
$responsibility_area = $_POST['responsibility_area'];
$created_at = date('Y-m-d H:i:s');
$updated_at = date('Y-m-d H:i:s');

// เพิ่มข้อมูลลูกจ้างในฐานข้อมูล
$sql = "INSERT INTO employees (seller_id, name, phone_number, commission_percentage, responsibility_area, created_at, updated_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issssss", $seller_id, $name, $phone_number, $commission_percentage, $responsibility_area, $created_at, $updated_at);

if ($stmt->execute()) {
    // แสดงข้อความแจ้งเตือนแล้วรีเฟรชหน้าเดิม
    echo "<script>alert('บันทึกข้อมูลสำเร็จ'); window.location.href = '".$_SERVER['HTTP_REFERER']."';</script>";
} else {
    echo "Error: " . $stmt->error;
}
?>
