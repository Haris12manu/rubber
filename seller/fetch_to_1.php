<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

require('../api/db_connect.php');

// ดึง seller_id จากฐานข้อมูลตาม user_id
$user_id = $_SESSION['user_id'];
$sql_seller = "SELECT s.seller_id FROM sellers s INNER JOIN users u ON u.user_id = s.user_id WHERE u.user_id = ?";
$stmt = $conn->prepare($sql_seller);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($seller_id);
$stmt->fetch();
$stmt->close();

// ตรวจสอบว่า seller_id ถูกดึงมาได้หรือไม่
if (!$seller_id) {
    echo "ไม่พบข้อมูลผู้ขาย";
    exit();
}

// คำสั่ง SQL เพื่อดึงข้อมูลจากตาราง rubber_purchases โดยดูทั้ง seller_id และ employee_id
$sql = "
    SELECT MONTH(rp.purchase_date) AS month, SUM(rp.total_price) AS total_price, SUM(rp.quantity) AS total_quantity
    FROM rubber_purchases rp
    LEFT JOIN employees e ON rp.employee_id = e.employee_id
    WHERE rp.seller_id = ? OR e.seller_id = ? 
    GROUP BY MONTH(rp.purchase_date)
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $seller_id, $seller_id); // ส่งค่า seller_id สำหรับทั้ง seller_id และ employee_id ที่เกี่ยวข้อง
$stmt->execute();
$result = $stmt->get_result();

// เตรียมตัวแปรสำหรับข้อมูลแต่ละเดือน
$months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
$quantities_current_year = array_fill(0, 12, null); // ใส่ค่าเริ่มต้นเป็น null สำหรับทุกเดือน
$prices_current_year = array_fill(0, 12, null);

// เก็บข้อมูลที่ดึงมาจากฐานข้อมูล
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $month_index = $row['month'] - 1; // ค่าดัชนีของเดือนใน array (0-11)
        $quantities_current_year[$month_index] = $row['total_quantity'];
        $prices_current_year[$month_index] = $row['total_price'];
    }
}

// กรองข้อมูลเพื่อเอาเฉพาะเดือนที่มีข้อมูล
$filtered_months = [];
$filtered_quantities = [];
$filtered_prices = [];

for ($i = 0; $i < count($months); $i++) {
    if ($quantities_current_year[$i] !== null || $prices_current_year[$i] !== null) {
        $filtered_months[] = $months[$i];
        $filtered_quantities[] = $quantities_current_year[$i];
        $filtered_prices[] = $prices_current_year[$i];
    }
}

// ปิดการเชื่อมต่อฐานข้อมูล
$stmt->close();
$conn->close();

// ตอนนี้คุณสามารถใช้ข้อมูล $filtered_months, $filtered_quantities, และ $filtered_prices ไปใช้ใน Chart.js หรือที่อื่น ๆ ตามต้องการ
?>
