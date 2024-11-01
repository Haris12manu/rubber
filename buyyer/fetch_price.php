<?php
// fetch_price.php
require_once('../api/db_connect.php');

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. ดึงข้อมูล price และ updated_at จากตาราง central_price
$sql = "SELECT price, updated_at FROM central_price";
$result = $conn->query($sql);

$prices = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // เก็บข้อมูล price และ updated_at ไว้ใน array
        $prices[] = [
            "price" => $row["price"],
            "updated_at" => $row["updated_at"]
        ];
    }
} else {
    $prices[] = [
        "price" => "No data available",
        "updated_at" => null
    ];
}

// ปิดการเชื่อมต่อฐานข้อมูล
$conn->close();

// ส่งข้อมูลกลับไปในรูปแบบ JSON
echo json_encode($prices);
?>
