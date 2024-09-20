<?php
session_start(); // เริ่มต้นเซสชัน

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

require('../api/db_connect.php');

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit();
}

// ดึง store_id ที่เชื่อมโยงกับ user_id ที่เข้าสู่ระบบ
$user_id = $_SESSION['user_id'];
$sql_store = "SELECT store_id FROM purchasing_stores WHERE user_id = ?";
$stmt_store = $conn->prepare($sql_store);
$stmt_store->bind_param("i", $user_id);
$stmt_store->execute();
$result_store = $stmt_store->get_result();
$store_data = $result_store->fetch_assoc();

if (!$store_data) {
    echo json_encode(['success' => false, 'message' => 'Store not found']);
    exit();
}

$store_id = $store_data['store_id'];

// Prepare and bind
$stmt = $conn->prepare("UPDATE `purchasing_stores` SET `store_name`=?, `address`=?, `phone`=? WHERE `store_id`=?");
$stmt->bind_param("sssi", $store_name, $address, $phone, $store_id);

// Set parameters and execute
$store_name = $_POST['storeName'];
$address = $_POST['address'];
$phone = $_POST['phone'];

$stmt->execute();
$stmt->close();
$conn->close();

// Redirect or display success message
header("Location: index.php?page=folder1/profile.php"); // Replace with your profile page URL
exit();
?>
