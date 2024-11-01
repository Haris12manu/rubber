<?php
// เริ่มเซสชันถ้ายังไม่ได้เริ่ม
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เชื่อมต่อฐานข้อมูล
require_once('../api/db_connect.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบแล้วหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// สืบค้น `store_id` จากฐานข้อมูลตาม `user_id`
$stmt = $conn->prepare("SELECT store_id FROM purchasing_stores WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

// ตรวจสอบว่าพบ `store_id` หรือไม่
if (empty($store_id)) {
    die("ไม่พบข้อมูลร้านค้า");
}

// ดึงข้อมูลตาม `store_id` ที่ค้นพบ
$months = [
    'มกราคม' => ['dry' => 0, 'wet' => 0],
    'กุมภาพันธ์' => ['dry' => 0, 'wet' => 0],
    'มีนาคม' => ['dry' => 0, 'wet' => 0],
    'เมษายน' => ['dry' => 0, 'wet' => 0],
    'พฤษภาคม' => ['dry' => 0, 'wet' => 0],
    'มิถุนายน' => ['dry' => 0, 'wet' => 0],
    'กรกฎาคม' => ['dry' => 0, 'wet' => 0],
    'สิงหาคม' => ['dry' => 0, 'wet' => 0],
    'กันยายน' => ['dry' => 0, 'wet' => 0],
    'ตุลาคม' => ['dry' => 0, 'wet' => 0],
    'พฤศจิกายน' => ['dry' => 0, 'wet' => 0],
    'ธันวาคม' => ['dry' => 0, 'wet' => 0],
];

// เขียนคำสั่ง SQL เพื่อดึงข้อมูลและคำนวณจำนวนรวมของ quantity ตามเดือน
$sql = "
SELECT 
    DATE_FORMAT(purchase_date, '%m') AS month_num,
    rubber_type,
    SUM(quantity) AS total_quantity
FROM 
    rubber_purchases
WHERE 
    YEAR(purchase_date) = 2024 -- เปลี่ยนเป็นปีที่ต้องการ
    AND store_id = ? -- เพิ่มเงื่อนไขสำหรับ filter ตาม store_id
GROUP BY 
    month_num, rubber_type
ORDER BY 
    month_num, rubber_type
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $store_id); // กำหนดค่า store_id
$stmt->execute();
$stmt->bind_result($month_num, $rubber_type, $total_quantity);

// ดึงข้อมูลและจัดเก็บลงใน array
while ($stmt->fetch()) {
    $month_index = (int)$month_num;
    $month_name = array_keys($months)[$month_index - 1]; // หาชื่อเดือนตามหมายเลข

    if ($rubber_type === 'Dry') {
        $months[$month_name]['dry'] += $total_quantity; // รวมจำนวน Dry
    } elseif ($rubber_type === 'Wet') {
        $months[$month_name]['wet'] += $total_quantity; // รวมจำนวน Wet
    }
}

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();

// แยกข้อมูล label และ data
$labels = array_keys($months);
$data_dry = array_column($months, 'dry');
$data_wet = array_column($months, 'wet');

// คำนวณข้อมูลสำหรับกราฟรวม
$data_total = array_map(function($dry, $wet) {
    return $dry + $wet;
}, $data_dry, $data_wet);

// แปลงปีเป็น พ.ศ.
$current_year = 2024; // เปลี่ยนเป็นปีที่ต้องการ
$thai_year = $current_year + 543;

// ส่งข้อมูลออกมาในรูปแบบ JSON
echo json_encode([
    'labels' => $labels,
    'data_dry' => $data_dry,
    'data_wet' => $data_wet,
    'data_total' => $data_total,
    'year' => $thai_year
]);
?>
