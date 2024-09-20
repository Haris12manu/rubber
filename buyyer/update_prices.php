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

$data = json_decode(file_get_contents('php://input'), true);
$success = true;

foreach ($data as $item) {
    $id = $item['id'];
    $adjusted_price = $item['adjusted_price'];

    $sql = "UPDATE store_prices SET adjusted_price = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("di", $adjusted_price, $id);
        if (!$stmt->execute()) {
            $success = false;
            break;
        }
        $stmt->close();
    } else {
        $success = false;
        break;
    }
}

$conn->close();

echo json_encode(['success' => $success]);
?>
