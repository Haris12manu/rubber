<?php
require '../api/db_connect.php'; // เชื่อมต่อฐานข้อมูล

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Queries
$queries = [
    'Total Users' => "SELECT COUNT(*) AS count FROM users", // นับจำนวนผู้ใช้ทั้งหมด
    'Purchasing Store' => "SELECT COUNT(*) AS count FROM users WHERE user_type = 'Purchasing Store'",
    'Seller' => "SELECT COUNT(*) AS count FROM users WHERE user_type = 'Seller'",
    'Employees' => "SELECT COUNT(*) AS count FROM employees",
    'Admin' => "SELECT COUNT(*) AS count FROM users WHERE user_type = 'Admin'"
];


$counts = [];
foreach ($queries as $label => $query) {
    $result = $conn->query($query);
    
    if ($result) { // ตรวจสอบว่า query สำเร็จหรือไม่
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $counts[$label] = $row['count'];
        } else {
            $counts[$label] = 0;
        }
    } else {
        // เพิ่มการจัดการข้อผิดพลาดในกรณีที่ query ไม่สำเร็จ
        error_log("Query failed for $label: " . $conn->error);
        $counts[$label] = 'Error';
    }
}

$conn->close();

// ส่งผลลัพธ์ออกเป็น JSON
header('Content-Type: application/json');
echo json_encode($counts);
?>
