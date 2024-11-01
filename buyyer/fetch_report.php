<?php
include('../api/db_connect.php');

session_start(); // เริ่มต้นเซสชัน

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึง store_id ที่เกี่ยวข้องกับ user_id จากฐานข้อมูล
$stmt = $conn->prepare("SELECT store_id FROM purchasing_stores WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($store_id);
$stmt->fetch();
$stmt->close();

if (empty($store_id)) {
    die("ไม่พบข้อมูลร้านค้า");
}

$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

// ปรับ SQL query เพื่อรวมเงื่อนไข store_id
$stmt = $conn->prepare("
    SELECT rp.purchase_id, rp.store_id, rp.seller_id, rp.employee_id, rp.rubber_type, rp.quantity, rp.price_per_unit, rp.total_price, rp.purchase_date,
           s.seller_name AS seller_name, e.name AS employer_name
    FROM rubber_purchases rp
    LEFT JOIN sellers s ON rp.seller_id = s.seller_id
    LEFT JOIN employees e ON rp.employee_id = e.employee_id
    WHERE DATE(rp.purchase_date) BETWEEN ? AND ? AND rp.store_id = ?");
$stmt->bind_param("sss", $start_date, $end_date, $store_id);
$stmt->execute();
$result = $stmt->get_result();

// ... ส่วนของโค้ดที่อยู่ด้านบนยังคงเหมือนเดิม

if ($result->num_rows > 0) {
    $index = 1;
    while ($row = $result->fetch_assoc()) {
        $sellerName = "-";
        $employerName = "-";
        $rubberTypeDisplay = "";

        // ถ้ามี `seller_id` เพียงอย่างเดียว
        if (!empty($row['seller_id']) && empty($row['employee_id'])) {
            $sellerName = htmlspecialchars($row['seller_name']);
        }
        // ถ้ามีทั้ง `seller_id` และ `employee_id`
        elseif (!empty($row['employee_id']) && !empty($row['seller_id'])) {
            $sellerName = htmlspecialchars($row['employer_name']); // ชื่อของ Employee
            $employerName = htmlspecialchars($row['seller_name']); // ชื่อของ Seller (นายจ้าง)
        }

        // ตรวจสอบประเภทของยางและกำหนดข้อความที่จะแสดง
        if ($row['rubber_type'] === 'Dry') {
            $rubberTypeDisplay = 'ยางแห้ง';
        } elseif ($row['rubber_type'] === 'Wet') {
            $rubberTypeDisplay = 'ยางเปียก';
        } else {
            $rubberTypeDisplay = htmlspecialchars($row['rubber_type']); // แสดงข้อความเดิมหากไม่ใช่ Dry หรือ Wet
        }

        echo "<tr>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($index) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($sellerName) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($employerName) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($row['quantity']) .'.ก.ก'. "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($rubberTypeDisplay) . "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($row['price_per_unit']) .'.บาท'. "</td>";
        echo "<td style='text-align: center;'>" . htmlspecialchars($row['total_price']) .'.บาท'. "</td>";
        echo "<td style='text-align: center;'>" . date('d-m-', strtotime($row['purchase_date'])) . (date('Y', strtotime($row['purchase_date'])) + 543) . "</td>";

        // เพิ่มไอคอนในคอลัมน์นี้
        echo "<td style='text-align: center;'>
        <a href='index.php?page=folder1/baiset.php&purchase_id=" . htmlspecialchars($row['purchase_id']) . "' target='_blank'>
            <i class='fa fa-file-text' aria-hidden='true'></i> ดูใบเสร็จ
        </a>
      </td>";
        echo "</tr>";
        $index++;
    }
} else {
    echo "<tr><td colspan='8' style='text-align: center;'>ไม่มีข้อมูล</td></tr>";
}

// ... ส่วนของโค้ดที่อยู่ด้านล่างยังคงเหมือนเดิม


$stmt->close();
$conn->close();
