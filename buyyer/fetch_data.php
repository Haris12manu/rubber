<?php
// เริ่ม session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// เชื่อมต่อกับฐานข้อมูล
require('../api/db_connect.php');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับค่า year จาก GET หรือใช้ปีปัจจุบันถ้าไม่ได้ส่งมา
$selected_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// สร้าง array สำหรับจัดเก็บข้อมูลแยกตามประเภทของยาง
$data = [
    'ยางแห้ง' => array_fill(1, 12, 0),
    'ยางเปียก' => array_fill(1, 12, 0),
    'ยางร่วมทั้งหมด' => array_fill(1, 12, 0)
];

// ดึงข้อมูลจากฐานข้อมูล
$sql = "SELECT rubber_type, quantity, MONTH(purchase_date) AS month
        FROM rubber_purchases
        WHERE store_id IN (SELECT store_id FROM purchasing_stores WHERE user_id = ?)
        AND YEAR(purchase_date) = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $_SESSION['user_id'], $selected_year);
$stmt->execute();
$result = $stmt->get_result();

// ตัวแปรเพื่อเช็คว่ามีข้อมูลหรือไม่
$data_found = false;

// จัดเก็บข้อมูลตามประเภทของยางและเดือน
while ($row = $result->fetch_assoc()) {
    $data_found = true; // พบข้อมูล
    $rubber_type = htmlspecialchars($row['rubber_type']);
    $month = intval($row['month']);
    $quantity = intval($row['quantity']);

    // แปลงประเภทของยางให้เป็นข้อความที่ต้องการ
    if ($rubber_type === 'Dry') {
        $rubber_type = 'ยางแห้ง';
    } elseif ($rubber_type === 'Wet') {
        $rubber_type = 'ยางเปียก';
    } else {
        $rubber_type = 'ยางอื่นๆ';
    }

    if (isset($data[$rubber_type])) {
        $data[$rubber_type][$month] += $quantity;
    }
    $data['ยางร่วมทั้งหมด'][$month] += $quantity;
}

$stmt->close();
$conn->close();

// เริ่มสร้าง HTML เพื่อตอบกลับ
if ($data_found) {
    echo '<table class="table table-bordered mt-4">';
    echo '<thead>';
    echo '<tr style="background-color: #e4e5e6">';
    echo '<th class="text-center">ประเภท</th>';
    echo '<th class="text-center">ม.ค.</th>';
    echo '<th class="text-center">ก.พ.</th>';
    echo '<th class="text-center">มี.ค.</th>';
    echo '<th class="text-center">เม.ย.</th>';
    echo '<th class="text-center">พ.ค.</th>';
    echo '<th class="text-center">มิ.ย.</th>';
    echo '<th class="text-center">ก.ค.</th>';
    echo '<th class="text-center">ส.ค.</th>';
    echo '<th class="text-center">ก.ย.</th>';
    echo '<th class="text-center">ต.ค.</th>';
    echo '<th class="text-center">พ.ย.</th>';
    echo '<th class="text-center">ธ.ค.</th>';
    echo '<th class="text-center">รวม</th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
    foreach ($data as $rubber_type => $months) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($rubber_type) . "</td>";
        $total = 0;
        foreach ($months as $quantity) {
            echo "<td class='text-center'>" . htmlspecialchars($quantity) . "</td>";
            $total += $quantity;
        }
        echo "<td class='text-center'>" . htmlspecialchars($total) . "</td>";
        echo "</tr>";
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo '<p class="text-center text-danger mt-4">ไม่พบข้อมูลสำหรับปี ' . htmlspecialchars($selected_year + 543) . '</p>';
}
