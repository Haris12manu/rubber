<?php
// เรียกใช้การเชื่อมต่อฐานข้อมูลจากไฟล์ db_connect.php
include '../api/db_connect.php';

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to get the data
$sql = "SELECT purchase_id, store_id, seller_id, employee_id, rubber_type, quantity, price_per_unit, total_price, purchase_date, sale_status 
        FROM rubber_purchases";

$result = $conn->query($sql);

$sellerData = [];
$employeeData = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if (!empty($row['seller_id']) && empty($row['employee_id'])) {
            // ถ้า seller_id มีอยู่ในระบบและไม่มี employee_id ให้แสดงข้อมูลใน sellerData
            $sellerData[$row['seller_id']][] = $row;
        } elseif (!empty($row['seller_id']) && !empty($row['employee_id'])) {
            // ถ้า seller_id และ employee_id มีอยู่ทั้งคู่ ให้แสดงข้อมูลใน employeeData
            $employeeData[$row['employee_id']][] = $row;
        }
    }
} else {
    // หากไม่มีข้อมูล
    echo json_encode(['message' => 'No data found']);
    exit();
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();

$response = [
    "sellerData" => $sellerData,
    "employeeData" => $employeeData,
];

// ส่งข้อมูลกลับในรูปแบบ JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
