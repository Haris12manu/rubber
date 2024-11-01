<?php
// เริ่ม session หากยังไม่มี session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เชื่อมต่อกับฐานข้อมูล
require('../api/db_connect.php');

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ตรวจสอบว่า store_id ที่เชื่อมโยงกับ user_id นี้คืออะไร
$query = "SELECT store_id FROM purchasing_stores WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

if (empty($store_id)) {
    die("ไม่พบข้อมูลร้านค้า");
}

// ตรวจสอบว่ามีการส่งฟอร์มหรือไม่
$start_date = isset($_GET['start']) ? $_GET['start'] : null;
$end_date = isset($_GET['end']) ? $_GET['end'] : null;

$query = "
    SELECT `sale_id`, `store_id`, `factory_name`, `quantity`, `price_per_unit`, `total_price`, `sale_date`, `rubber_percentage`, `rubber_type`
    FROM `rubber_sales`
    WHERE `store_id` = ?";

if ($start_date && $end_date) {
    $query .= " AND `sale_date` BETWEEN ? AND ?";
}

$stmt = $conn->prepare($query);

if ($start_date && $end_date) {
    $stmt->bind_param("iss", $store_id, $start_date, $end_date);
} else {
    $stmt->bind_param("i", $store_id);
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$stmt->close();
$conn->close();

// แสดงผลข้อมูลที่ได้
if (!empty($data)) {
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>ชื่อโรงงาน</th>';
    echo '<th>ปริมาณยาง (กก)</th>';
    echo '<th>ราคาต่อหน่วย (บาท/กก)</th>';
    echo '<th>ราคารวม (บาท)</th>';
    echo '<th>วันที่ขาย</th>';
    echo '<th>เปอร์เซ็นต์ความเข้มข้นของยาง (%)</th>';
    echo '<th>ประเภทของยาง</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';

    foreach ($data as $row) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['factory_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['quantity']) . '</td>';
        echo '<td>' . htmlspecialchars($row['price_per_unit']) . '</td>';
        echo '<td>' . htmlspecialchars($row['total_price']) . '</td>';
        echo '<td>' . htmlspecialchars($row['sale_date']) . '</td>';
        echo '<td>' . htmlspecialchars($row['rubber_percentage']) . '</td>';
        if ($row['rubber_type'] === 'Mixed (Dry, Wet)') {
            echo '<td>ยางร่วม</td>';
        } elseif ($row['rubber_type'] === 'Dry') {
            echo '<td>ยางแห้ง</td>';
        } elseif ($row['rubber_type'] === 'Wet') {
            echo '<td>ยางเปียก</td>';
        } else {
            echo '<td>' . htmlspecialchars($row['rubber_type']) . '</td>';
        }
        echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p class="text-center mt-4">ไม่พบข้อมูล</p>';
}
